<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderSection extends Model
{
    use HasFactory;

    protected $table = 'header_sections';

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
        'sort_order' => 'integer'
    ];

    protected $attributes = [
        'is_active' => false,
        'sort_order' => 1,
        'logo_link' => '{"url":"/","target":"_self"}',
        'buttons' => '[]',
        'logo_styles' => '{"image":{"width":"180px","height":"auto"},"text":{"font_family":"\'Poppins\', sans-serif","font_size":"24px","font_weight":"700","color":"#ffffff"}}',
        'tagline_styles' => '{"font_size":"14px","color":"#d1d5db"}',
        'nav_styles' => '{"background":"#102e4a","padding":"0 20px","box_shadow":"0 2px 10px rgba(0,0,0,0.1)"}',
        'advanced_settings' => '{"enableAnimations":true,"stickyHeader":false,"hideOnMobile":false,"transitionDuration":"0.3s","zIndex":1000}'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    // Mutators
    public function setLogoLinkAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['logo_link'] = $value;
        } else {
            $this->attributes['logo_link'] = json_encode($value);
        }
    }

    public function setButtonsAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['buttons'] = $value;
        } else {
            $this->attributes['buttons'] = json_encode($value);
        }
    }

    public function setLogoStylesAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['logo_styles'] = $value;
        } else {
            $this->attributes['logo_styles'] = json_encode($value);
        }
    }

    public function setTaglineStylesAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['tagline_styles'] = $value;
        } else {
            $this->attributes['tagline_styles'] = json_encode($value);
        }
    }

    public function setNavStylesAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['nav_styles'] = $value;
        } else {
            $this->attributes['nav_styles'] = json_encode($value);
        }
    }

    public function setAdvancedSettingsAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['advanced_settings'] = $value;
        } else {
            $this->attributes['advanced_settings'] = json_encode($value);
        }
    }
}
