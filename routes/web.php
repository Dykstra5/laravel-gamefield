<?php

use App\Http\Controllers\GamesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckIsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/post/{post}', [PostController::class, 'view'])
    ->name('post.view');

// Por alguna razón esta declaración de ruta da un error que hace que todas las demás rutas declaradas después fallen :)
// Route::get('/{user:username}', [ProfileController::class, 'index']
// )->name('profile');
// Route::get('/user/{user:username}', [ProfileController::class, 'index'])
// ->name('profile');


Route::middleware('auth')->group(function () {

    // Profile
    Route::post('/profile/update-images', [ProfileController::class, 'updateImages'])
        ->name('profile.updateImages');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::post('/post', [PostController::class, 'store'])
        ->name('post.create');

    Route::get('/user/{username}', [ProfileController::class, 'index'])
        ->name('profile');


    // ->middleware(CheckIsAdmin::class);

    // Users
    Route::post('/user/{user}/follow', [UserController::class, 'followUser'])
        ->name('user.follow');

    Route::post('/user/{user}/unfollow', [UserController::class, 'unfollowUser'])
        ->name('user.unfollow');

    Route::get('/user/{keyword}/search', [UserController::class, 'searchUsers'])
        ->name('user.search')
        ->middleware(CheckIsAdmin::class);

    Route::delete('/user/{user}', [UserController::class, 'destroy'])
        ->name('user.destroy')
        ->middleware(CheckIsAdmin::class);

    // Posts
    Route::delete('/post/{post}', [PostController::class, 'destroy'])
        ->name('post.destroy');

    Route::delete('/post/delete/{post}', [PostController::class, 'destroyAsAdmin'])
        ->name('post.admin.destroy')
        ->middleware(CheckIsAdmin::class);

    Route::patch('/post/restore/{postId}', [PostController::class, 'restoreAsAdmin'])
        ->name('post.admin.restore')
        ->middleware(CheckIsAdmin::class);

    Route::get('/search/tags/{keyword}', [SearchController::class, 'searchTags'])
        ->name('search.tags');

    Route::get('/post/download/{attachment}', [PostController::class, 'downloadAttachment'])
        ->name('post.download');

    Route::post('/post/{post}/like', [PostController::class, 'postLike'])
        ->name('post.like');

    // Post comments
    Route::post('/post/{post}/comment', [PostController::class, 'storeComment'])
        ->name('post.comment.create');

    Route::delete('/comment/{comment}', [PostController::class, 'destroyComment'])
        ->name('post.comment.destroy');

    Route::post('/comment/{comment}/like', [PostController::class, 'commentLike'])
        ->name('comment.like');

    // games
    Route::get('/game/{gameSlug}', [GamesController::class, 'index'])
        ->name('game.name');
});

Route::get('/games/get-external-data', [GamesController::class, 'getExternalData'])
    ->name('games.data')
    ->middleware(CheckIsAdmin::class);
Route::delete('/games', [GamesController::class, 'destroyAll'])
    ->name('games.destroy')
    ->middleware(CheckIsAdmin::class);

require __DIR__ . '/auth.php';
