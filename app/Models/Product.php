<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function subcategory() {
        return $this->belongsTo(SubCategory::class);
    }

    public function productvarians() {
        return $this->hasMany(ProductVarian::class);
    }

    public function comment() {
        return $this->hasMany(Comment::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

}
