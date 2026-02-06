<?php

require_once __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check if column exists and add it if it doesn't
if (!Schema::hasColumn('addresses', 'is_default')) {
    Schema::table('addresses', function (Blueprint $table) {
        $table->boolean('is_default')->default(false)->after('additional_info');
    });
    
    echo "Column 'is_default' added to 'addresses' table successfully.\n";
} else {
    echo "Column 'is_default' already exists in 'addresses' table.\n";
}

echo "Migration completed.\n";