<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo_path',
        'description',
        'tagline',
        'social_links',
        'quick_links',
        'programs',
        'address',
        'phone',
        'email',
        'working_hours',
        'background_color',
        'text_color',
        'accent_color',
        'link_color',
        'link_hover_color',
        'is_active'
    ];

    protected $casts = [
        'social_links' => 'array',
        'quick_links' => 'array',
        'programs' => 'array',
        'is_active' => 'boolean'
    ];

    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }

    public function activate()
    {
        // Deactivate all other footer sections
        self::where('id', '!=', $this->id)->update(['is_active' => false]);

        // Activate this footer section
        $this->update(['is_active' => true]);
    }
}
