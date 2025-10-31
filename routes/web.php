<?php

use App\Http\Controllers\AdminCommentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserListItemController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ShopController;
use App\Models\Activity;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LandingPageController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController; 


Route::get('/', function () {
    if (Auth::check()) {
        // Si el usuario está logueado, llévalo al dashboard.
        return redirect()->route('dashboard');
    }
    // Si es un invitado, muéstrale la landing page.
    return app(LandingPageController::class)->index();
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// Rutas para crear posts (solo para admins)
Route::middleware(['auth', 'can:manage-posts'])->group(function () {
    
    Route::get('/admin/posts', [PostController::class, 'adminIndex'])->name('posts.admin.index');
    Route::get('posts/{post}/editar', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::patch('/admin/posts/{post}/status', [PostController::class, 'updateStatus'])->name('posts.admin.updateStatus');
    Route::get('/admin/comentarios', [AdminCommentController::class, 'index'])->name('admin.comments.index');
    Route::patch('/admin/comentarios/{comment}/status', [AdminCommentController::class, 'updateStatus'])->name('admin.comments.updateStatus');
    Route::delete('/admin/comentarios{comment}', [AdminCommentController::class, 'destroy'])->name('admin.comments.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/posts/crear', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/mi-lista/guardar', [UserListItemController::class, 'store'])->name('user-list.store');
    Route::get('mi-lista/{userListItem}/edit', [UserListItemController::class, 'edit'])->name('user-list.edit');
    Route::put('mi-lista/{userListItem}', [UserListItemController::class, 'update'])->name('user-list.update');
    Route::delete('/mi-lista/{userListItem}', [UserListItemController::class, 'destroy'])->name('user-list.destroy');
    Route::post('/noticias/{post}/comentarios', [CommentController::class, 'store'])->name('comments.store');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/noticias', [PostController::class, 'index'])->name('posts.index');
    Route::get('/noticias/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/mi-lista', [UserListItemController::class, 'index'])->name('user-list.index');
    Route::get('/mi-lista-deseos', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/mi-lista-deseos/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/mi-lista-deseos/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/usuarios/{user}/follow', [FollowController::class, 'follow'])->name('users.follow');
    Route::delete('/usuarios/{user}/unfollow', [FollowController::class, 'unfollow'])->name('users.unfollow');
    Route::get('/notificaciones', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
});

Route::get('/usuarios/{user:username}', [UserProfileController::class, 'show'])->name('profiles.show');
Route::get('/tienda', [ShopController::class, 'index'])->name('shop.index');
require __DIR__.'/auth.php';
