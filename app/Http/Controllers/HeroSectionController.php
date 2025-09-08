<?php

namespace App\Http\Controllers;

use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class HeroSectionController extends Controller
{
    /**
     * Get the active hero section
     */
    public function getActive(): JsonResponse
    {
        try {
            $heroSection = HeroSection::where('is_active', true)->first();

            if (!$heroSection) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active hero section found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $heroSection
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching active hero section: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching hero section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all hero sections
     */
    public function index(): JsonResponse
    {
        try {
            $heroSections = HeroSection::orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $heroSections
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching hero sections: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching hero sections: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific hero section
     */
    public function show($id): JsonResponse
    {
        try {
            $heroSection = HeroSection::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $heroSection
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hero section not found'
            ], 404);
        }
    }

    /**
     * Create new hero section
     */
    public function store(Request $request): JsonResponse
    {
        try {
            Log::info('Creating hero section with data: ', $request->all());

            // Validate the request
            $validated = $this->validateHeroSection($request);

            // Handle file uploads first
            $validated = $this->handleFileUploads($request, $validated);

            // If this is set as active, deactivate others
            if ($validated['is_active'] ?? false) {
                HeroSection::where('is_active', true)->update(['is_active' => false]);
            }

            $heroSection = HeroSection::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Hero section created successfully',
                'data' => $heroSection
            ], 201);
        } catch (ValidationException $e) {
            Log::error('Validation error creating hero section: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating hero section: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating hero section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update hero section
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            Log::info('Updating hero section ' . $id . ' with data: ', $request->all());

            $heroSection = HeroSection::findOrFail($id);

            // Validate the request
            $validated = $this->validateHeroSection($request, $id);

            // Handle file uploads
            $validated = $this->handleFileUploads($request, $validated, $heroSection);

            // If this is set as active, deactivate others
            if ($validated['is_active'] ?? false) {
                HeroSection::where('id', '!=', $id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $heroSection->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Hero section updated successfully',
                'data' => $heroSection->fresh()
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation error updating hero section: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating hero section: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating hero section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete hero section
     */
    public function destroy($id): JsonResponse
    {
        try {
            $heroSection = HeroSection::findOrFail($id);

            // Don't allow deleting if it's the only section and it's active
            $totalSections = HeroSection::count();
            if ($heroSection->is_active && $totalSections === 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete the only active hero section'
                ], 422);
            }

            // Delete associated files
            $this->deleteAssociatedFiles($heroSection);

            $heroSection->delete();

            return response()->json([
                'success' => true,
                'message' => 'Hero section deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting hero section: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting hero section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle hero section active status
     */
    public function setActive(Request $request, $id): JsonResponse
    {
        try {
            $heroSection = HeroSection::findOrFail($id);

            // Get current status and toggle it
            $newStatus = !$heroSection->is_active;

            if ($newStatus) {
                // If setting as active, deactivate all others first
                HeroSection::where('id', '!=', $id)->update(['is_active' => !$newStatus]);
                $heroSection->update(['is_active' => $newStatus]);
            } else {
                // Check if this is the only active section
                $activeSections = HeroSection::where('is_active', true)->count();
                if ($activeSections <= 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'At least one hero section must remain active'
                    ], 422);
                }
                $heroSection->update(['is_active' => false]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Hero section status updated successfully',
                'data' => $heroSection->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling hero section status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating hero section status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicate hero section
     */
    public function duplicate($id): JsonResponse
    {
        try {
            $originalHero = HeroSection::findOrFail($id);

            $duplicateData = $originalHero->toArray();
            unset($duplicateData['id']);
            unset($duplicateData['created_at']);
            unset($duplicateData['updated_at']);

            // Make sure the duplicate is not active
            $duplicateData['is_active'] = false;
            $duplicateData['section_name'] = $duplicateData['section_name'] . ' (Copy)';

            $duplicate = HeroSection::create($duplicateData);

            return response()->json([
                'success' => true,
                'message' => 'Hero section duplicated successfully',
                'data' => $duplicate
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error duplicating hero section: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error duplicating hero section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle file uploads for hero section
     */
    private function handleFileUploads(Request $request, array $validated, HeroSection $heroSection = null): array
    {
        // Handle background images
        $backgroundImages = [];

        // Keep existing background images if updating and no new files
        if ($heroSection && !$request->hasFile('background_images')) {
            $backgroundImages = $heroSection->background_images ?? [];
        }

        // Handle new background image uploads
        if ($request->hasFile('background_images')) {
            // Delete old images if updating
            if ($heroSection && $heroSection->background_images) {
                foreach ($heroSection->background_images as $oldImage) {
                    $this->deleteFile($oldImage);
                }
            }

            $backgroundImages = [];
            $files = is_array($request->file('background_images'))
                ? $request->file('background_images')
                : [$request->file('background_images')];

            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('uploads/slides', $filename, 'public');
                    $backgroundImages[] = '/storage/' . $path;
                }
            }
        }

        if (!empty($backgroundImages)) {
            $validated['background_images'] = $backgroundImages;
        }

        // Handle logo upload
        if ($request->hasFile('logo_image')) {
            // Delete old logo if updating
            if ($heroSection) {
                $oldNavigation = $heroSection->navigation ?? [];
                if (isset($oldNavigation['logo']['image_path'])) {
                    $this->deleteFile($oldNavigation['logo']['image_path']);
                }
            }

            $logoFile = $request->file('logo_image');
            if ($logoFile->isValid()) {
                $filename = time() . '_logo_' . uniqid() . '.' . $logoFile->getClientOriginalExtension();
                $path = $logoFile->storeAs('uploads/image', $filename, 'public');

                // Update navigation logo path
                $navigation = $validated['navigation'] ?? [];
                if (!isset($navigation['logo'])) {
                    $navigation['logo'] = [];
                }
                $navigation['logo']['image_path'] = '/storage/' . $path;
                $validated['navigation'] = $navigation;
            }
        }

        return $validated;
    }

    /**
     * Delete a file from storage
     */
    private function deleteFile($filePath): void
    {
        if ($filePath && strpos($filePath, '/storage/') === 0) {
            $relativePath = str_replace('/storage/', '', $filePath);
            Storage::disk('public')->delete($relativePath);
        }
    }

    /**
     * Delete associated files for a hero section
     */
    private function deleteAssociatedFiles(HeroSection $heroSection): void
    {
        // Delete background images
        if ($heroSection->background_images) {
            foreach ($heroSection->background_images as $image) {
                $this->deleteFile($image);
            }
        }

        // Delete logo
        $navigation = $heroSection->navigation ?? [];
        if (isset($navigation['logo']['image_path'])) {
            $this->deleteFile($navigation['logo']['image_path']);
        }
    }

    /**
     * Validate hero section data
     */
    private function validateHeroSection(Request $request, $id = null): array
    {
        // Convert string booleans to actual booleans
        $booleanFields = ['is_active', 'enable_slider', 'show_navigation'];
        foreach ($booleanFields as $field) {
            if ($request->has($field)) {
                $value = $request->input($field);
                if (is_string($value)) {
                    $request->merge([$field => filter_var($value, FILTER_VALIDATE_BOOLEAN)]);
                }
            }
        }

        // Parse JSON strings from form data
        $jsonFields = [
            'background_gradients',
            'cta_buttons',
            'text_styles',
            'overlay_styles',
            'section_styles',
            'navigation',
            'nav_styles',
            'advanced_settings',
            'meta_tags'
        ];

        foreach ($jsonFields as $field) {
            if ($request->has($field)) {
                $value = $request->input($field);
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $request->merge([$field => $decoded]);
                    }
                }
            }
        }

        $rules = [
            'section_name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'title' => 'nullable|string|max:255',
            'title_highlight' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'tagline' => 'nullable|string',
            'background_gradients' => 'nullable|array',
            'slider_interval' => 'nullable|integer|min:1000|max:15000',
            'enable_slider' => 'boolean',
            'cta_buttons' => 'nullable|array',
            'text_styles' => 'nullable|array',
            'overlay_styles' => 'nullable|array',
            'section_styles' => 'nullable|array',
            'navigation' => 'nullable|array',
            'show_navigation' => 'boolean',
            'nav_styles' => 'nullable|array',
            'advanced_settings' => 'nullable|array',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_tags' => 'nullable|array',
            // File uploads
            'background_images.*' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'logo_image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ];

        return $request->validate($rules);
    }

    /**
     * Get hero section settings/options
     */
    public function getSettings(): JsonResponse
    {
        try {
            $settings = [
                'slider_intervals' => [
                    ['label' => '2 seconds', 'value' => 2000],
                    ['label' => '3 seconds', 'value' => 3000],
                    ['label' => '3.5 seconds', 'value' => 3500],
                    ['label' => '4 seconds', 'value' => 4000],
                    ['label' => '5 seconds', 'value' => 5000],
                    ['label' => '7 seconds', 'value' => 7000],
                    ['label' => '10 seconds', 'value' => 10000],
                ],
                'button_types' => [
                    ['label' => 'Primary', 'value' => 'primary'],
                    ['label' => 'Secondary', 'value' => 'secondary'],
                ],
                'link_targets' => [
                    ['label' => 'Same Tab', 'value' => '_self'],
                    ['label' => 'New Tab', 'value' => '_blank'],
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching settings: ' . $e->getMessage()
            ], 500);
        }
    }
}
