<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityButton extends Model
{
    use HasFactory;

    protected $fillable = [
        'facilities_section_id',
        'text',
        'url',
        'sort_order',
        'background_color',
        'text_color',
        'hover_background_color',
        'hover_text_color',
        'border_radius',
        'font_size',
        'font_weight',
        'padding',
    ];

    public function facilitiesSection()
    {
        return $this->belongsTo(FacilitiesSection::class);
    }
}
