<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionStyle extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_section_id',
        'element_class',
        'styles'
    ];

    protected $casts = [
        'styles' => 'array',
    ];

    public function locationSection()
    {
        return $this->belongsTo(LocationSection::class);
    }
}
