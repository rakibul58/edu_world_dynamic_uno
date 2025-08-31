<?php

namespace App\Http\Controllers;

use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class HeroSectionController extends Controller
{
    /**
     * Get the active hero section
     */
    public function getActive(): JsonResponse
    {
        try {
            $heroSection = HeroSection::getActive();

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
            $validated = $this->validateHeroSection($request);

            // Handle background image uploads
            $backgroundImages = [];
            
            // Handle existing background images (URLs)
            if ($request->has('background_images') && is_string($request->input('background_images'))) {
                $existingImages = json_decode($request->input('background_images'), true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($existingImages)) {
                    $backgroundImages = array_merge($backgroundImages, $existingImages);
                }
            }

            // Handle new file uploads
            if ($request->hasFile('background_files')) {
                foreach ($request->file('background_files') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('uploads/slides', $filename, 'public');
                    $backgroundImages[] = '/storage/' . $path;
                }
            }

            if (!empty($backgroundImages)) {
                $validated['background_images'] = $backgroundImages;
            }

            // Handle logo upload
            if ($request->hasFile('logo_image')) {
                $logoFile = $request->file('logo_image');
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
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
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
            $heroSection = HeroSection::findOrFail($id);

            // Handle boolean values properly
            $request->merge([
                'is_active' => $request->has('is_active') ? filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN) : false,
                'enable_slider' => $request->has('enable_slider') ? filter_var($request->input('enable_slider'), FILTER_VALIDATE_BOOLEAN) : false,
                'show_navigation' => $request->has('show_navigation') ? filter_var($request->input('show_navigation'), FILTER_VALIDATE_BOOLEAN) : false,
            ]);

            $validated = $this->validateHeroSection($request, $id);

            // Handle background image uploads
            $backgroundImages = [];
            
            // Keep existing images if no new ones are uploaded
            if (!$request->hasFile('background_files')) {
                $backgroundImages = $heroSection->background_images ?? [];
            }

            // Handle existing background images (URLs) from form
            if ($request->has('background_images') && is_string($request->input('background_images'))) {
                $existingImages = json_decode($request->input('background_images'), true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($existingImages)) {
                    $backgroundImages = $existingImages;
                }
            }

            // Handle new file uploads
            if ($request->hasFile('background_files')) {
                // Delete old background images if uploading new ones
                if ($heroSection->background_images) {
                    foreach ($heroSection->background_images as $oldImage) {
                        if (strpos($oldImage, '/storage/') === 0) {
                            Storage::disk('public')->delete(str_replace('/storage/', '', $oldImage));
                        }
                    }
                }

                $backgroundImages = [];
                foreach ($request->file('background_files') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('uploads/slides', $filename, 'public');
                    $backgroundImages[] = '/storage/' . $path;
                }
            }

            if (!empty($backgroundImages)) {
                $validated['background_images'] = $backgroundImages;
            }

            // Handle logo upload
            if ($request->hasFile('logo_image')) {
                // Delete old logo
                $oldNavigation = $heroSection->navigation ?? [];
                if (isset($oldNavigation['logo']['image_path']) && strpos($oldNavigation['logo']['image_path'], '/storage/') === 0) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $oldNavigation['logo']['image_path']));
                }

                $logoFile = $request->file('logo_image');
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
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
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

            // Don't allow deleting the active section if it's the only one
            if ($heroSection->is_active && HeroSection::count() === 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete the only active hero section'
                ], 422);
            }

            // Delete associated files
            if ($heroSection->background_images) {
                foreach ($heroSection->background_images as $image) {
                    if (strpos($image, '/storage/') === 0) {
                        Storage::disk('public')->delete(str_replace('/storage/', '', $image));
                    }
                }
            }

            // Delete logo if exists
            $navigation = $heroSection->navigation ?? [];
            if (isset($navigation['logo']['image_path']) && strpos($navigation['logo']['image_path'], '/storage/') === 0) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $navigation['logo']['image_path']));
            }

            $heroSection->delete();

            return response()->json([
                'success' => true,
                'message' => 'Hero section deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting hero section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set hero section as active
     */
    public function setActive($id): JsonResponse
    {
        try {
            $heroSection = HeroSection::findOrFail($id);
            $heroSection->setAsActive();

            return response()->json([
                'success' => true,
                'message' => 'Hero section set as active successfully',
                'data' => $heroSection->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error setting hero section as active: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload background images
     */
    public function uploadBackgroundImage(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'hero_section_id' => 'nullable|exists:hero_sections,id'
            ]);

            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('uploads/slides', $filename, 'public');

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => [
                    'url' => '/storage/' . $path,
                    'filename' => $filename,
                    'path' => $path
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading image: ' . $e->getMessage()
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
            return response()->json([
                'success' => false,
                'message' => 'Error duplicating hero section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate hero section data
     */
    private function validateHeroSection(Request $request, $id = null): array
    {
        $rules = [
            'section_name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'title' => 'nullable|string|max:255',
            'title_highlight' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'tagline' => 'nullable|string',
            'background_images' => 'nullable|string', // This will be JSON string of URLs
            'slider_interval' => 'nullable|integer|min:1000|max:10000',
            'enable_slider' => 'boolean',
            'cta_buttons' => 'nullable|string', // JSON string
            'text_styles' => 'nullable|string', // JSON string
            'overlay_styles' => 'nullable|string', // JSON string
            'section_styles' => 'nullable|string', // JSON string
            'navigation' => 'nullable|string', // JSON string
            'show_navigation' => 'boolean',
            'nav_styles' => 'nullable|string', // JSON string
            'advanced_settings' => 'nullable|string', // JSON string
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_tags' => 'nullable|string', // JSON string
            // File uploads - separate field names
            'background_files.*' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'logo_image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ];

        // Parse JSON strings from form data
        $jsonFields = [
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
            if ($request->has($field) && is_string($request->input($field))) {
                $decoded = json_decode($request->input($field), true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $request->merge([$field => $decoded]);
                }
            }
        }

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