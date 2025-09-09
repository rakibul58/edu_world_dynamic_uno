<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'age_range',
        'icon',
        'items',
        'order',
        'is_active',
        
        // Icon styling
        'icon_background_color',
        'icon_color',
        'icon_size',
        'icon_width',
        'icon_height',
        'icon_border_radius',
        
        // Title styling
        'title_color',
        'title_font_size',
        'title_font_weight',
        'title_margin_bottom',
        
        // Age range styling
        'age_range_color',
        'age_range_font_size',
        'age_range_font_weight',
        
        // Items styling
        'items_color',
        'items_font_size',
        'items_font_weight',
        'items_padding',
        'items_check_color',
        'items_padding_left',
        
        // Card styling
        'card_background_color',
        'card_border_radius',
        'card_padding',
        'card_shadow',
        'card_hover_shadow',
        'card_hover_transform',
        'header_gap',
        'content_margin_top',
    ];

    protected $casts = [
        'items' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}