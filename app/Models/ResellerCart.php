<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResellerCart extends Model
{
    protected $guarded = [];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class)->with('address');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    // public function address()
    // {
    //     return $this->belongsTo(User::class);
    // }
    // function relation from product and media with parameter media code

    public function reseller()
    {
        return $this->belongsTo(Reseller::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->with(['user', 'user.address', 'mediaCode', 'category', 'subcategory']);
        // return $this->belongsTo(Product::class);
    }
}
