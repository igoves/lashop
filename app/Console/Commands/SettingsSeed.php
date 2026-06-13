<?php

namespace App\Console\Commands;

use Database\Seeders\SettingsSeeder;
use Illuminate\Console\Command;

class SettingsSeed extends Command
{
    protected $signature = 'settings:seed';

    protected $description = 'Seed default settings into the database';

    public function handle(): int
    {
        $this->call(SettingsSeeder::class);

        $this->info('Settings seeded successfully.');

        return self::SUCCESS;
    }
}
