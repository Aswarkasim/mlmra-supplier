<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = [];
    use HasFactory;

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function product_reseller() {
        return $this->belongsTo(ProductReseller::class);
    }
}
