<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsEventsSectionSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'section_title',
        'section_description',
        'section_title_color',
        'section_title_font_size',
        'section_title_font_weight',
        'section_description_color',
        'section_description_font_size',
        'section_description_font_weight',
        'section_background_color',
        'section_background_image',
        'section_padding',
        'grid_columns',
        'grid_gap',
        'container_max_width',
        'container_padding',
        'header_text_align',
        'header_margin_bottom',
        'enable_animations',
        'animation_duration',
        'animation_delay_increment',
        'mobile_breakpoint',
        'mobile_grid_columns',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'enable_animations' => 'boolean'
    ];

    // Get settings by key
    public static function getSettings($key = 'main_settings')
    {
        return self::where('key', $key)->first();
    }

    // Get or create settings
    public static function getOrCreateSettings($key = 'main_settings')
    {
        return self::firstOrCreate(['key' => $key]);
    }

    // Update settings
    public static function updateSettings($key = 'main_settings', array $data = [])
    {
        $settings = self::getOrCreateSettings($key);
        $settings->update($data);
        return $settings;
    }

    // Get CSS variables for frontend
    public function getCssVariablesAttribute()
    {
        return [
            '--section-title-color' => $this->section_title_color,
            '--section-title-font-size' => $this->section_title_font_size,
            '--section-title-font-weight' => $this->section_title_font_weight,
            '--section-description-color' => $this->section_description_color,
            '--section-description-font-size' => $this->section_description_font_size,
            '--section-description-font-weight' => $this->section_description_font_weight,
            '--section-background-color' => $this->section_background_color,
            '--section-padding' => $this->section_padding,
            '--grid-columns' => $this->grid_columns,
            '--grid-gap' => $this->grid_gap,
            '--container-max-width' => $this->container_max_width,
            '--container-padding' => $this->container_padding,
            '--header-text-align' => $this->header_text_align,
            '--header-margin-bottom' => $this->header_margin_bottom,
            '--animation-duration' => $this->animation_duration,
            '--animation-delay-increment' => $this->animation_delay_increment,
            '--mobile-breakpoint' => $this->mobile_breakpoint,
            '--mobile-grid-columns' => $this->mobile_grid_columns,
        ];
    }

    // Handle background image upload
    public function handleBackgroundImageUpload($file)
    {
        if ($file) {
            // Delete old image if exists
            if ($this->section_background_image && file_exists(public_path($this->section_background_image))) {
                unlink(public_path($this->section_background_image));
            }

            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Ensure directory exists
            $uploadPath = public_path('uploads/news_events');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Move file
            $file->move($uploadPath, $filename);

            return 'uploads/news_events/' . $filename;
        }

        return null;
    }

    // Delete image when model is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($settings) {
            if ($settings->section_background_image && file_exists(public_path($settings->section_background_image))) {
                unlink(public_path($settings->section_background_image));
            }
        });
    }
}
