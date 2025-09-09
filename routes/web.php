<?php

use Illuminate\Support\Facades\Route;

// Exclude /api/* from catch-all
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$'); // everything except /api/*

