<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Mantener Log para depuración si es necesario

use App\Models\Pivots\ItemUser; // Importar el modelo pivot

class UserListItemController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user(); // Obtiene al usuario logueado (ej: ID 1)

        $statusFilter = $request->query('status');

        // ¡¡ESTA ES LA LÍNEA MÁS IMPORTANTE!!
        // Inicia la consulta SÓLO en los items que pertenecen a ESE usuario.
        $itemsQuery = $user->items(); 

        // Carga los datos del pivote (incluyendo el 'id' del pivot)
        $itemsQuery->withPivot('id', 'status', 'score', 'review', 'episodes_watched'); 

        if ($statusFilter) {
            $itemsQuery->wherePivot('status', $statusFilter);
        }

        $items = $itemsQuery->get(); // $items AHORA SÓLO CONTIENE items donde user_id = 1

        return view('user-list.index', ['items' => $items, 'statusFilter' => $statusFilter]);
    }
    
    public function store(Request $request)
    {
        // 1. Validamos los datos (esto ya lo teníamos)
        $validated = $request->validate([
            'api_id' => 'required|integer',
            'type' => 'required|string|in:game,anime',
            'title' => 'required|string|max:255',
            'cover_image_url' => 'required|string|max:255',
            'episodes' => 'nullable|integer',
        ]);

        // 2. Buscamos o creamos el Item (esto ya lo teníamos)
        $item = Item::firstOrCreate(
            ['api_id' => $validated['api_id'], 'type' => $validated['type']],
            [
                'title' => $validated['title'],
                'cover_image_url' => $validated['cover_image_url'],
                'episodes' => $validated['episodes'] ?? null,
            ]
        );

        // 3. Obtenemos al usuario
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 4. --- ¡AQUÍ ESTÁ LA NUEVA LÓGICA! ---
        // Verificamos si el usuario ya tiene este ítem en su colección
        $isAlreadyAdded = $user->items()->where('item_id', $item->id)->exists();

        if ($isAlreadyAdded) {
            // Si ya lo tiene, volvemos con un mensaje de "info" (color azul)
            return back()->with('info', '¡' . $item->title . ' ya estaba en tu colección!');
        } else {
            // Si no lo tiene, lo añadimos (con el estado 'Pendiente' por defecto)
            $user->items()->attach($item->id, ['status' => 'Pendiente']);
            // Y volvemos con un mensaje de "éxito" (color verde)
            return back()->with('success', '¡' . $item->title . ' ha sido añadido a tu lista!');
        }
    }

    public function edit(ItemUser $userListItem) // Laravel inyectará el modelo ItemUser (el registro pivot)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // **COMPROBACIÓN DE SEGURIDAD CRÍTICA**
        // Si el user_id del registro pivot no coincide con el usuario autenticado,
        // se redirige con un error. ESTO ES CORRECTO Y NECESARIO.
        if ($userListItem->user_id !== $user->id) {
            return redirect()->route('user-list.index')->with('error', 'No tienes permiso para editar esta entrada.');
        }

        // El ID del item global, lo obtenemos del modelo pivot inyectado
        $itemId = $userListItem->item_id; 

        // 1. Obtenemos las reseñas públicas de otros usuarios para este item.
        $publicReviews = ItemUser::query()
                                ->with(['user', 'helpfulVotes']) // Carga el autor de la reseña y los votos útiles
                                ->where('item_id', $itemId) // Usamos el item_id del pivot
                                ->whereNotNull('review')
                                ->where('review', '!=', '')
                                ->where('user_id', '!=', $user->id) // Excluir la reseña del usuario actual
                                ->latest('updated_at')
                                ->paginate(1); 

        // 2. Calculamos la puntuación promedio y el número de puntuaciones.
        $averageScoreData = DB::table('item_user')
                                ->where('item_id', $itemId) // Usamos el item_id del pivot
                                ->whereNotNull('score')
                                ->selectRaw('AVG(score) as average_score, COUNT(id) as score_count')
                                ->first();
        
        $averageScore = $averageScoreData->average_score ? round($averageScoreData->average_score, 1) : null;
        $scoreCount = $averageScoreData->score_count;
        
        return view('user-list.edit', [
            'userListItem' => $userListItem,      // El modelo pivot con sus datos (status, score, review, etc.)
            'item' => $userListItem->item,        // El modelo Item global, accedido desde el pivot (¡relación crucial!)
            'publicReviews' => $publicReviews,
            'averageScore' => $averageScore,
            'scoreCount' => $scoreCount,
        ]);
    }

    
    public function update(Request $request, ItemUser $userListItem){ 
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // **COMPROBACIÓN DE SEGURIDAD CRÍTICA**
        if ($userListItem->user_id !== $user->id) {
            return redirect()->route('user-list.index')->with('error', 'No tienes permiso para actualizar esta entrada.');
        }

        // Validamos que los datos necesarios lleguen al formulario
        // Asegúrate de que $userListItem->item está cargado o usa el ID del ítem directamente
        $maxEpisodes = $userListItem->item->episodes ?? 1000; // Asumiendo que $userListItem->item está disponible

        $validated = $request->validate([
            'status' => 'required|string|in:Pendiente,Jugando,Completado,Abandonado',
            'score' => 'nullable|integer|min:1|max:10',
            'review' => 'nullable|string|max:10000',
            'episodes_watched' => 'nullable|integer|min:0|max:' . $maxEpisodes, 
        ]);

        if ($userListItem->item->type === 'anime' && isset($validated['episodes_watched'])) {
            if ($validated['episodes_watched'] > $userListItem->item->episodes) {
                $validated['episodes_watched'] = $userListItem->item->episodes; // No permitir más episodios vistos que los que tiene el anime
            }
        }
        
        // CORRECCIÓN: Actualizar el modelo pivot directamente, ya lo tenemos inyectado
        $userListItem->update($validated); 

        // Registro de actividad
        $user->activities()->create([
            'type' => 'updated_list_item',
            'subject_id' => $userListItem->item->id,  // El ID del Item global
            'subject_type' => Item::class, 
        ]);

        // Redirige al perfil o de vuelta a la edición para ver los cambios
        return redirect()->route('user-list.edit', $userListItem->id)->with('success', '¡' . $userListItem->item->title . ' ha sido actualizado en tu lista!');
    }

    
    public function destroy(ItemUser $userListItem) 
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($userListItem->user_id !== $user->id) {
            return redirect()->route('user-list.index')->with('error', 'No tienes permiso para eliminar esta entrada.');
        }

        $title = $userListItem->item->title; // Guarda el título antes de eliminar para el mensaje de éxito

        // Eliminar el registro del pivot (item_user) directamente
        $userListItem->delete();

        // Registro de actividad (opcional, pero buena práctica)
        $user->activities()->create([
            'type' => 'deleted_list_item',
            'subject_id' => $userListItem->item->id, // El ID del Item global
            'subject_type' => Item::class, 
        ]);

        return redirect()->route('user-list.index')->with('success', '¡' . $title . ' ha sido eliminado de tu colección!');
    }

}