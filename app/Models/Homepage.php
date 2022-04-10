<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homepage extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content_type', 'status', 'description_1', 'description_2', 'button_text','media_id'];

    public function media() {
        return $this->belongsTo(Media::class);
    }
}
