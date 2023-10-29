<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\DailyGift;
use App\Models\Server;
use App\Models\UserDailyGiftStatus;
use App\Services\Cabinet\DailyGiftService;
use Exception;
use Illuminate\Http\Request;
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

    public function getDailyGift(Request $request)
    {
        $serverId = Server::where('server_name', $request->input('server'))->value('id');
        $userId = session('user')['id'];
        $giftInfo = $this->UserDailyGiftStatus->getUserGiftStatusAndCount($userId, $serverId);

        if ($giftInfo['isGiftReceived']) {
            return ['status' => 0, 'massage' => 'Gift already received'];
        }

        if ($giftInfo['daysReceived'] === $giftInfo['giftsCount']) {
            $giftInfo['daysReceived'] = $this->UserDailyGiftStatus->resetDaysReceived($userId, $serverId);
        }

        DB::beginTransaction();
        try {
            $nextGiftId = $this->getNextGiftData($giftInfo['daysReceived'], $serverId)->id;
            $this->UserDailyGiftStatus->addGiftToTheSendQueueTable($userId, $nextGiftId);
            $this->UserDailyGiftStatus->updateUserGiftStatus($userId, $serverId);
            DB::commit();
            return ['status' => 1, 'massage' => 'Gift was sent successfully'];
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getDailyGiftData(Request $request) {
        $serverId = Server::where('server_name', $request->input('server'))->value('id');
        $userId = session('user')['id'];
        $giftData = UserDailyGiftStatus::firstOrNew(['user_id' => $userId, 'server_id' => $serverId]);
        if (!is_int($giftData->days_received) || !is_int($giftData->award_received)) {
            $giftData['award_received'] = 0;
            $giftData['days_received'] = 0;
        }
        $giftData->save();
        $nextGift = $this->getNextGiftData($giftData['days_received'], $serverId);

        if (!$nextGift) {
            $this->resetDaysReceived($userId, $serverId);
            $nextGift = $this->getNextGiftData(0, $serverId);
        }

        $giftData['nextGift'] = $nextGift;

        $giftData['status'] = !$giftData['award_received'] ? 1 : 0;

        return $giftData;
    }



    private function getNextGiftData($daysReceived, $serverId) {
        return DailyGift::where('server_id', $serverId)
            ->select(['id', 'title', 'count'])->skip($daysReceived)->take(1)->first();
    }

    private function resetDaysReceived($userId, $serverId) {
        UserDailyGiftStatus::where('user_id', $userId)->where('server_id', $serverId)->update([
            'days_received' => 0
        ]);
    }
}
