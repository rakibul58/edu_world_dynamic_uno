<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_type',
        'logo_text',
        'logo_image_path',
        'logo_tagline',
        'logo_link',
        'buttons',
        'logo_styles',
        'tagline_styles',
        'nav_styles',
        'advanced_settings',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'logo_link' => 'array',
        'buttons' => 'array',
        'logo_styles' => 'array',
        'tagline_styles' => 'array',
        'nav_styles' => 'array',
        'advanced_settings' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
