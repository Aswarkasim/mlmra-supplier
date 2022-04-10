<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'name', 'category_type','status','media_id'];

    public function media() {
        return $this->belongsTo(Media::class);
    }

    public function subcategory() {
        return $this->hasMany(SubCategory::class);
    }
}
