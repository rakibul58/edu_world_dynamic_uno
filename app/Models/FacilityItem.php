<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'facilities_section_id',
        'title',
        'description',
        'icon',
        'sort_order',
        'background_color',
        'title_color',
        'description_color',
        'title_font_size',
        'description_font_size',
    ];

    public function facilitiesSection()
    {
        return $this->belongsTo(FacilitiesSection::class);
    }
}
