<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public API Routes
Route::get('/site-data', [SiteController::class, 'getSiteData']);
Route::get('/settings', [SiteController::class, 'getSettings']);

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

// Admin Routes (Protected)
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    // Site Settings
    Route::get('/settings', [AdminController::class, 'getSettings']);
    Route::post('/settings', [AdminController::class, 'updateSettings']);

    // Hero Slides
    Route::get('/hero-slides', [AdminController::class, 'getHeroSlides']);
    Route::post('/hero-slides', [AdminController::class, 'storeHeroSlide']);
    Route::put('/hero-slides/{heroSlide}', [AdminController::class, 'updateHeroSlide']);
    Route::delete('/hero-slides/{heroSlide}', [AdminController::class, 'deleteHeroSlide']);

    // Programs
    Route::get('/programs', [AdminController::class, 'getPrograms']);
    Route::post('/programs', [AdminController::class, 'storeProgram']);
    Route::put('/programs/{program}', [AdminController::class, 'updateProgram']);
    Route::delete('/programs/{program}', [AdminController::class, 'deleteProgram']);

    // Features
    Route::get('/features', [AdminController::class, 'getFeatures']);
    Route::post('/features', [AdminController::class, 'storeFeature']);
    Route::put('/features/{feature}', [AdminController::class, 'updateFeature']);
    Route::delete('/features/{feature}', [AdminController::class, 'deleteFeature']);

    // Testimonials
    Route::get('/testimonials', [AdminController::class, 'getTestimonials']);
    Route::post('/testimonials', [AdminController::class, 'storeTestimonial']);
    Route::put('/testimonials/{testimonial}', [AdminController::class, 'updateTestimonial']);
    Route::delete('/testimonials/{testimonial}', [AdminController::class, 'deleteTestimonial']);

    // Gallery Items
    Route::get('/gallery-items', [AdminController::class, 'getGalleryItems']);
    Route::post('/gallery-items', [AdminController::class, 'storeGalleryItem']);
    Route::put('/gallery-items/{galleryItem}', [AdminController::class, 'updateGalleryItem']);
    Route::delete('/gallery-items/{galleryItem}', [AdminController::class, 'deleteGalleryItem']);

    // News & Events
    Route::get('/news-events', [AdminController::class, 'getNewsEvents']);
    Route::post('/news-events', [AdminController::class, 'storeNewsEvent']);
    Route::put('/news-events/{newsEvent}', [AdminController::class, 'updateNewsEvent']);
    Route::delete('/news-events/{newsEvent}', [AdminController::class, 'deleteNewsEvent']);

    // File Upload
    Route::post('/upload', [FileUploadController::class, 'upload']);
    Route::delete('/upload', [FileUploadController::class, 'delete']);
});
