<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResellerCoupon extends Model
{
    protected $guarded = [];
    use HasFactory;

    public function coupon() {
        return $this->belongsTo(Coupon::class);
    }

    public function reseller() {
        return $this->belongsTo(Reseller::class);
    }
}
