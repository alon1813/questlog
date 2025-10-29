<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LikeController; 

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas para dar y quitar "Me Gusta" a un ItemUser
    // ¡SIN el '/' inicial en la URI!
    Route::post('item-users/{itemUser}/likes', [LikeController::class, 'store'])->name('api.item-users.likes.store');
    Route::delete('item-users/{itemUser}/likes', [LikeController::class, 'destroy'])->name('api.item-users.likes.destroy');

    // ... (otras rutas API que puedas tener) ...
});
