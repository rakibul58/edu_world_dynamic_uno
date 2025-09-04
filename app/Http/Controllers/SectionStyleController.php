<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SectionStyle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SectionStyleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = SectionStyle::query();

        if ($request->has('location_section_id')) {
            $query->where('location_section_id', $request->location_section_id);
        }

        $styles = $query->get();

        return response()->json([
            'success' => true,
            'data' => $styles
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'location_section_id' => 'required|exists:location_sections,id',
            'element_class' => 'required|string|max:100',
            'styles' => 'required|array'
        ]);

        $style = SectionStyle::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Section style created successfully',
            'data' => $style
        ], 201);
    }

    public function update(Request $request, SectionStyle $sectionStyle): JsonResponse
    {
        $validated = $request->validate([
            'element_class' => 'sometimes|required|string|max:100',
            'styles' => 'sometimes|required|array'
        ]);

        $sectionStyle->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Section style updated successfully',
            'data' => $sectionStyle
        ]);
    }

    public function destroy(SectionStyle $sectionStyle): JsonResponse
    {
        $sectionStyle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Section style deleted successfully'
        ]);
    }

    public function bulkUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'location_section_id' => 'required|exists:location_sections,id',
            'styles' => 'required|array',
            'styles.*' => 'array'
        ]);

        $locationSectionId = $validated['location_section_id'];

        // Delete existing styles for this section
        SectionStyle::where('location_section_id', $locationSectionId)->delete();

        // Create new styles
        foreach ($validated['styles'] as $elementClass => $styles) {
            SectionStyle::create([
                'location_section_id' => $locationSectionId,
                'element_class' => $elementClass,
                'styles' => $styles
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Section styles updated successfully'
        ]);
    }
}
