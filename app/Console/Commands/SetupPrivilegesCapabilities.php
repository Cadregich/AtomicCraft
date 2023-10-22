<?php

namespace App\Console\Commands;

use App\Models\Privilege;
use App\Models\Server;
use Illuminate\Console\Command;

class SetupPrivilegesCapabilities extends Command
{
    protected $signature = 'privilege:setup {--server=}';

    protected $description = 'Set privilege capabilities to DB';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if ($this->option('server') === 'AtomicFragility') {
            $privileges = config('privileges.AtomicFragility');

            Privilege::query()->delete();

            $serverId = Server::select('id')
                ->whereRaw("REPLACE(server_name, ' ', '') = ?", [$this->option('server')])
                ->first()['id'];

            foreach ($privileges as $privilegeName => $privilegeData) {
                Privilege::create([
                    'server_id' => $serverId,
                    'title' => $privilegeName,
                    'capabilities' => json_encode($privilegeData)
                ]);
            }
            $this->info('Privilege settings setup successfully');
        }
    }
}
