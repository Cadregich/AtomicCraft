<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Services\Cabinet\DailyGiftService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class DailyGiftController extends Controller
{
    private $UserDailyGiftStatus;

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

    public function __invoke()
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
            $this->UserDailyGiftStatus->addGiftToTheSendQueueTable($userId, $giftInfo['daysReceived'] + 1);
            $this->UserDailyGiftStatus->updateUserGiftStatus($userId);
            DB::commit();
            return ['status' => 1, 'massage' => 'Gift was sent successfully'];
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
