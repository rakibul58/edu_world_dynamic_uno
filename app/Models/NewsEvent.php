<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'slug',
        'icon',
        'background_gradient_start',
        'background_gradient_end',
        'featured_image',
        'event_date',
        'type',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'event_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($newsEvent) {
            if (!$newsEvent->slug) {
                $newsEvent->slug = Str::slug($newsEvent->title);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
