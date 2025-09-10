<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'name',
        'role',
        'initials',
        'avatar_url',
        'avatar_type',
        'avatar_bg_color',
        'avatar_text_color',
        'text_color',
        'name_color',
        'role_color',
        'text_font_size',
        'name_font_size',
        'role_font_size',
        'text_font_weight',
        'name_font_weight',
        'role_font_weight',
        'card_background',
        'card_border_color',
        'card_border_radius',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($testimonial) {
            if (is_null($testimonial->order)) {
                $testimonial->order = static::max('order') + 1;
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
