<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referal extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reseller() {
        return $this->belongsTo(Reseller::class);
    }
}
