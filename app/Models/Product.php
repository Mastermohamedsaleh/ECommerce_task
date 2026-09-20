<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   protected $fillable = ['category_id', 'name', 'slug', 'description', 'price', 'stock', 'image'];

  protected $appends = ['average_rating', 'total_reviews'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ? round($this->reviews()->avg('rating'), 1) : 0;
    }

    // حساب إجمالي عدد التقييمات
    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }





}
