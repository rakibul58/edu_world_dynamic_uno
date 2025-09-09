<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AcademicProgram;
use App\Models\AcademicProgramsSettings;

class AcademicProgramsPublicController extends Controller
{
    public function index()
    {
        $settings = AcademicProgramsSettings::getSettings();

        if (!$settings->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Academic programs section is not active'
            ], 404);
        }

        $programs = AcademicProgram::active()->ordered()->get();

        return response()->json([
            'success' => true,
            'settings' => $settings,
            'programs' => $programs
        ]);
    }

    public function show(AcademicProgram $program)
    {
        if (!$program->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Program not found or not active'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'program' => $program
        ]);
    }

    public function getComponentData()
    {
        $settings = AcademicProgramsSettings::getSettings();
        $programs = AcademicProgram::active()->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => [
                'settings' => $settings,
                'programs' => $programs->map(function ($program) {
                    return [
                        'icon' => $program->icon,
                        'title' => $program->title,
                        'ageRange' => $program->age_range,
                        'items' => $program->items,
                        'styling' => [
                            'icon' => [
                                'backgroundColor' => $program->icon_background_color,
                                'color' => $program->icon_color,
                                'fontSize' => $program->icon_size,
                                'width' => $program->icon_width,
                                'height' => $program->icon_height,
                                'borderRadius' => $program->icon_border_radius,
                            ],
                            'title' => [
                                'color' => $program->title_color,
                                'fontSize' => $program->title_font_size,
                                'fontWeight' => $program->title_font_weight,
                                'marginBottom' => $program->title_margin_bottom,
                            ],
                            'ageRange' => [
                                'color' => $program->age_range_color,
                                'fontSize' => $program->age_range_font_size,
                                'fontWeight' => $program->age_range_font_weight,
                            ],
                            'items' => [
                                'color' => $program->items_color,
                                'fontSize' => $program->items_font_size,
                                'fontWeight' => $program->items_font_weight,
                                'padding' => $program->items_padding,
                                'checkColor' => $program->items_check_color,
                                'paddingLeft' => $program->items_padding_left,
                            ],
                            'card' => [
                                'backgroundColor' => $program->card_background_color,
                                'borderRadius' => $program->card_border_radius,
                                'padding' => $program->card_padding,
                                'shadow' => $program->card_shadow,
                                'hoverShadow' => $program->card_hover_shadow,
                                'hoverTransform' => $program->card_hover_transform,
                                'headerGap' => $program->header_gap,
                                'contentMarginTop' => $program->content_margin_top,
                            ],
                        ],
                    ];
                }),
            ]
        ]);
    }
}
