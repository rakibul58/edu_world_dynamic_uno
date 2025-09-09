<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AcademicProgramsSettings;
use Illuminate\Http\Request;

class AcademicProgramsSettingsController extends Controller
{
    public function index()
    {
        $settings = AcademicProgramsSettings::getSettings();
        return response()->json($settings);
    }

    public function store(Request $request)
    {


        $validated = $request->validate([
            'section_title' => 'required|string|max:255',
            'section_description' => 'required|string',
            'section_title_color' => 'nullable|string|max:20',
            'section_title_font_size' => 'nullable|string|max:20',
            'section_title_font_weight' => 'nullable|string|max:20',
            'section_description_color' => 'nullable|string|max:20',
            'section_description_font_size' => 'nullable|string|max:20',
            'section_description_font_weight' => 'nullable|string|max:20',
            'section_background_color' => 'nullable|string|max:20',
            'section_padding' => 'nullable|string|max:50',
            'header_text_align' => 'nullable|string|in:left,center,right',
            'header_margin_bottom' => 'nullable|string|max:20',
            'container_max_width' => 'nullable|string|max:20',
            'container_padding' => 'nullable|string|max:50',
            'programs_gap' => 'nullable|string|max:20',
            'enable_animations' => 'nullable|in:true,false,1,0',
            'animation_duration' => 'nullable|string|max:20',
            'animation_delay_increment' => 'nullable|string|max:20',
            'is_active' => 'nullable|in:true,false,1,0',
        ]);

        $validated['enable_animations'] = filter_var($validated['enable_animations'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $validated['is_active'] = filter_var($validated['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN);

        $settings = AcademicProgramsSettings::first();

        if ($settings) {
            $settings->update($validated);
        } else {
            $settings = AcademicProgramsSettings::create($validated);
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully',
            'data' => $settings
        ]);
    }
}
