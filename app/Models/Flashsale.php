<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flashsale extends Model
{
    use HasFactory;

    protected $guarded = [];


    function mediaCode()
    {
        return $this->hasMany(Media::class, 'code', 'media_code');
    }
    function product()
    {
        return $this->belongsTo(Product::class)->with('mediaCode');
    }
}
