<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class HeroSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active',
        'section_name',
        'title',
        'title_highlight',
        'subtitle',
        'tagline',
        'background_images',
        'slider_interval',
        'enable_slider',
        'background_gradients',
        'cta_buttons',
        'text_styles',
        'overlay_styles',
        'section_styles',
        'navigation',
        'show_navigation',
        'nav_styles',
        'advanced_settings',
        'meta_title',
        'meta_description',
        'meta_tags',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'enable_slider' => 'boolean',
        'show_navigation' => 'boolean',
        'background_images' => 'array',
        'background_gradients' => 'array',
        'cta_buttons' => 'array',
        'text_styles' => 'array',
        'overlay_styles' => 'array',
        'section_styles' => 'array',
        'navigation' => 'array',
        'nav_styles' => 'array',
        'advanced_settings' => 'array',
        'meta_tags' => 'array',
        'slider_interval' => 'integer',
    ];

    protected $attributes = [
        'is_active' => true,
        'enable_slider' => true,
        'show_navigation' => true,
        'slider_interval' => 3500,
    ];

    /**
     * Get the active hero section
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Set only this hero section as active
     */
    public function setAsActive()
    {
        // Deactivate all other hero sections
        self::where('id', '!=', $this->id)->update(['is_active' => false]);
        
        // Activate this one
        $this->update(['is_active' => true]);
        
        return $this;
    }

    /**
     * Get formatted background images with full URLs
     */
    protected function backgroundImages(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) return [];
                
                $images = is_string($value) ? json_decode($value, true) : $value;
                
                return collect($images)->map(function ($image) {
                    if (is_string($image)) {
                        return [
                            'url' => $this->formatImageUrl($image),
                            'alt' => 'Hero background image'
                        ];
                    }
                    
                    return [
                        'url' => $this->formatImageUrl($image['url'] ?? $image),
                        'alt' => $image['alt'] ?? 'Hero background image'
                    ];
                })->toArray();
            }
        );
    }

    /**
     * Format image URL properly
     */
    private function formatImageUrl($url)
    {
        if (!$url) return '';
        
        // If it's already a full URL, return as is
        if (str_starts_with($url, 'http')) {
            return $url;
        }
        
        // If it starts with /storage/, /uploads/, etc., use asset()
        if (str_starts_with($url, '/')) {
            return asset($url);
        }
        
        // If it's a relative path, prepend with storage or uploads
        if (str_starts_with($url, 'uploads/')) {
            return asset($url);
        }
        
        // Default case
        return asset('uploads/' . ltrim($url, '/'));
    }

    /**
     * Get default text styles
     */
    public function getTextStylesAttribute($value)
    {
        $default = [
            'title' => [
                'fontSize' => '1.9rem',
                'fontWeight' => '700',
                'color' => '#ffffff',
                'marginBottom' => '8px'
            ],
            'title_highlight' => [
                'color' => '#ff7101'
            ],
            'subtitle' => [
                'fontSize' => '1.5rem',
                'fontWeight' => '600',
                'color' => '#21bf6b',
                'marginBottom' => '12px'
            ],
            'tagline' => [
                'fontSize' => '2.2rem',
                'fontWeight' => '800',
                'color' => '#f7b731',
                'lineHeight' => '1.05',
                'marginBottom' => '18px'
            ]
        ];

        if (!$value) return $default;
        
        $styles = is_string($value) ? json_decode($value, true) : $value;
        
        return array_merge($default, $styles);
    }

    /**
     * Get default overlay styles
     */
    public function getOverlayStylesAttribute($value)
    {
        $default = [
            'background' => 'linear-gradient(135deg, rgba(6, 6, 7, 0.8), rgba(0, 30, 60, 0.7))',
            'opacity' => '1'
        ];

        if (!$value) return $default;
        
        $styles = is_string($value) ? json_decode($value, true) : $value;
        
        return array_merge($default, $styles);
    }

    /**
     * Get default section styles
     */
    public function getSectionStylesAttribute($value)
    {
        $default = [
            'minHeight' => '80vh',
            'padding' => '110px 20px 80px'
        ];

        if (!$value) return $default;
        
        $styles = is_string($value) ? json_decode($value, true) : $value;
        
        return array_merge($default, $styles);
    }

    /**
     * Get default CTA button styles
     */
    public function getCtaButtonsAttribute($value)
    {
        $default = [
            [
                'text' => 'Apply now',
                'url' => '#',
                'target' => '_self',
                'type' => 'primary',
                'styles' => [
                    'background' => '#20bf6b',
                    'color' => '#fff',
                    'border' => '2px solid #20bf6b',
                    'borderRadius' => '6px',
                    'padding' => '10px 28px',
                    'fontWeight' => '500'
                ],
                'hover_styles' => [
                    'background' => 'transparent',
                    'color' => '#20bf6b',
                    'transform' => 'translateY(-2px)'
                ]
            ],
            [
                'text' => 'Learn more',
                'url' => '#',
                'target' => '_self',
                'type' => 'secondary',
                'styles' => [
                    'background' => 'transparent',
                    'color' => '#ff7101',
                    'border' => '2px solid #ff7101',
                    'borderRadius' => '6px',
                    'padding' => '10px 28px',
                    'fontWeight' => '500'
                ],
                'hover_styles' => [
                    'background' => '#ff7101',
                    'color' => '#fff',
                    'transform' => 'translateY(-2px)'
                ]
            ]
        ];

        if (!$value) return $default;
        
        return is_string($value) ? json_decode($value, true) : $value;
    }

    /**
     * Get default navigation menu
     */
    public function getNavigationAttribute($value)
    {
        $default = [
            'logo' => [
                'type' => 'image',
                'image_path' => '/public/uploads/image/logo.png',
                'text' => 'Your Brand',
                'url' => '#'
            ],
            'menu_items' => [
                [
                    'title' => 'Home',
                    'url' => '#',
                    'sub_items' => []
                ],
                [
                    'title' => 'About Us',
                    'url' => '#',
                    'sub_items' => ['Our Story', 'Mission & Vision']
                ],
                [
                    'title' => 'Admission',
                    'url' => '#',
                    'sub_items' => ['Apply Now', 'Requirements']
                ],
                [
                    'title' => 'Campus',
                    'url' => '#',
                    'sub_items' => ['Facilities', 'Virtual Tour']
                ],
                [
                    'title' => 'IA Life',
                    'url' => '#',
                    'sub_items' => ['Student Life', 'Events']
                ],
                [
                    'title' => 'Academics',
                    'url' => '#',
                    'sub_items' => ['Curriculum', 'Programs']
                ],
                [
                    'title' => 'Information',
                    'url' => '#',
                    'sub_items' => ['News', 'Resources']
                ],
                [
                    'title' => 'More',
                    'url' => '#',
                    'sub_items' => ['Contact', 'Gallery']
                ]
            ]
        ];

        if (!$value) return $default;
        
        return is_string($value) ? json_decode($value, true) : $value;
    }

    /**
     * Get default advanced settings
     */
    public function getAdvancedSettingsAttribute($value)
    {
        $default = [
            'enable_animations' => true,
            'transition_duration' => '1s',
            'enable_zoom_effect' => true,
            'zoom_duration' => '8s',
            'mobile_responsive' => true,
            'lazy_load_images' => true
        ];

        if (!$value) return $default;
        
        $settings = is_string($value) ? json_decode($value, true) : $value;
        
        return array_merge($default, $settings);
    }

    /**
     * Scope to get only active sections
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get full asset URLs for background gradients
     */
    public function getBackgroundGradientsAttribute($value)
    {
        $default = [
            'linear-gradient(135deg, #ff7101, #102e4a)',
            'linear-gradient(135deg, #20bf6b, #102e4a)',
            'linear-gradient(135deg, #f7b731, #102e4a)',
            'linear-gradient(135deg, #102e4a, #030811)',
            'linear-gradient(135deg, #d35b00, #102e4a)'
        ];

        if (!$value) return $default;
        
        return is_string($value) ? json_decode($value, true) : $value;
    }
}