<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeaturesItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon',
        'title',
        'description',
        'color',
        'order',
        'is_active',
        'title_color',
        'title_font_size',
        'title_font_weight',
        'description_color',
        'description_font_size',
        'description_font_weight',
        'card_background_color',
        'card_border_radius',
        'card_shadow',
        'card_hover_shadow',
        'card_hover_transform',
        'card_border_top_width',
        'card_border_top_style',
        'card_padding',
        'icon_size',
        'icon_font_size',
        'icon_color',
        'icon_margin_bottom'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    public function section()
    {
        return $this->belongsTo(FeaturesSection::class);
    }
}
