<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_section_id',
        'embed_url',
        'width',
        'height',
        'border_radius',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function locationSection()
    {
        return $this->belongsTo(LocationSection::class);
    }
}
