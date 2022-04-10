<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function media() {
        return $this->belongsTo(Media::class);
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }

    public function resellerTransaction() {
        return $this->belongsTo(ResellerTransaction::class);
    }
}
