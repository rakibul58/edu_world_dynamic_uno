<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AcademicProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AcademicProgramsController extends Controller
{
    public function index()
    {
        $programs = AcademicProgram::ordered()->get();
        return response()->json($programs);
    }

    public function store(Request $request)
    {
        $validated = $this->validateProgramData($request);
        $processed = $this->processFormData($validated);

        // Set order for new program
        $processed['order'] = AcademicProgram::max('order') + 1;

        $program = AcademicProgram::create($processed);

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

    public function update(Request $request, $id)
    {
        $program = AcademicProgram::findOrFail($id);

        $validated = $this->validateProgramData($request);
        $processed = $this->processFormData($validated);

        $program->update($processed);

        return response()->json([
            'success' => true,
            'message' => 'Academic program updated successfully',
            'data' => $program
        ]);
    }

    public function destroy($id)
    {
        $program = AcademicProgram::findOrFail($id);
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

    private function validateProgramData(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'age_range' => 'required|string|max:255',
            'icon' => 'required|string|max:10',
            'items' => 'required',
            'is_active' => 'nullable',
            'order' => 'nullable|integer|min:0',

            // Icon styling
            'icon_background_color' => 'nullable|string|max:50',
            'icon_color' => 'nullable|string|max:50',
            'icon_size' => 'nullable|string|max:50',
            'icon_width' => 'nullable|string|max:50',
            'icon_height' => 'nullable|string|max:50',
            'icon_border_radius' => 'nullable|string|max:50',

            // Title styling
            'title_color' => 'nullable|string|max:50',
            'title_font_size' => 'nullable|string|max:50',
            'title_font_weight' => 'nullable|string|max:50',
            'title_margin_bottom' => 'nullable|string|max:50',

            // Age range styling
            'age_range_color' => 'nullable|string|max:50',
            'age_range_font_size' => 'nullable|string|max:50',
            'age_range_font_weight' => 'nullable|string|max:50',

            // Items styling
            'items_color' => 'nullable|string|max:50',
            'items_font_size' => 'nullable|string|max:50',
            'items_font_weight' => 'nullable|string|max:50',
            'items_padding' => 'nullable|string|max:50',
            'items_check_color' => 'nullable|string|max:50',
            'items_padding_left' => 'nullable|string|max:50',

            // Card styling
            'card_background_color' => 'nullable|string|max:50',
            'card_border_radius' => 'nullable|string|max:50',
            'card_padding' => 'nullable|string|max:50',
            'card_shadow' => 'nullable|string|max:100',
            'card_hover_shadow' => 'nullable|string|max:100',
            'card_hover_transform' => 'nullable|string|max:50',
            'header_gap' => 'nullable|string|max:50',
            'content_margin_top' => 'nullable|string|max:50',
        ]);
    }

    private function processFormData(array $validated)
    {
        // Create a clean array with only the fields we want
        $allowedFields = [
            'title',
            'age_range',
            'icon',
            'items',
            'order',
            'is_active',
            'icon_background_color',
            'icon_color',
            'icon_size',
            'icon_width',
            'icon_height',
            'icon_border_radius',
            'title_color',
            'title_font_size',
            'title_font_weight',
            'title_margin_bottom',
            'age_range_color',
            'age_range_font_size',
            'age_range_font_weight',
            'items_color',
            'items_font_size',
            'items_font_weight',
            'items_padding',
            'items_check_color',
            'items_padding_left',
            'card_background_color',
            'card_border_radius',
            'card_padding',
            'card_shadow',
            'card_hover_shadow',
            'card_hover_transform',
            'header_gap',
            'content_margin_top'
        ];

        $processed = [];

        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $validated)) {
                $processed[$field] = $validated[$field];
            }
        }

        // Process items - handle both JSON string and array
        if (isset($processed['items'])) {
            if (is_string($processed['items'])) {
                $items = json_decode($processed['items'], true);
                $processed['items'] = is_array($items) ? $items : [$processed['items']];
            } elseif (is_array($processed['items'])) {
                $processed['items'] = $processed['items'];
            } else {
                $processed['items'] = [];
            }
        }

        // Convert various formats to actual boolean
        if (isset($processed['is_active'])) {
            $value = $processed['is_active'];
            if (is_string($value)) {
                $processed['is_active'] = in_array(strtolower($value), ['true', '1', 'yes', 'on']);
            } elseif (is_numeric($value)) {
                $processed['is_active'] = (bool) intval($value);
            } else {
                $processed['is_active'] = (bool) $value;
            }
        }

        // Convert string order to integer
        if (isset($processed['order'])) {
            $processed['order'] = (int) $processed['order'];
        }

        return $processed;
    }
}
