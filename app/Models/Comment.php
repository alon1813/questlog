<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'post_id', 'body', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // 🆕 Relación polimórfica para likes
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // 🆕 Método helper para verificar si un usuario dio like
    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}