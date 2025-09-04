<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LocationSection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocationSectionController extends Controller
{
    public function index(): JsonResponse
    {
        $sections = LocationSection::with(['contactCards', 'locationMap', 'sectionStyles'])
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sections
        ]);
    }

    public function show(LocationSection $locationSection): JsonResponse
    {
        $locationSection->load(['contactCards', 'locationMap', 'sectionStyles']);

        return response()->json([
            'success' => true,
            'data' => $locationSection
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'background_color' => 'nullable|string|max:10',
            'padding_top' => 'nullable|integer|min:0|max:200',
            'padding_bottom' => 'nullable|integer|min:0|max:200',
            'is_active' => 'boolean'
        ]);

        $locationSection = LocationSection::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Location section created successfully',
            'data' => $locationSection
        ], 201);
    }

    public function update(Request $request, LocationSection $locationSection): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'background_color' => 'nullable|string|max:10',
            'padding_top' => 'nullable|integer|min:0|max:200',
            'padding_bottom' => 'nullable|integer|min:0|max:200',
            'is_active' => 'boolean'
        ]);

        $locationSection->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Location section updated successfully',
            'data' => $locationSection
        ]);
    }

    public function destroy(LocationSection $locationSection): JsonResponse
    {
        $locationSection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Location section deleted successfully'
        ]);
    }

    public function getPublicData(): JsonResponse
    {
        $section = LocationSection::with(['contactCards' => function ($query) {
            $query->where('is_active', true)->orderBy('order');
        }, 'locationMap' => function ($query) {
            $query->where('is_active', true);
        }, 'sectionStyles'])
            ->where('is_active', true)
            ->first();

        if (!$section) {
            return response()->json([
                'success' => false,
                'message' => 'No active location section found'
            ], 404);
        }

        // Transform styles for frontend consumption
        $styles = [];
        foreach ($section->sectionStyles as $style) {
            $styles[$style->element_class] = $style->styles;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'section' => $section,
                'styles' => $styles
            ]
        ]);
    }
}
