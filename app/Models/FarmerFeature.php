<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmerFeature extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image_url',
        'description',
        'cta_label',
        'cta_url',
        'is_active',
    ];
}
