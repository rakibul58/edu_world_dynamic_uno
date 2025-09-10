<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FacilitiesSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FacilitiesSectionController extends Controller
{
    // Get active facilities section
    public function getActive()
    {
        try {
            $section = FacilitiesSection::getActive();

            if (!$section) {
                return response()->json([
                    'message' => 'No active facilities section found'
                ], 404);
            }

            return response()->json($section);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching facilities section',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get specific facilities section
    public function show($id)
    {
        try {
            $section = FacilitiesSection::with(['facilityItems', 'facilityButtons'])
                ->findOrFail($id);

            return response()->json($section);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Facilities section not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    // Get all facilities sections
    public function index()
    {
        try {
            $sections = FacilitiesSection::with(['facilityItems', 'facilityButtons'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($sections);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching facilities sections',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Create new facilities section
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string',
            'featured_title' => 'required|string|max:255',
            'featured_description' => 'required|string',
            'featured_type' => 'required|in:image,gradient',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'facility_items' => 'required|array|min:1',
            'facility_items.*.title' => 'required|string|max:255',
            'facility_items.*.description' => 'required|string',
            'facility_items.*.icon' => 'required|string',
            'facility_buttons' => 'nullable|array',
            'facility_buttons.*.text' => 'required|string|max:255',
            'facility_buttons.*.url' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();

            // Handle image upload
            if ($request->hasFile('featured_image') && $data['featured_type'] === 'image') {
                $imagePath = $request->file('featured_image')->store('facilities', 'public');
                $data['featured_image_path'] = $imagePath;
            }

            // Create facilities section
            $section = FacilitiesSection::create($data);

            // Create facility items
            foreach ($data['facility_items'] as $index => $itemData) {
                $itemData['sort_order'] = $index;
                $section->facilityItems()->create($itemData);
            }

            // Create facility buttons
            if (isset($data['facility_buttons'])) {
                foreach ($data['facility_buttons'] as $index => $buttonData) {
                    $buttonData['sort_order'] = $index;
                    $section->facilityButtons()->create($buttonData);
                }
            }

            return response()->json([
                'message' => 'Facilities section created successfully',
                'data' => $section->load(['facilityItems', 'facilityButtons'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating facilities section',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update facilities section
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string',
            'featured_title' => 'required|string|max:255',
            'featured_description' => 'required|string',
            'featured_type' => 'required|in:image,gradient',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'facility_items' => 'required|array|min:1',
            'facility_items.*.title' => 'required|string|max:255',
            'facility_items.*.description' => 'required|string',
            'facility_items.*.icon' => 'required|string',
            'facility_buttons' => 'nullable|array',
            'facility_buttons.*.text' => 'required|string|max:255',
            'facility_buttons.*.url' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $section = FacilitiesSection::findOrFail($id);
            $data = $request->all();

            // Handle image upload
            if ($request->hasFile('featured_image') && $data['featured_type'] === 'image') {
                // Delete old image if exists
                if ($section->featured_image_path) {
                    Storage::disk('public')->delete($section->featured_image_path);
                }
                $imagePath = $request->file('featured_image')->store('facilities', 'public');
                $data['featured_image_path'] = $imagePath;
            }

            // Update facilities section
            $section->update($data);

            // Update facility items
            $section->facilityItems()->delete();
            foreach ($data['facility_items'] as $index => $itemData) {
                $itemData['sort_order'] = $index;
                $section->facilityItems()->create($itemData);
            }

            // Update facility buttons
            $section->facilityButtons()->delete();
            if (isset($data['facility_buttons'])) {
                foreach ($data['facility_buttons'] as $index => $buttonData) {
                    $buttonData['sort_order'] = $index;
                    $section->facilityButtons()->create($buttonData);
                }
            }

            return response()->json([
                'message' => 'Facilities section updated successfully',
                'data' => $section->load(['facilityItems', 'facilityButtons'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating facilities section',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete facilities section
    public function destroy($id)
    {
        try {
            $section = FacilitiesSection::findOrFail($id);

            // Delete associated image if exists
            if ($section->featured_image_path) {
                Storage::disk('public')->delete($section->featured_image_path);
            }

            $section->delete();

            return response()->json([
                'message' => 'Facilities section deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting facilities section',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Toggle active status
    public function toggleActive($id)
    {
        try {
            $section = FacilitiesSection::findOrFail($id);

            // Deactivate all other sections
            FacilitiesSection::where('id', '!=', $id)->update(['is_active' => false]);

            // Activate this section
            $section->update(['is_active' => true]);

            return response()->json([
                'message' => 'Facilities section activated successfully',
                'data' => $section
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error activating facilities section',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
