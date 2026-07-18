<?php
/**
 * Database Configuration
 * Restaurant Management System
 */

return [
    'driver' => 'sqlite',
    'database' => __DIR__ . '/../database/restaurant.db',
    'schema' => __DIR__ . '/../database/schema.sql',
    'seeder' => __DIR__ . '/../database/seeder.php',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_STRINGIFY_FETCHES => false,
    ]
];