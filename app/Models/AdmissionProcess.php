<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'background_color',
        'text_color',
        'padding_top',
        'padding_bottom',
        'text_align',
        'title_color',
        'title_font_size',
        'title_font_weight',
        'description_color',
        'description_font_size',
        'description_opacity',
        'step_number_bg_color',
        'step_number_text_color',
        'step_title_color',
        'step_title_font_size',
        'step_description_color',
        'step_description_opacity',
        'grid_min_width',
        'step_gap',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function steps()
    {
        return $this->hasMany(AdmissionStep::class)->orderBy('sort_order');
    }

    public function activeSteps()
    {
        return $this->hasMany(AdmissionStep::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function activate()
    {
        // Deactivate all other admission processes
        self::where('id', '!=', $this->id)->update(['is_active' => false]);

        // Activate this one
        $this->update(['is_active' => true]);
    }

    public static function getActive()
    {
        return self::active()->with('activeSteps')->first();
    }
}
