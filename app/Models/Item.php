<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Item extends Model
{
    use HasFactory;
    /**
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'api_id',            
        'type',              
        'title',
        'cover_image_url',
        'synopsis',
        'episodes',          
        
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('status', 'score', 'review')
                    ->withTimestamps();
    }
}