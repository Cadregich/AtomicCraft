<?php

namespace App\Services\Cabinet;

use App\Models\Gift;
use App\Models\ReadyGifts;
use App\Models\UserDailyGiftStatus;
use Illuminate\Support\Facades\DB;


class DailyGiftService
{
    public function addGiftToTheSendQueueTable($userId, $giftId)
    {
        ReadyGifts::create([
            'user_id' => $userId,
            'gift_id' => $giftId
        ]);
    }
    public function updateUserGiftStatus($userId)
    {
        UserDailyGiftStatus::where('user_id', $userId)->update([
            'award_received' => true,
            'days_received' => DB::raw('days_received + 1')
        ]);
    }

    public function resetDaysReceived($userId):int
    {
        UserDailyGiftStatus::where('user_id', $userId)->update([
            'days_received' => 0
        ]);

        return 0;
    }

    public function getUserGiftStatusAndCount($userId): array
    {
        $entry = UserDailyGiftStatus::firstOrCreate([
            'user_id' => $userId
        ]);
        $maxGifts = Gift::count();

        return [
            'isGiftReceived' => $entry->award_received,
            'daysReceived' => $entry->days_received,
            'giftsCount' => $maxGifts
        ];
    }
}
