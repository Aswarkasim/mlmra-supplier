<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function reseller() {
        return $this->belongsTo(Reseller::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
