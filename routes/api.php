<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationApiController; 


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Route::get('/notifications', [NotificationApiController::class, 'index']);
    // Route::post('/notifications/mark-as-read', [NotificationApiController::class, 'markAsRead']);
});

