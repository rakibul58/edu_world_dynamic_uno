<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'about_section_id',
        'value',
        'label',
        'number_color',
        'label_color',
        'number_font_size',
        'label_font_size',
        'sort_order'
    ];

    public function aboutSection()
    {
        return $this->belongsTo(AboutSection::class);
    }
}
