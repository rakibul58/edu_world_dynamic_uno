<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeaturesSection extends Model
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
        'grid_columns',
        'grid_gap',
        'container_max_width',
        'container_padding',
        'enable_animations',
        'animation_duration',
        'animation_delay_increment',
        'mobile_breakpoint',
        'mobile_grid_columns',
        'section_background_image',
        'is_active'
    ];

    protected $casts = [
        'enable_animations' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function features()
    {
        return $this->hasMany(FeaturesItem::class)->orderBy('order');
    }

    public function activeFeatures()
    {
        return $this->features()->where('is_active', true);
    }
}
