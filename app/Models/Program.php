<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'class_name', 
        'border_color', 'btn_color', 'btn_border_color', 'button_text',
        'order', 'is_active'
    ];
}