<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_name',
        'section_key',
        'section_name',
        'description',
        'is_visible',
        'sort_order',
        'settings'
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'settings' => 'array'
    ];

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeForPage($query, $pageName = 'home')
    {
        return $query->where('page_name', $pageName);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
