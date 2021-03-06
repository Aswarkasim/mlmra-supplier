<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResellerTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reseller()
    {
        return $this->belongsTo(Reseller::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function bank_account()
    {
        return $this->belongsTo(BankAccount::class);
    }

    function mediaCode()
    {
        return $this->hasMany(Media::class, 'code', 'media_code');
    }
    function kategori()
    {
        return $this->belongsTo(Kategori::class, 'category_id');
    }

    function product()
    {
        return $this->hasMany(Product::class)->with('mediaCode');
    }

    public function reseller_transaction_detail()
    {
        return $this->hasMany(ResellerTransactionDetail::class)->with(['product', 'product.mediaCode']);
    }
}
