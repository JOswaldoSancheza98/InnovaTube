<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YoutubeController;
use App\Http\Controllers\FavoriteVideoController;


Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Búsqueda de videos
Route::post('/buscar-videos', [YouTubeController::class, 'buscar'])->name('videos.search');

// Favoritos
