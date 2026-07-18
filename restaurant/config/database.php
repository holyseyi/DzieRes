<?php
/**
 * Database Configuration
 * Restaurant Management System
 */

// Database file path: overridable via the DZ_DB_PATH environment variable
// (useful on read-only/ephemeral hosts like Wasmer where the app directory
// is not writable). Falls back to the local database/ directory.
$dbFile = getenv('DZ_DB_PATH')
    ?: (__DIR__ . '/../database/restaurant.db');

return [
    'driver' => 'sqlite',
    'database' => $dbFile,
    'schema' => __DIR__ . '/../database/schema.sql',
    'seeder' => __DIR__ . '/../database/seeder.php',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_STRINGIFY_FETCHES => false,
    ]
];