<?php
// Simple script to register the LiveStream module in the database
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// Check what modules table looks like
try {
    $tables = DB::select("SHOW TABLES LIKE 'modules'");
    if (empty($tables)) {
        echo "ERROR: 'modules' table does not exist!\n";
        
        // Try to find the correct table name
        $allTables = DB::select("SHOW TABLES");
        echo "Available tables:\n";
        foreach ($allTables as $t) {
            $vals = (array) $t;
            echo " - " . array_values($vals)[0] . "\n";
        }
    } else {
        echo "Found 'modules' table.\n";
        
        // Check columns
        $cols = DB::select("DESCRIBE modules");
        echo "Columns: " . implode(', ', array_column($cols, 'Field')) . "\n";
        
        // Check existing entries
        $existing = DB::table('modules')->get();
        echo "Existing entries:\n";
        foreach ($existing as $e) {
            echo " - " . json_encode($e) . "\n";
        }
        
        // Check if LiveStream already exists
        $exists = DB::table('modules')->where('name', 'LiveStream')->first();
        if ($exists) {
            echo "LiveStream already exists! Enabling it...<br>";
            DB::table('modules')->where('name', 'LiveStream')->update(['enabled' => 1]);
        } else {
            echo "Inserting LiveStream...<br>";
            DB::table('modules')->insert(['name' => 'LiveStream', 'enabled' => 1]);
        }
        
        // Clear cache
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        echo "Cache cleared!<br>";
        
        echo "<b>SUCCESS: LiveStream is now registered and enabled!</b><br>";
    }
} catch (\Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
