<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'image_url', 'affiliate_url', 'category'];

    public function usersInWishlist()
    {
        return $this->belongsToMany(User::class, 'product_user')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}

