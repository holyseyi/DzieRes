<?php
/**
 * Application Configuration
 * Restaurant Management System
 */

return [
    'name' => 'DzieRes Restaurant',
    'version' => '1.0.0',
    'debug' => true,
    'url' => 'http://localhost/restaurant',
    'timezone' => 'Africa/Accra',
    'locale' => 'en',
    'charset' => 'UTF-8',
    
    // Session
    'session' => [
        'lifetime' => 7200,
        'name' => 'DRES_SESSION',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax',
    ],
    
    // Security
    'security' => [
        'hash_cost' => 12,
        'csrf_expiry' => 3600,
        'rate_limit' => 60, // requests per minute
        'max_login_attempts' => 5,
        'lockout_time' => 900, // 15 minutes
    ],
    
    // Pagination
    'pagination' => [
        'per_page' => 12,
        'admin_per_page' => 20,
    ],
    
    // Upload
    'upload' => [
        'max_size' => 5242880, // 5MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
        'path' => __DIR__ . '/../uploads',
    ],
    
    // Tax & Currency
    'tax' => [
        'rate' => 12.5,
        'name' => 'VAT',
    ],
    'currency' => [
        'symbol' => '₵',
        'code' => 'GHS',
        'name' => 'Ghana Cedi',
    ],
    'delivery' => [
        'fee' => 15.00,
        'free_above' => 100.00,
        'radius_km' => 20,
    ],
    'service_charge' => 5.00,
    
    // Loyalty
    'loyalty' => [
        'points_per_ghs' => 10,
        'ghs_per_point' => 0.10,
        'welcome_points' => 100,
    ],
    
    // Email (placeholder)
    'email' => [
        'from' => 'noreply@dzieres.com',
        'name' => 'DzieRes Restaurant',
    ],
    
    // Restaurant Info
    'restaurant' => [
        'name' => 'DzieRes',
        'tagline' => 'Where Every Meal Tells a Story',
        'phone' => '+233 50 000 0000',
        'email' => 'info@dzieres.com',
        'address' => '123 Independence Avenue, Accra, Ghana',
        'opening_hours' => 'Mon-Sun: 7:00 AM - 11:00 PM',
    ],
];