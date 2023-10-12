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
            'gift_id' => $giftId,
        ]);
    }

    public function updateUserGiftStatus($userId, $serverId)
    {
        UserDailyGiftStatus::where('user_id', $userId)
            ->where('server_id', $serverId)->update([
                'award_received' => true,
                'days_received' => DB::raw('days_received + 1')
            ]);
    }

    public function resetDaysReceived($userId, $serverId): int
    {
        UserDailyGiftStatus::where('user_id', $userId)->where('server_id', $serverId)->update([
            'days_received' => 0
        ]);

        return 0;
    }

    public function getUserGiftStatusAndCount($userId, $serverId): array
    {
        $entry = UserDailyGiftStatus::firstOrCreate([
            'user_id' => $userId,
            'server_id' => $serverId
        ]);
        $maxGifts = Gift::where('server_id', $serverId)->count();

        return [
            'isGiftReceived' => $entry->award_received,
            'daysReceived' => $entry->days_received,
            'giftsCount' => $maxGifts
        ];
    }
}
