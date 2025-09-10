<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilitiesSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'background_color',
        'is_active',
        'title_color',
        'title_font_size',
        'title_font_weight',
        'subtitle_color',
        'subtitle_font_size',
        'subtitle_font_weight',
        'featured_title',
        'featured_description',
        'featured_type',
        'featured_image_path',
        'featured_gradient_start',
        'featured_gradient_end',
        'featured_gradient_direction',
        'featured_tint_color',
        'featured_tint_opacity',
        'featured_text_color',
        'featured_title_font_size',
        'featured_desc_font_size',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'featured_tint_opacity' => 'float',
    ];

    public function facilityItems()
    {
        return $this->hasMany(FacilityItem::class)->orderBy('sort_order');
    }

    public function facilityButtons()
    {
        return $this->hasMany(FacilityButton::class)->orderBy('sort_order');
    }

    // Get active section
    public static function getActive()
    {
        return self::where('is_active', true)
            ->with(['facilityItems', 'facilityButtons'])
            ->first();
    }
}
