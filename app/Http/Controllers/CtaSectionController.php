<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CtaSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CtaSectionController extends Controller
{
    public function index()
    {
        $sections = CtaSection::orderBy('order')->orderBy('created_at', 'desc')->get();
        return response()->json($sections);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'title_color' => 'required|string|max:50',
            'title_font_size' => 'required|string|max:20',
            'title_font_weight' => 'required|string|max:10',
            'description_color' => 'required|string|max:50',
            'description_font_size' => 'required|string|max:20',
            'description_font_weight' => 'required|string|max:10',
            'background_type' => 'required|in:solid,gradient',
            'background_color' => 'required|string|max:50',
            'gradient_start' => 'nullable|string|max:50',
            'gradient_end' => 'nullable|string|max:50',
            'gradient_direction' => 'nullable|string|max:20',
            'padding_top' => 'required|string|max:20',
            'padding_bottom' => 'required|string|max:20',
            'text_align' => 'required|in:left,center,right',
            'buttons' => 'nullable|array',
            'buttons.*.text' => 'required|string|max:100',
            'buttons.*.url' => 'nullable|string|max:500',
            'buttons.*.background_color' => 'required|string|max:50',
            'buttons.*.text_color' => 'required|string|max:50',
            'buttons.*.hover_background' => 'nullable|string|max:50',
            'buttons.*.hover_text_color' => 'required|string|max:50',
            'buttons.*.border_color' => 'required|string|max:50',
            'buttons.*.hover_border_color' => 'required|string|max:50',
            'buttons.*.font_size' => 'required|string|max:20',
            'buttons.*.font_weight' => 'required|string|max:10',
            'buttons.*.padding' => 'required|string|max:50',
            'buttons.*.border_radius' => 'required|string|max:20',
            'buttons.*.border_width' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get the highest order number and add 1
            $maxOrder = CtaSection::max('order') ?? 0;

            $section = CtaSection::create(array_merge($request->all(), [
                'order' => $maxOrder + 1
            ]));

            return response()->json($section, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating CTA section',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(CtaSection $ctaSection)
    {
        return response()->json($ctaSection);
    }

    public function update(Request $request, CtaSection $ctaSection)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'title_color' => 'required|string|max:50',
            'title_font_size' => 'required|string|max:20',
            'title_font_weight' => 'required|string|max:10',
            'description_color' => 'required|string|max:50',
            'description_font_size' => 'required|string|max:20',
            'description_font_weight' => 'required|string|max:10',
            'background_type' => 'required|in:solid,gradient',
            'background_color' => 'required|string|max:50',
            'gradient_start' => 'nullable|string|max:50',
            'gradient_end' => 'nullable|string|max:50',
            'gradient_direction' => 'nullable|string|max:20',
            'padding_top' => 'required|string|max:20',
            'padding_bottom' => 'required|string|max:20',
            'text_align' => 'required|in:left,center,right',
            'buttons' => 'nullable|array',
            'buttons.*.text' => 'required|string|max:100',
            'buttons.*.url' => 'nullable|string|max:500',
            'buttons.*.background_color' => 'required|string|max:50',
            'buttons.*.text_color' => 'required|string|max:50',
            'buttons.*.hover_background' => 'nullable|string|max:50',
            'buttons.*.hover_text_color' => 'required|string|max:50',
            'buttons.*.border_color' => 'required|string|max:50',
            'buttons.*.hover_border_color' => 'required|string|max:50',
            'buttons.*.font_size' => 'required|string|max:20',
            'buttons.*.font_weight' => 'required|string|max:10',
            'buttons.*.padding' => 'required|string|max:50',
            'buttons.*.border_radius' => 'required|string|max:20',
            'buttons.*.border_width' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $ctaSection->update($request->all());
            return response()->json($ctaSection);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating CTA section',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy(CtaSection $ctaSection)
    {
        try {
            // Don't allow deletion of active sections
            if ($ctaSection->is_active) {
                return response()->json([
                    'message' => 'Cannot delete an active section. Please deactivate it first.'
                ], 422);
            }

            $ctaSection->delete();

            return response()->json([
                'message' => 'CTA section deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting CTA section',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function activate(CtaSection $ctaSection)
    {
        try {
            $ctaSection->activate();

            return response()->json([
                'message' => 'CTA section activated successfully',
                'section' => $ctaSection->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error activating CTA section',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:cta_sections,id',
            'sections.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            foreach ($request->sections as $sectionData) {
                CtaSection::where('id', $sectionData['id'])
                    ->update(['order' => $sectionData['order']]);
            }

            return response()->json([
                'message' => 'Sections reordered successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error reordering sections',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getActive()
    {
        $section = CtaSection::getActive();
        return response()->json($section);
    }
}
