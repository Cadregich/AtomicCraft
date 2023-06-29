<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadyGifts extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gift_id'
    ];
}
