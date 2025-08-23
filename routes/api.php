<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageSectionController;
use App\Http\Controllers\HeaderSectionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::prefix('admin')->group(function () {
        Route::get('page-sections', [PageSectionController::class, 'index']);
        Route::put('page-sections/{section}', [PageSectionController::class, 'update']);
        Route::patch('page-sections/{section}/toggle', [PageSectionController::class, 'toggleVisibility']);
        Route::patch('page-sections/update-order', [PageSectionController::class, 'updateOrder']);
        Route::patch('page-sections/bulk-toggle', [PageSectionController::class, 'bulkToggle']);
        Route::get('header-sections', [HeaderSectionController::class, 'index']);
        Route::post('header-sections', [HeaderSectionController::class, 'store']);
        Route::get('header-sections/{id}', [HeaderSectionController::class, 'show']);
        Route::put('header-sections/{id}', [HeaderSectionController::class, 'update']);
        Route::delete('header-sections/{id}', [HeaderSectionController::class, 'destroy']);
        Route::patch('header-sections/{id}/toggle', [HeaderSectionController::class, 'toggleActive']);
        Route::patch('header-sections/update-order', [HeaderSectionController::class, 'updateOrder']);
        Route::patch('header-sections/bulk-toggle', [HeaderSectionController::class, 'bulkToggle']);
    });
});

// Public route for frontend
Route::get('page-sections/visible', [PageSectionController::class, 'getVisibleSections']);
Route::get('/header-sections/active', [HeaderSectionController::class, 'getActive']);
