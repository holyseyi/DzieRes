<?php
/**
 * PHP Built-in Server Router
 * 
 * This file acts as a router for the PHP built-in server (used by Wasmer).
 * The built-in server does NOT support .htaccess files, so we need this
 * to forward all non-file requests to index.php (the front controller).
 */

// If the requested file exists and is not a PHP file, serve it directly
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestedFile = __DIR__ . $uri;

// Serve existing static files directly
if ($uri !== '/' && file_exists($requestedFile) && !is_dir($requestedFile)) {
    return false;
}

// Forward all other requests to the front controller
require __DIR__ . '/index.php';