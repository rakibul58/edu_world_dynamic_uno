<?php

namespace App\Http\Controllers;

use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

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
            $validated = $this->validateHeroSection($request, $id);

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
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'hero_section_id' => 'nullable|exists:hero_sections,id'
            ]);

            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('uploads/image', $filename, 'public');

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
            'background_images' => 'nullable|array',
            'background_images.*' => 'string',
            'slider_interval' => 'integer|min:1000|max:10000',
            'enable_slider' => 'boolean',
            'background_gradients' => 'nullable|array',
            'background_gradients.*' => 'string',
            'cta_buttons' => 'nullable|array',
            'cta_buttons.*.text' => 'required|string|max:50',
            'cta_buttons.*.url' => 'required|string',
            'cta_buttons.*.target' => 'in:_self,_blank',
            'cta_buttons.*.type' => 'in:primary,secondary',
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
            'meta_tags.*' => 'string|max:50'
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
                ],
                'default_gradients' => [
                    'linear-gradient(135deg, #ff7101, #102e4a)',
                    'linear-gradient(135deg, #20bf6b, #102e4a)',
                    'linear-gradient(135deg, #f7b731, #102e4a)',
                    'linear-gradient(135deg, #102e4a, #030811)',
                    'linear-gradient(135deg, #d35b00, #102e4a)',
                    'linear-gradient(135deg, #667eea, #764ba2)',
                    'linear-gradient(135deg, #f093fb, #f5576c)',
                    'linear-gradient(135deg, #4facfe, #00f2fe)',
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
