<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NewsEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        'description',
        'link',
        'card_background_type',
        'icon',
        'image_url',
        'gradient_start',
        'gradient_end',
        'gradient_direction',
        'title_color',
        'title_font_size',
        'title_font_weight',
        'description_color',
        'description_font_size',
        'description_font_weight',
        'date_color',
        'date_font_size',
        'date_font_weight',
        'link_color',
        'link_hover_color',
        'link_font_weight',
        'card_border_radius',
        'card_shadow',
        'card_hover_shadow',
        'card_hover_transform',
        'card_background_color',
        'is_active',
        'order'
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->date->format('F j, Y');
    }

    public function getGradientStyleAttribute()
    {
        return "linear-gradient({$this->gradient_direction}, {$this->gradient_start}, {$this->gradient_end})";
    }

    public function getCardImageStyleAttribute()
    {
        if ($this->card_background_type === 'gradient') {
            return [
                'background' => $this->gradient_style,
                'color' => '#fff'
            ];
        } else {
            return [
                'background-image' => $this->image_url ? "url('" . asset($this->image_url) . "')" : 'none',
                'background-size' => 'cover',
                'background-position' => 'center',
                'color' => '#fff'
            ];
        }
    }

    // Handle file upload
    public function handleImageUpload($file)
    {
        if ($file) {
            // Delete old image if exists
            if ($this->image_url && file_exists(public_path($this->image_url))) {
                unlink(public_path($this->image_url));
            }

            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Ensure directory exists
            $uploadPath = public_path('uploads/image');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Move file
            $file->move($uploadPath, $filename);

            return 'uploads/image/' . $filename;
        }

        return null;
    }

    // Delete image when model is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($newsEvent) {
            if ($newsEvent->image_url && file_exists(public_path($newsEvent->image_url))) {
                unlink(public_path($newsEvent->image_url));
            }
        });
    }

    // Auto-increment order on creation
    protected static function booted()
    {
        static::creating(function ($newsEvent) {
            if (is_null($newsEvent->order)) {
                $newsEvent->order = static::max('order') + 1;
            }
        });
    }
}
