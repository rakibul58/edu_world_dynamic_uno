<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'background_color',
        'padding_top',
        'padding_bottom',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function contactCards()
    {
        return $this->hasMany(ContactCard::class)->orderBy('order');
    }

    public function locationMap()
    {
        return $this->hasOne(LocationMap::class);
    }

    public function sectionStyles()
    {
        return $this->hasMany(SectionStyle::class);
    }
}
