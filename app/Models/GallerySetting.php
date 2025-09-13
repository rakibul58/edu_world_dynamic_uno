<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GallerySetting extends Model
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
        'section_background_color',
        'section_padding',
        'header_text_align',
        'grid_columns',
        'grid_gap',
        'container_max_width',
        'container_padding',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
