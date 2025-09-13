<?php

use App\Http\Controllers\AboutSectionController;
use App\Http\Controllers\AcademicProgramsController;
use App\Http\Controllers\AcademicProgramsPublicController;
use App\Http\Controllers\AcademicProgramsSettingsController;
use App\Http\Controllers\AdmissionProcessController;
use App\Http\Controllers\FacilitiesSectionController;
use App\Http\Controllers\CtaSectionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactCardController;
use App\Http\Controllers\FeaturesController;
use App\Http\Controllers\FooterSectionController;
use App\Http\Controllers\PageSectionController;
use App\Http\Controllers\HeaderSectionController;
use App\Http\Controllers\HeroSectionController;
use App\Http\Controllers\LocationMapController;
use App\Http\Controllers\LocationSectionController;
use App\Http\Controllers\NewsEventsController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramSettingController;
use App\Http\Controllers\SectionStyleController;
use App\Http\Controllers\TestimonialController;

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

        Route::resource('cta-sections', CtaSectionController::class);
        Route::post('cta-sections/{ctaSection}/activate', [CtaSectionController::class, 'activate']);
        Route::post('cta-sections/reorder', [CtaSectionController::class, 'reorder']);
        Route::apiResource('admission-processes', AdmissionProcessController::class);
        Route::post('admission-processes/{admissionProcess}/activate', [AdmissionProcessController::class, 'activate']);
        Route::apiResource('location-sections', LocationSectionController::class);
        Route::apiResource('contact-cards', ContactCardController::class);
        Route::post('contact-cards/reorder', [ContactCardController::class, 'reorder']);
        Route::apiResource('location-maps', LocationMapController::class)->except(['index', 'show']);
        Route::apiResource('section-styles', SectionStyleController::class);
        Route::post('section-styles/bulk-update', [SectionStyleController::class, 'bulkUpdate']);
        Route::get('footer-sections', [FooterSectionController::class, 'index']);
        Route::post('footer-sections', [FooterSectionController::class, 'store']);
        Route::get('footer-sections/{footerSection}', [FooterSectionController::class, 'show']);
        Route::put('footer-sections/{footerSection}', [FooterSectionController::class, 'update']);
        Route::delete('footer-sections/{footerSection}', [FooterSectionController::class, 'destroy']);
        Route::post('footer-sections/{footerSection}/activate', [FooterSectionController::class, 'activate']);
        Route::get('/news-events', [NewsEventsController::class, 'index']);
        Route::post('/news-events', [NewsEventsController::class, 'store']);

        // Specific action routes (must come before {id} routes)
        Route::post('/news-events/update-order', [NewsEventsController::class, 'updateOrder']);
        Route::get('/news-events-settings', [NewsEventsController::class, 'getSectionSettings']);
        Route::post('/news-events-settings', [NewsEventsController::class, 'updateSectionSettings']);

        // Parameterized routes (must come after specific routes)
        Route::get('/news-events/{id}', [NewsEventsController::class, 'show']);
        Route::put('/news-events/{id}', [NewsEventsController::class, 'update']);
        Route::post('/news-events/{id}', [NewsEventsController::class, 'update']); // For FormData with _method
        Route::delete('/news-events/{id}', [NewsEventsController::class, 'destroy']);
        Route::post('/news-events/{id}/toggle-status', [NewsEventsController::class, 'toggleStatus']);
        // Route::apiResource('academic-programs', AcademicProgramsController::class);
        Route::get('academic-programs', [AcademicProgramsController::class, 'index']);
        Route::post('academic-programs', [AcademicProgramsController::class, 'store']);
        Route::get('academic-programs/{id}', [AcademicProgramsController::class, 'show']);
        Route::put('academic-programs/{id}', [AcademicProgramsController::class, 'update']);
        Route::delete('academic-programs/{id}', [AcademicProgramsController::class, 'destroy']);
        Route::post('academic-programs/{id}/toggle-status', [AcademicProgramsController::class, 'toggleStatus']);
        Route::post('academic-programs/update-order', [AcademicProgramsController::class, 'updateOrder']);

        // Academic Programs Settings
        Route::get('academic-programs-settings', [AcademicProgramsSettingsController::class, 'index']);
        Route::post('academic-programs-settings', [AcademicProgramsSettingsController::class, 'store']);

        Route::get('testimonials', [TestimonialController::class, 'index']);
        Route::post('testimonials', [TestimonialController::class, 'store']);
        Route::put('testimonials/{id}', [TestimonialController::class, 'update']);
        Route::delete('testimonials/{id}', [TestimonialController::class, 'destroy']);
        Route::post('testimonials/section-settings', [TestimonialController::class, 'sectionSettings']);

        Route::get('facilities-sections', [FacilitiesSectionController::class, 'index']);
        Route::post('facilities-sections', [FacilitiesSectionController::class, 'store']);
        Route::put('facilities-sections/{id}', [FacilitiesSectionController::class, 'update']);
        Route::delete('facilities-sections/{id}', [FacilitiesSectionController::class, 'destroy']);
        Route::patch('facilities-sections/{id}/toggle-active', [FacilitiesSectionController::class, 'toggleActive']);

        Route::get('features', [FeaturesController::class, 'index']);
        Route::post('features', [FeaturesController::class, 'store']);
        Route::put('features/{id}', [FeaturesController::class, 'update']);
        Route::delete('features/{id}', [FeaturesController::class, 'destroy']);
        Route::post('features/{id}/toggle-status', [FeaturesController::class, 'toggleStatus']);
        Route::post('features/update-order', [FeaturesController::class, 'updateOrder']);

        // Section settings
        Route::get('features-settings', [FeaturesController::class, 'getSettings']);
        Route::post('features-settings', [FeaturesController::class, 'updateSettings']);
        Route::delete('features-settings/remove-background-image', [FeaturesController::class, 'removeBackgroundImage']);
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
Route::get('cta-sections/active', [CtaSectionController::class, 'getActive']);
Route::get('cta-sections/{ctaSection}', [CtaSectionController::class, 'show']);
Route::get('admission-process/active', [AdmissionProcessController::class, 'getActive']);
Route::get('/location-section/public', [LocationSectionController::class, 'getPublicData']);
Route::get('/footer-sections/active', [FooterSectionController::class, 'getActive']);
Route::get('/news-events/active', [NewsEventsController::class, 'getPublicNewsEvents']);
Route::get('academic-programs', [AcademicProgramsPublicController::class, 'index']);
Route::get('academic-programs/{program}', [AcademicProgramsPublicController::class, 'show']);
Route::get('academic-programs-component-data', [AcademicProgramsPublicController::class, 'getComponentData']);

Route::get('testimonials', [TestimonialController::class, 'frontendIndex']);
Route::get('testimonials/get', [TestimonialController::class, 'getTestimonials']);
Route::get('facilities-sections/active', [FacilitiesSectionController::class, 'getActive']);
Route::get('facilities-sections/{id}', [FacilitiesSectionController::class, 'show']);
Route::get('features/active', [FeaturesController::class, 'getPublicData']);

