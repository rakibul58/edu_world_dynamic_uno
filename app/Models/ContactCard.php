<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_section_id',
        'icon',
        'title',
        'content',
        'order',
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
