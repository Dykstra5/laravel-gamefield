<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index']
)->middleware(['auth', 'verified'])->name('dashboard');

// Por alguna razón esta declaración de ruta da un error que hace que todas las demás rutas declaradas después fallen :)
// Route::get('/{user:username}', [ProfileController::class, 'index']
// )->name('profile');
Route::get('/user/{user:username}', [ProfileController::class, 'index']
)->name('profile');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';

