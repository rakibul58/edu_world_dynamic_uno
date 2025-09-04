<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ContactCard;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContactCardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ContactCard::query();

        if ($request->has('location_section_id')) {
            $query->where('location_section_id', $request->location_section_id);
        }

        $cards = $query->orderBy('order')->get();

        return response()->json([
            'success' => true,
            'data' => $cards
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'location_section_id' => 'required|exists:location_sections,id',
            'icon' => 'required|string|max:10',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $card = ContactCard::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Contact card created successfully',
            'data' => $card
        ], 201);
    }

    public function show(ContactCard $contactCard): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $contactCard
        ]);
    }

    public function update(Request $request, ContactCard $contactCard): JsonResponse
    {
        $validated = $request->validate([
            'icon' => 'sometimes|required|string|max:10',
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $contactCard->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Contact card updated successfully',
            'data' => $contactCard
        ]);
    }

    public function destroy(ContactCard $contactCard): JsonResponse
    {
        $contactCard->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact card deleted successfully'
        ]);
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cards' => 'required|array',
            'cards.*.id' => 'required|exists:contact_cards,id',
            'cards.*.order' => 'required|integer|min:0'
        ]);

        foreach ($validated['cards'] as $cardData) {
            ContactCard::where('id', $cardData['id'])
                ->update(['order' => $cardData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Contact cards reordered successfully'
        ]);
    }
}
