<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResellerShop extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reseller() {
        return $this->belongsTo(Reseller::class);
    }
}
