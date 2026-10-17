<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'barcode',
        'unit',
        'price',
        'compare_price',
        'image_url',
        'badge',
        'short_description',
        'description',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'stock',
        'is_featured',
        'is_active',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
