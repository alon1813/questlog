<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LikeController; 


// Middleware 'auth:sanctum' para proteger las rutas de la API, requiere autenticación
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas para dar y quitar "Me Gusta" a un ItemUser
    Route::post('/item-users/{itemUser}/likes', [LikeController::class, 'store'])->name('api.item_users.likes.store');
    Route::delete('/item-users/{itemUser}/likes', [LikeController::class, 'destroy'])->name('api.item_users.likes.destroy');

    // ... (otras rutas API que puedas tener) ...
});