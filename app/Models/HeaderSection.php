<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo_type',
        'logo_image_path',
        'logo_text',
        'logo_text_style',
        'logo_styles',
        'logo_link',
        'logo_responsive',
        'logo_tagline',
        'tagline_styles',
        'buttons',
        'nav_styles',
        'is_active'
    ];

    protected $casts = [
        'logo_text_style' => 'array',
        'logo_styles' => 'array',
        'logo_link' => 'array',
        'logo_responsive' => 'array',
        'tagline_styles' => 'array',
        'buttons' => 'array',
        'nav_styles' => 'array'
    ];

    public static function getActiveHeader()
    {
        return self::where('is_active', true)->first();
    }
}
