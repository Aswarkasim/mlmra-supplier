<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commision extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }

    public function reseller() {
        return $this->belongsTo(Reseller::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
