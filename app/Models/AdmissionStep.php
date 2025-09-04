<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_process_id',
        'title',
        'description',
        'step_number',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function admissionProcess()
    {
        return $this->belongsTo(AdmissionProcess::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
