<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{
    protected $guarded = [];
    use HasFactory;

    public function reseller_transaction() {
        return $this->belongsTo(ResellerTransaction::class);
    }

    public function reseller() {
        return $this->belongsTo(Reseller::class);
    }
}
