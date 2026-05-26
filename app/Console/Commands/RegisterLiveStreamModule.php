<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RegisterLiveStreamModule extends Command
{
    protected $signature = 'livestream:register';
    protected $description = 'Register and enable the LiveStream module in the database';

    public function handle()
    {
        try {
            // Check if modules table exists
            $tableExists = DB::select("SHOW TABLES LIKE 'modules'");

            if (empty($tableExists)) {
                $this->error("'modules' table does not exist in DB.");
                return 1;
            }

            // Check if LiveStream already exists
            $existing = DB::table('modules')->where('name', 'LiveStream')->first();

            if ($existing) {
                DB::table('modules')->where('name', 'LiveStream')->update(['enabled' => 1]);
                $this->info('LiveStream module was already registered — it has been enabled!');
            } else {
                DB::table('modules')->insert(['name' => 'LiveStream', 'enabled' => 1]);
                $this->info('LiveStream module has been registered and enabled successfully!');
            }

            // Also clear the module cache
            \Artisan::call('cache:clear');
            $this->info('Cache cleared.');

            return 0;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
