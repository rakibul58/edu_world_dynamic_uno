<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestimonialSectionSettings extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'background_color',
        'title_color',
        'text_color',
        'card_background',
        'card_border_color',
        'avatar_background',
        'animation_style',
        'animation_duration',
        'columns'
    ];

    protected $casts = [
        'animation_duration' => 'integer'
    ];

    public static function getSettings()
    {
        $settings = static::first();

        if (!$settings) {
            $settings = static::create([]);
        }

        return $settings;
    }
}
