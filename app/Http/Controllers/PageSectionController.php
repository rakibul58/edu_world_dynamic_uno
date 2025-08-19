<?php

namespace App\Http\Controllers;

use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PageSectionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $pageName = $request->get('page', 'home');

        $sections = PageSection::forPage($pageName)
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sections
        ]);
    }

    public function getVisibleSections(Request $request): JsonResponse
    {
        $pageName = $request->get('page', 'home');

        $sections = PageSection::forPage($pageName)
            ->visible()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sections
        ]);
    }

    public function update(Request $request, PageSection $section): JsonResponse
    {
        $request->validate([
            'section_name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'is_visible' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0',
            'settings' => 'sometimes|array'
        ]);

        $section->update($request->only([
            'section_name',
            'description',
            'is_visible',
            'sort_order',
            'settings'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Section updated successfully',
            'data' => $section
        ]);
    }

    public function toggleVisibility(PageSection $section): JsonResponse
    {
        $section->update([
            'is_visible' => !$section->is_visible
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Section visibility toggled',
            'data' => $section
        ]);
    }

    public function updateOrder(Request $request): JsonResponse
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:page_sections,id',
            'sections.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($request->sections as $sectionData) {
            PageSection::where('id', $sectionData['id'])
                ->update(['sort_order' => $sectionData['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Section order updated successfully'
        ]);
    }

    public function bulkToggle(Request $request): JsonResponse
    {
        $request->validate([
            'section_ids' => 'required|array',
            'section_ids.*' => 'exists:page_sections,id',
            'is_visible' => 'required|boolean'
        ]);

        PageSection::whereIn('id', $request->section_ids)
            ->update(['is_visible' => $request->is_visible]);

        return response()->json([
            'success' => true,
            'message' => 'Sections updated successfully'
        ]);
    }
}
