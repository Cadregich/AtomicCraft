<?php

namespace Database\Seeders;

use App\Models\Mod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mod::factory()->count(15)->create();
    }
}
