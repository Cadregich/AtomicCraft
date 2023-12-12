<?php

namespace App\Console\Commands;

use App\Models\Kit;
use App\Models\Privilege;
use App\Models\Server;
use Illuminate\Console\Command;

class SetupKits extends Command
{
    protected $signature = 'setup:kits {--server=}';

    protected $description = 'Setup kits to DB';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if ($this->option('server') === 'AtomicFragility') {
            $kits = config('kits.AtomicFragility');
            Kit::query()->delete();

            $serverId = Server::select('id')
                ->whereRaw("REPLACE(server_name, ' ', '') = ?", [$this->option('server')])
                ->first()['id'];

            foreach ($kits as $kit => $kitData) {
                Kit::create([
                    'server_id' => $serverId,
                    'command' => $kit,
                    'data' => json_encode($kitData)
                ]);
            }
            $this->info('Privilege settings setup successfully');
        }
    }
}
