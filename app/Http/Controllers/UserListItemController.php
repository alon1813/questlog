<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pivots\ItemUser; 

class UserListItemController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user(); 
        $statusFilter = $request->query('status');
        $itemsQuery = $user->items(); 

        $itemsQuery->withPivot('id', 'status', 'score', 'review', 'episodes_watched'); 

        if ($statusFilter) {
            $itemsQuery->wherePivot('status', $statusFilter);
        }

        $items = $itemsQuery->get(); 

        return view('user-list.index', ['items' => $items, 'statusFilter' => $statusFilter]);
    }
    
    public function edit(ItemUser $userListItem) 
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($userListItem->user_id !== $user->id) {
            return redirect()->route('user-list.index')->with('error', 'No tienes permiso para editar esta entrada.');
        }
        $itemId = $userListItem->item_id; 

        $publicReviews = ItemUser::query()
                                ->with(['user', 'helpfulVotes']) 
                                ->where('item_id', $itemId) 
                                ->whereNotNull('review')
                                ->where('review', '!=', '')
                                ->where('user_id', '!=', $user->id) 
                                ->latest('updated_at')
                                ->paginate(1); 

        $averageScoreData = DB::table('item_user')
                                ->where('item_id', $itemId) 
                                ->whereNotNull('score')
                                ->selectRaw('AVG(score) as average_score, COUNT(id) as score_count')
                                ->first();
        
        $averageScore = $averageScoreData->average_score ? round($averageScoreData->average_score, 1) : null;
        $scoreCount = $averageScoreData->score_count;
        
        return view('user-list.edit', [
            'userListItem' => $userListItem,      
            'item' => $userListItem->item,        
            'publicReviews' => $publicReviews,
            'averageScore' => $averageScore,
            'scoreCount' => $scoreCount,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'api_id' => 'required|integer',
            'type' => 'required|string|in:game,anime',
            'title' => 'required|string|max:255',
            'cover_image_url' => 'required|string|max:255',
            'episodes' => 'nullable|integer',
        ]);

        $item = Item::firstOrCreate(
            ['api_id' => $validated['api_id'], 'type' => $validated['type']],
            [
                'title' => $validated['title'],
                'cover_image_url' => $validated['cover_image_url'],
                'episodes' => $validated['episodes'] ?? null,
            ]
        );

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $existingPivot = $user->items()->where('item_id', $item->id)->first();

        if ($request->expectsJson()) {
            if ($existingPivot) {
                
                return response()->json([
                    'message' => 'El item ya estaba en tu colección.',
                    'user_list_item_id' => $existingPivot->pivot->id 
                ], 200); 
            }

            $user->items()->attach($item->id, ['status' => 'Pendiente']);
            $pivotId = $user->items()->where('item_id', $item->id)->first()->pivot->id;

            return response()->json([
                'message' => '¡' . $item->title . ' ha sido añadido a tu lista!',
                'user_list_item_id' => $pivotId 
            ]);
        }

        if ($existingPivot) {
            return back()->with('info', '¡' . $item->title . ' ya estaba en tu colección!');
        } else {
            $user->items()->attach($item->id, ['status' => 'Pendiente']);
            return back()->with('success', '¡' . $item->title . ' ha sido añadido a tu lista!');
        }
    }

    public function update(Request $request, ItemUser $userListItem){ 
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($userListItem->user_id !== $user->id) {
            return redirect()->route('user-list.index')->with('error', 'No tienes permiso para actualizar esta entrada.');
        }
        
        $maxEpisodes = $userListItem->item->episodes ?? 1000; 
        $validated = $request->validate([
            'status' => 'required|string|in:Pendiente,Jugando,Completado,Abandonado',
            'score' => 'nullable|integer|min:1|max:10',
            'review' => 'nullable|string|max:10000',
            'episodes_watched' => 'nullable|integer|min:0|max:' . $maxEpisodes, 
        ]);

        if ($userListItem->item->type === 'anime' && isset($validated['episodes_watched'])) {
            if ($validated['episodes_watched'] > $userListItem->item->episodes) {
                $validated['episodes_watched'] = $userListItem->item->episodes; 
            }
        }
        $userListItem->update($validated); 
        $user->activities()->create([
            'type' => 'updated_list_item',
            'subject_id' => $userListItem->item->id,  
            'subject_type' => Item::class, 
        ]);
        return redirect()->route('user-list.edit', $userListItem->id)->with('success', '¡' . $userListItem->item->title . ' ha sido actualizado en tu lista!');
    }

    
    public function destroy(ItemUser $userListItem, Request $request) 
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        
        if ($userListItem->user_id !== $user->id) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No tienes permiso para eliminar esta entrada.'], 403);
            }
            return redirect()->route('user-list.index')->with('error', 'No tienes permiso para eliminar esta entrada.');
        }

        $title = $userListItem->item->title; 
        $itemId = $userListItem->item->id; 
        
        $userListItem->delete();

        
        $user->activities()->create([
            'type' => 'deleted_list_item',
            'subject_id' => $itemId, 
            'subject_type' => Item::class, 
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => '¡' . $title . ' ha sido eliminado de tu colección!']);
        }
        
        return redirect()->route('user-list.index')->with('success', '¡' . $title . ' ha sido eliminado de tu colección!');
    }

}