<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDailyGiftStatus extends Model
{
    use HasFactory;

    protected $table = 'users_daily_gift_status';
    protected $fillable = [
        'user_id',
        'award_received',
        'days_received'
    ];
}
