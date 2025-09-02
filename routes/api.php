<?php

use App\Http\Controllers\AboutSectionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageSectionController;
use App\Http\Controllers\HeaderSectionController;
use App\Http\Controllers\HeroSectionController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramSettingController;

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
        Route::post('/header-sections/{id}', [HeaderSectionController::class, 'update']);
        Route::delete('header-sections/{id}', [HeaderSectionController::class, 'destroy']);
        Route::patch('header-sections/{id}/toggle', [HeaderSectionController::class, 'toggleActive']);
        Route::patch('header-sections/update-order', [HeaderSectionController::class, 'updateOrder']);
        Route::patch('header-sections/bulk-toggle', [HeaderSectionController::class, 'bulkToggle']);
        Route::get('hero-sections', [HeroSectionController::class, 'index']);
        Route::get('hero-sections/{id}', [HeroSectionController::class, 'show']);
        Route::post('hero-sections', [HeroSectionController::class, 'store']);
        Route::post('hero-sections/{id}', [HeroSectionController::class, 'update']);
        Route::delete('hero-sections/{id}', [HeroSectionController::class, 'destroy']);
        Route::patch('hero-sections/{id}/set-active', [HeroSectionController::class, 'setActive']);
        Route::post('hero-sections/{id}/duplicate', [HeroSectionController::class, 'duplicate']);
        Route::post('hero-sections/upload-background-image', [HeroSectionController::class, 'uploadBackgroundImage']);

        Route::prefix('programs')->group(function () {
            // Program management routes
            Route::get('/admin', [ProgramController::class, 'indexAdmin']);
            Route::post('/', [ProgramController::class, 'store']);
            Route::put('/{id}', [ProgramController::class, 'update']);
            Route::delete('/{id}', [ProgramController::class, 'destroy']);

            // Settings management
            Route::post('/settings', [ProgramSettingController::class, 'update']);
        });

        Route::get('/about-sections', [AboutSectionController::class, 'index']);
        Route::get('/about-sections/{id}', [AboutSectionController::class, 'show']);
        Route::post('/about-sections', [AboutSectionController::class, 'store']);
        Route::put('/about-sections/{id}', [AboutSectionController::class, 'update']);
        Route::delete('/about-sections/{id}', [AboutSectionController::class, 'destroy']);
        Route::post('/about-sections/{id}/activate', [AboutSectionController::class, 'activate']);
    });
});

// Public route for frontend
Route::get('page-sections/visible', [PageSectionController::class, 'getVisibleSections']);
Route::get('header-sections/active', [HeaderSectionController::class, 'getActive']);
Route::get('hero-sections/active', [HeroSectionController::class, 'getActive']);
Route::get('hero-sections/settings', [HeroSectionController::class, 'getSettings']);
Route::prefix('programs')->group(function () {
    // Get all active programs (for frontend display)
    Route::get('/', [ProgramController::class, 'indexPublic']);

    // Get global settings (for frontend display)
    Route::get('/settings', [ProgramSettingController::class, 'index']);
});
Route::get('/about-sections/active', [AboutSectionController::class, 'getActive']);
