<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pivots\ItemUser; // ¡Importante! Asegúrate de que la ruta sea correcta
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View; // Importa View

class UserProfileController extends Controller
{
    public function show(User $user): View
    {
        // 1. Cargar la relación 'items' del usuario, asegurándonos de que Laravel use
        // el modelo ItemUser para el pivot y seleccione sus columnas.
        $user->load([
            'items' => function($query) {
                // Aquí $query es sobre el modelo App\Models\Item.
                // Le decimos qué columnas del pivot necesitamos.
                $query->withPivot('id', 'status', 'score', 'episodes_watched');

                // Si tu relación 'items' en User.php usa ->using(ItemUser::class),
                // entonces los pivots serán instancias de ItemUser y podemos cargarlos después.
            }
        ]);

        // 2. Ahora que tenemos los 'Item's con sus 'ItemUser' pivots,
        // necesitamos cargar las relaciones de likes en CADA INSTANCIA de ItemUser.
        // Itera sobre los items cargados para acceder a sus pivots.
        foreach ($user->items as $item) {
            $itemUser = $item->pivot; // Esto es una instancia de App\Models\Pivots\ItemUser

            // Cargar el conteo de likes en el ItemUser
            $itemUser->loadCount('likes'); // 'likes' es la relación morphMany en ItemUser

            // Si hay un usuario autenticado, cargar su like específico en el ItemUser
            if (Auth::check()) {
                $itemUser->load(['likes' => function ($q) {
                    $q->where('user_id', Auth::id());
                }]);
            }
        }

        // 3. También cargar los conteos de seguidores/siguiendo
        $user->loadCount(['followers', 'following']);

        // La vista espera la variable $user
        return view('profiles.show', ['user' => $user]);
    }
}