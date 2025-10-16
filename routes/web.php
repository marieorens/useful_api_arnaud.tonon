<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// redirect publik pour les shortlinks
Route::get('/s/{code}', [\App\Http\Controllers\ShortLinkController::class, 'redirect']);
