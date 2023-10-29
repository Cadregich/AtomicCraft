<?php

namespace App\Console\Commands;

use App\Models\UserDailyGiftStatus;
use Exception;
use Illuminate\Console\Command;

class ResetGiftStatus extends Command
{
    protected $signature = 'gift:reset';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            UserDailyGiftStatus::where('award_received', false)
                ->update(['days_received' => 0]);
            UserDailyGiftStatus::where('award_received', true)
                ->update(['award_received' => false]);
        } catch (Exception $e) {
            throw new Exception("Error updating user daily gift status: " . $e->getMessage());
        }
    }
}
