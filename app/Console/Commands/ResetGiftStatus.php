<?php

namespace App\Console\Commands;

use App\Models\UserDailyGiftStatus;
use Exception;
use Illuminate\Console\Command;

class ResetGiftStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gift:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
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
