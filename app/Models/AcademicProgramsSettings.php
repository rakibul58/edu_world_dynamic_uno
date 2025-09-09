<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicProgramsSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_title',
        'section_description',
        'section_title_color',
        'section_title_font_size',
        'section_title_font_weight',
        'section_description_color',
        'section_description_font_size',
        'section_description_font_weight',
        'section_background_color',
        'section_padding',
        'header_text_align',
        'header_margin_bottom',
        'container_max_width',
        'container_padding',
        'programs_gap',
        'enable_animations',
        'animation_duration',
        'animation_delay_increment',
        'is_active',
    ];

    protected $casts = [
        'enable_animations' => 'boolean',
        'is_active' => 'boolean',
    ];

    public static function getSettings()
    {
        return self::first() ?? new self();
    }
}
