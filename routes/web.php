<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YoutubeController;
use App\Http\Controllers\FavoriteController;


Route::get('/', function () {
    return view('home');
})->middleware('auth');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

// Búsqueda de videos
Route::post('/buscar-videos', [YouTubeController::class, 'buscar'])->name('videos.search')->middleware('auth');

// Favoritos
Route::post('/añadir-favoritos', [YouTubeController::class, 'addToFavorites'])->name('addToFavorites')->middleware('auth');
Route::get('/favorites/show', [FavoriteController::class, 'show'])->name('favorites.show')->middleware('auth');
Route::delete('/favorites/{favorite}', [FavoriteController::class, 'destroy'])->name('favorites.destroy')->middleware('auth');