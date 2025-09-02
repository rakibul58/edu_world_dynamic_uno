<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description_1',
        'description_2',
        'background_color',
        'title_color',
        'title_font_size',
        'title_font_weight',
        'text_color',
        'text_font_size',
        'text_font_weight',
        'image_path',
        'gradient_start',
        'gradient_end',
        'gradient_direction',
        'right_side_type',
        'image_icon',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function stats()
    {
        return $this->hasMany(AboutStat::class)->orderBy('sort_order');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('uploads/images/' . $this->image_path);
        }
        return null;
    }

    public function getGradientStyleAttribute()
    {
        return "linear-gradient({$this->gradient_direction}, {$this->gradient_start}, {$this->gradient_end})";
    }
}
