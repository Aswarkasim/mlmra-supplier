<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResellerTransactionDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function reseller_transaction() {
        return $this->belongsTo(ResellerTransaction::class);
    }
}
