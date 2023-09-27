<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\DailyGift;
use App\Models\UserDailyGiftStatus;
use App\Services\Cabinet\DailyGiftService;
use Exception;
use Illuminate\Support\Facades\DB;

class DailyGiftController extends Controller
{
    private DailyGiftService $UserDailyGiftStatus;

    public function __construct(DailyGiftService $UserDailyGiftStatus)
    {
        $this->UserDailyGiftStatus = $UserDailyGiftStatus;
    }

    /**
     * Gets information about the daily gift status for the user.
     * If the gift for today has not been received,
     * fills the "ready_gifts" table with the user and gift IDs,
     * updates the status of the "users_daily_gift_status" table,
     * and resets the count of days the gift has been received.
     *
     * @throws Exception
     */

    public function getDailyGift()
    {
        $userId = session('user')['id'];
        $giftInfo = $this->UserDailyGiftStatus->getUserGiftStatusAndCount($userId);

        if ($giftInfo['isGiftReceived']) {
            return ['status' => 0, 'massage' => 'Gift already received'];
        }

        if ($giftInfo['daysReceived'] === $giftInfo['giftsCount']) {
            $giftInfo['daysReceived'] = $this->UserDailyGiftStatus->resetDaysReceived($userId);
        }

        DB::beginTransaction();
        try {
            $nextGiftId = $this->getNextGiftData($giftInfo['daysReceived'])->id;
            $this->UserDailyGiftStatus->addGiftToTheSendQueueTable($userId, $nextGiftId);
            $this->UserDailyGiftStatus->updateUserGiftStatus($userId);
            DB::commit();
            return ['status' => 1, 'massage' => 'Gift was sent successfully'];
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getDailyGiftData() {
        $userId = session('user')['id'];
        $giftData = UserDailyGiftStatus::select(['award_received', 'days_received'])
            ->where('user_id', $userId)->first();
        $nextGift = $this->getNextGiftData($giftData['days_received']);

        if (!$nextGift) {
            $this->resetDaysReceived($userId);
            $nextGift = $this->getNextGiftData(0);
        }
        $giftData['nextGift'] = $nextGift;

        $giftData['status'] = !$giftData['award_received'] ? 1 : 0;

        return $giftData;
    }

    private function getNextGiftData($daysReceived) {
        return DailyGift::select(['id', 'title', 'count'])->skip($daysReceived)->take(1)->first();
    }

    private function resetDaysReceived($userId) {
        UserDailyGiftStatus::where('user_id', $userId)->update([
            'days_received' => 0
        ]);
    }
}
