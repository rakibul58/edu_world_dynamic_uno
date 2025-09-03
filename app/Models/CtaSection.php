<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtaSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'title_color',
        'title_font_size',
        'title_font_weight',
        'description_color',
        'description_font_size',
        'description_font_weight',
        'background_type',
        'background_color',
        'gradient_start',
        'gradient_end',
        'gradient_direction',
        'padding_top',
        'padding_bottom',
        'text_align',
        'buttons',
        'is_active',
        'order'
    ];

    protected $casts = [
        'buttons' => 'array',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'buttons' => '[]',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public static function getActive()
    {
        return self::active()->ordered()->first();
    }

    public function activate()
    {
        // Deactivate all other sections
        self::where('id', '!=', $this->id)->update(['is_active' => false]);

        // Activate this section
        $this->update(['is_active' => true]);

        return $this;
    }
}
