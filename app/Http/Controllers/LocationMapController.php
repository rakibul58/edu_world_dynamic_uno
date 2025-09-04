<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LocationMap;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocationMapController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'location_section_id' => 'required|exists:location_sections,id',
            'embed_url' => 'required|url',
            'width' => 'integer|min:300|max:1200',
            'height' => 'integer|min:200|max:800',
            'border_radius' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);

        $map = LocationMap::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Location map created successfully',
            'data' => $map
        ], 201);
    }

    public function update(Request $request, LocationMap $locationMap): JsonResponse
    {
        $validated = $request->validate([
            'embed_url' => 'sometimes|required|url',
            'width' => 'integer|min:300|max:1200',
            'height' => 'integer|min:200|max:800',
            'border_radius' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);

        $locationMap->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Location map updated successfully',
            'data' => $locationMap
        ]);
    }

    public function destroy(LocationMap $locationMap): JsonResponse
    {
        $locationMap->delete();

        return response()->json([
            'success' => true,
            'message' => 'Location map deleted successfully'
        ]);
    }
}
