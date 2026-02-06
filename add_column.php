<?php
// Simple script to add the is_default column directly to the database

// Load the environment variables
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Setup Eloquent ORM
$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'port'      => $_ENV['DB_PORT'] ?? '3306',
    'database'  => $_ENV['DB_DATABASE'] ?? 'kc_musical_store_db',
    'username'  => $_ENV['DB_USERNAME'] ?? 'root',
    'password'  => $_ENV['DB_PASSWORD'] ?? '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    // Check if the column exists
    $result = Capsule::select("SHOW COLUMNS FROM addresses LIKE 'is_default'");
    
    if (empty($result)) {
        // Column doesn't exist, add it
        Capsule::statement("ALTER TABLE addresses ADD COLUMN is_default BOOLEAN DEFAULT FALSE");
        echo "Column 'is_default' added successfully.\n";
    } else {
        echo "Column 'is_default' already exists.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}