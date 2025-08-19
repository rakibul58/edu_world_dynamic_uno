<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageSectionController;

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
    });
});

// Public route for frontend
Route::get('page-sections/visible', [PageSectionController::class, 'getVisibleSections']);
