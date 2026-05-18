<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'category_id', 'name', 'brand', 'slug', 'description', 
        'sizes', 'colors', 'specifications', 'price', 'stock', 'is_active', 'images'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected $casts = [
        'images' => 'array',
        'sizes' => 'array',
        'colors' => 'array',
        'specifications' => 'array',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }
}
