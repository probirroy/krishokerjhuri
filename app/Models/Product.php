<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected function casts(): array
    {
        return [
            'image_urls' => 'array',
        ];
    }

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
        'image_urls',
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

    public function galleryImages(): array
    {
        return array_values(array_unique(array_filter(
            array_merge([$this->image_url], $this->image_urls ?? []),
            static fn ($image): bool => is_string($image) && $image !== '',
        )));
    }
}
