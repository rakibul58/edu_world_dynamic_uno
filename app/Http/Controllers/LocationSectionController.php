<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LocationSection;
use App\Models\ContactCard;
use App\Models\LocationMap;
use App\Models\SectionStyle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LocationSectionController extends Controller
{
    public function index(): JsonResponse
    {
        $sections = LocationSection::with(['contactCards', 'locationMap', 'sectionStyles'])
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
            'is_active' => 'boolean',
            // Add validation for nested data
            'contact_cards' => 'sometimes|array',
            'contact_cards.*.icon' => 'required|string|max:10',
            'contact_cards.*.title' => 'required|string|max:255',
            'contact_cards.*.content' => 'required|string',
            'contact_cards.*.is_active' => 'boolean',
            'map_embed_url' => 'sometimes|required|url',
            'map_width' => 'sometimes|integer|min:300|max:1200',
            'map_height' => 'sometimes|integer|min:200|max:800',
            'map_border_radius' => 'nullable|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            // Create the main section
            $locationSection = LocationSection::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'background_color' => $validated['background_color'] ?? null,
                'padding_top' => $validated['padding_top'] ?? null,
                'padding_bottom' => $validated['padding_bottom'] ?? null,
                'is_active' => $validated['is_active'] ?? false,
            ]);

            // Create contact cards if provided
            if (isset($validated['contact_cards'])) {
                foreach ($validated['contact_cards'] as $index => $cardData) {
                    ContactCard::create([
                        'location_section_id' => $locationSection->id,
                        'icon' => $cardData['icon'],
                        'title' => $cardData['title'],
                        'content' => $cardData['content'],
                        'order' => $index,
                        'is_active' => $cardData['is_active'] ?? true,
                    ]);
                }
            }

            // Create location map if provided
            if (isset($validated['map_embed_url'])) {
                LocationMap::create([
                    'location_section_id' => $locationSection->id,
                    'embed_url' => $validated['map_embed_url'],
                    'width' => $validated['map_width'] ?? 600,
                    'height' => $validated['map_height'] ?? 450,
                    'border_radius' => $validated['map_border_radius'] ?? '15px',
                    'is_active' => true,
                ]);
            }

            DB::commit();

            $locationSection->load(['contactCards', 'locationMap', 'sectionStyles']);

            return response()->json([
                'success' => true,
                'message' => 'Location section created successfully',
                'data' => $locationSection
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create location section: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, LocationSection $locationSection): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'background_color' => 'nullable|string|max:10',
            'padding_top' => 'nullable|integer|min:0|max:200',
            'padding_bottom' => 'nullable|integer|min:0|max:200',
            'is_active' => 'boolean',
            // Add validation for nested data
            'contact_cards' => 'sometimes|array',
            'contact_cards.*.id' => 'sometimes|exists:contact_cards,id',
            'contact_cards.*.icon' => 'required|string|max:10',
            'contact_cards.*.title' => 'required|string|max:255',
            'contact_cards.*.content' => 'required|string',
            'contact_cards.*.is_active' => 'boolean',
            'map_embed_url' => 'sometimes|required|url',
            'map_width' => 'sometimes|integer|min:300|max:1200',
            'map_height' => 'sometimes|integer|min:200|max:800',
            'map_border_radius' => 'nullable|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            // Update main section
            $locationSection->update([
                'title' => $validated['title'] ?? $locationSection->title,
                'description' => $validated['description'] ?? $locationSection->description,
                'background_color' => $validated['background_color'] ?? $locationSection->background_color,
                'padding_top' => $validated['padding_top'] ?? $locationSection->padding_top,
                'padding_bottom' => $validated['padding_bottom'] ?? $locationSection->padding_bottom,
                'is_active' => $validated['is_active'] ?? $locationSection->is_active,
            ]);

            // Handle contact cards
            if (isset($validated['contact_cards'])) {
                $existingCardIds = $locationSection->contactCards->pluck('id')->toArray();
                $updatedCardIds = [];

                foreach ($validated['contact_cards'] as $index => $cardData) {
                    if (isset($cardData['id']) && in_array($cardData['id'], $existingCardIds)) {
                        // Update existing card
                        $card = ContactCard::find($cardData['id']);
                        $card->update([
                            'icon' => $cardData['icon'],
                            'title' => $cardData['title'],
                            'content' => $cardData['content'],
                            'order' => $index,
                            'is_active' => $cardData['is_active'] ?? true,
                        ]);
                        $updatedCardIds[] = $cardData['id'];
                    } else {
                        // Create new card
                        $card = ContactCard::create([
                            'location_section_id' => $locationSection->id,
                            'icon' => $cardData['icon'],
                            'title' => $cardData['title'],
                            'content' => $cardData['content'],
                            'order' => $index,
                            'is_active' => $cardData['is_active'] ?? true,
                        ]);
                        $updatedCardIds[] = $card->id;
                    }
                }

                // Delete cards that were removed
                $cardsToDelete = array_diff($existingCardIds, $updatedCardIds);
                if (!empty($cardsToDelete)) {
                    ContactCard::whereIn('id', $cardsToDelete)->delete();
                }
            }

            // Handle location map
            if (isset($validated['map_embed_url'])) {
                if ($locationSection->locationMap) {
                    // Update existing map
                    $locationSection->locationMap->update([
                        'embed_url' => $validated['map_embed_url'],
                        'width' => $validated['map_width'] ?? $locationSection->locationMap->width,
                        'height' => $validated['map_height'] ?? $locationSection->locationMap->height,
                        'border_radius' => $validated['map_border_radius'] ?? $locationSection->locationMap->border_radius,
                    ]);
                } else {
                    // Create new map
                    LocationMap::create([
                        'location_section_id' => $locationSection->id,
                        'embed_url' => $validated['map_embed_url'],
                        'width' => $validated['map_width'] ?? 600,
                        'height' => $validated['map_height'] ?? 450,
                        'border_radius' => $validated['map_border_radius'] ?? '15px',
                        'is_active' => true,
                    ]);
                }
            }

            // Handle active section logic
            if (isset($validated['is_active']) && $validated['is_active']) {
                LocationSection::where('id', '!=', $locationSection->id)
                    ->update(['is_active' => false]);
            }

            DB::commit();

            $locationSection->load(['contactCards', 'locationMap', 'sectionStyles']);

            return response()->json([
                'success' => true,
                'message' => 'Location section updated successfully',
                'data' => $locationSection
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update location section: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(LocationSection $locationSection): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Delete related records first
            $locationSection->contactCards()->delete();
            $locationSection->locationMap()->delete();
            $locationSection->sectionStyles()->delete();

            // Delete the main section
            $locationSection->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Location section deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete location section: ' . $e->getMessage()
            ], 500);
        }
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
