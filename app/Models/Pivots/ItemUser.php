<?php

namespace App\Models\Pivots;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ItemUser extends Pivot
{
    protected $table = 'item_user'; 

    protected $fillable = [
        'user_id', 
        'item_id', 
        'status', 
        'score', 
        'review', 
        'episodes_watched'
    ];

    public $incrementing = true; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function helpfulVotes()
    {
        return $this->belongsToMany(User::class, 'helpful_reviews', 'review_id', 'user_id');
    }

    public function likes(){
        return $this->morphMany(Like::class, 'likeable');
    }

    public function isLikedBy(User $user){
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}