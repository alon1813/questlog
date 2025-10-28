<?php

namespace App\Models\Pivots;

use App\Models\User;
use App\Models\Item; 
use Illuminate\Database\Eloquent\Relations\Pivot;

class ItemUser extends Pivot
{
    protected $table = 'item_user'; // Nombre de tu tabla pivote

    // Permite la asignación masiva para estas columnas
    protected $fillable = [
        'user_id', 
        'item_id', 
        'status', 
        'score', 
        'review', 
        'episodes_watched'
    ];

    // Indica que la tabla pivote tiene un ID auto-incrementable propio
    public $incrementing = true; 

    // Relación: esta entrada pivot pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: esta entrada pivot pertenece a un ítem
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Tu relación con los votos útiles, si aplica
    public function helpfulVotes()
    {
        return $this->belongsToMany(User::class, 'helpful_reviews', 'review_id', 'user_id');
    }
}