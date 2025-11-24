<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
        'image_url',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public static function booted(){
        static::deleting(function($post){
            
            $post->activities()->delete();
        });
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
