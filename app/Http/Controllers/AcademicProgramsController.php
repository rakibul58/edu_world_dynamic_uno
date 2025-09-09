<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AcademicProgram;
use Illuminate\Http\Request;

class AcademicProgramsController extends Controller
{
    public function index()
    {
        $programs = AcademicProgram::ordered()->get();
        return response()->json($programs);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'age_range' => 'required|string|max:255',
            'icon' => 'required|string|max:10',
            'items' => 'required|string',

            // Icon styling
            'icon_background_color' => 'nullable|string|max:20',
            'icon_color' => 'nullable|string|max:20',
            'icon_size' => 'nullable|string|max:20',
            'icon_width' => 'nullable|string|max:20',
            'icon_height' => 'nullable|string|max:20',
            'icon_border_radius' => 'nullable|string|max:20',

            // Title styling
            'title_color' => 'nullable|string|max:20',
            'title_font_size' => 'nullable|string|max:20',
            'title_font_weight' => 'nullable|string|max:20',
            'title_margin_bottom' => 'nullable|string|max:20',

            // Age range styling
            'age_range_color' => 'nullable|string|max:20',
            'age_range_font_size' => 'nullable|string|max:20',
            'age_range_font_weight' => 'nullable|string|max:20',

            // Items styling
            'items_color' => 'nullable|string|max:20',
            'items_font_size' => 'nullable|string|max:20',
            'items_font_weight' => 'nullable|string|max:20',
            'items_padding' => 'nullable|string|max:50',
            'items_check_color' => 'nullable|string|max:20',
            'items_padding_left' => 'nullable|string|max:20',

            // Card styling
            'card_background_color' => 'nullable|string|max:20',
            'card_border_radius' => 'nullable|string|max:20',
            'card_padding' => 'nullable|string|max:20',
            'card_shadow' => 'nullable|string|max:100',
            'card_hover_shadow' => 'nullable|string|max:100',
            'card_hover_transform' => 'nullable|string|max:50',
            'header_gap' => 'nullable|string|max:20',
            'content_margin_top' => 'nullable|string|max:20',
        ]);

        // Parse items JSON
        $validated['items'] = json_decode($validated['items'], true) ?: [];

        // Set order for new program
        $validated['order'] = AcademicProgram::max('order') + 1;

        $program = AcademicProgram::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Academic program created successfully',
            'data' => $program
        ], 201);
    }

    public function show(AcademicProgram $program)
    {
        return response()->json($program);
    }

    public function update(Request $request, AcademicProgram $program)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'age_range' => 'required|string|max:255',
            'icon' => 'required|string|max:10',
            'items' => 'required|string',

            // All styling fields (same as store method)
            'icon_background_color' => 'nullable|string|max:20',
            'icon_color' => 'nullable|string|max:20',
            'icon_size' => 'nullable|string|max:20',
            'icon_width' => 'nullable|string|max:20',
            'icon_height' => 'nullable|string|max:20',
            'icon_border_radius' => 'nullable|string|max:20',
            'title_color' => 'nullable|string|max:20',
            'title_font_size' => 'nullable|string|max:20',
            'title_font_weight' => 'nullable|string|max:20',
            'title_margin_bottom' => 'nullable|string|max:20',
            'age_range_color' => 'nullable|string|max:20',
            'age_range_font_size' => 'nullable|string|max:20',
            'age_range_font_weight' => 'nullable|string|max:20',
            'items_color' => 'nullable|string|max:20',
            'items_font_size' => 'nullable|string|max:20',
            'items_font_weight' => 'nullable|string|max:20',
            'items_padding' => 'nullable|string|max:50',
            'items_check_color' => 'nullable|string|max:20',
            'items_padding_left' => 'nullable|string|max:20',
            'card_background_color' => 'nullable|string|max:20',
            'card_border_radius' => 'nullable|string|max:20',
            'card_padding' => 'nullable|string|max:20',
            'card_shadow' => 'nullable|string|max:100',
            'card_hover_shadow' => 'nullable|string|max:100',
            'card_hover_transform' => 'nullable|string|max:50',
            'header_gap' => 'nullable|string|max:20',
            'content_margin_top' => 'nullable|string|max:20',
        ]);

        // Parse items JSON
        $validated['items'] = json_decode($validated['items'], true) ?: [];

        $program->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Academic program updated successfully',
            'data' => $program
        ]);
    }

    public function destroy(AcademicProgram $program)
    {
        $program->delete();

        return response()->json([
            'success' => true,
            'message' => 'Academic program deleted successfully'
        ]);
    }

    public function toggleStatus(AcademicProgram $program)
    {
        $program->update(['is_active' => !$program->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Program status updated successfully',
            'data' => $program
        ]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'programs' => 'required|array',
            'programs.*.id' => 'required|exists:academic_programs,id',
            'programs.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->programs as $programData) {
            AcademicProgram::where('id', $programData['id'])
                ->update(['order' => $programData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Program order updated successfully'
        ]);
    }
}
