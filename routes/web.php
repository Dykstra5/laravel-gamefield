<?php

use App\Http\Controllers\GamesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Middleware\CheckIsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified'])
    ->name('dashboard');

// Por alguna razón esta declaración de ruta da un error que hace que todas las demás rutas declaradas después fallen :)
// Route::get('/{user:username}', [ProfileController::class, 'index']
// )->name('profile');
// Route::get('/user/{user:username}', [ProfileController::class, 'index'])
// ->name('profile');
Route::get('/user/{username}', [ProfileController::class, 'index'])
    ->name('profile');

Route::get('/game/{gameSlug}', [GamesController::class, 'index'])
    ->name('game.name');

Route::middleware('auth')->group(function () {
    Route::post('/profile/update-images', [ProfileController::class, 'updateImages'])
        ->name('profile.updateImages');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::post('/post', [PostController::class, 'store'])
        ->name('post.create');
    // ->middleware(CheckIsAdmin::class);

    Route::delete('/post/{post}', [PostController::class, 'destroy'])
        ->name('post.destroy');

    Route::get('/search/tags/{keyword}', [SearchController::class, 'searchTags'])
        ->name('search.tags');

    Route::get('/post/download/{attachment}', [PostController::class, 'downloadAttachment'])
        ->name('post.download');

    Route::post('/post/{post}/like', [PostController::class, 'postLike'])
        ->name('post.like');

    Route::post('/post/{post}/comment', [PostController::class, 'storeComment'])
        ->name('post.comment.create');

    Route::delete('/comment/{comment}', [PostController::class, 'destroyComment'])
        ->name('post.comment.destroy');

    Route::post('/comment/{comment}/like', [PostController::class, 'commentLike'])
        ->name('comment.like');
});

Route::get('/games/get-external-data', [GamesController::class, 'getExternalData'])
    ->name('games.data')
    ->middleware(CheckIsAdmin::class);
Route::delete('/games', [GamesController::class, 'destroyAll'])
    ->name('games.destroy')
    ->middleware(CheckIsAdmin::class);

require __DIR__ . '/auth.php';
