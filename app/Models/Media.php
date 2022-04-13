<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['file_name', 'code', 'file_size', 'media_type', 'category_type', 'path'];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
    use HasFactory;
}
