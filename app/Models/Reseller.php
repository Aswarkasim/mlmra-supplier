<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Reseller extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasFactory;

    protected $fillable = [
        'full_name',
        'username',
        'email',
        'phone_number',
        'whatsapp',
        'point',
        'commision_total',
        'status',
        'password',
        'birth_date',
        'gender',
        'job',
        'level',
        'referal_code',
        'referal_count',
        'referal_bonus',
        'code',
        'description',
        'province_id',
        'city_id',
        'district'
    ];

    protected $hidden = [
        'password',
    ];


    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
