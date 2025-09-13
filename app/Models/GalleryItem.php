<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'icon',
        'description',
        'background_type',
        'gradient_start',
        'gradient_end',
        'gradient_direction',
        'background_color',
        'background_image',
        'title_color',
        'title_font_size',
        'title_font_weight',
        'description_color',
        'description_font_size',
        'item_height',
        'border_radius',
        'icon_size',
        'hover_transform',
        'hover_shadow',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
