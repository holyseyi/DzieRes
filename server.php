<?php
/**
 * PHP Built-in Server Router
 * 
 * This file acts as a router for the PHP built-in server (used by Wasmer).
 * The built-in server does NOT support .htaccess files, so we need this
 * to forward all non-file requests to index.php (the front controller).
 */

// If the requested file exists, is not a directory, and is not a PHP
// script, serve it directly as a static asset. Everything else (including
// missing files and any .php path) is forwarded to index.php.
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestedFile = __DIR__ . $uri;

if ($uri !== '/'
    && file_exists($requestedFile)
    && !is_dir($requestedFile)
    && strtolower(pathinfo($requestedFile, PATHINFO_EXTENSION)) !== 'php'
) {
    return false; // built-in server serves the static file
}

// Forward all other requests to the front controller
error_log("SERVER: forwarding $uri, SCRIPT_NAME=" . ($_SERVER['SCRIPT_NAME'] ?? ''));
require __DIR__ . '/index.php';
error_log("SERVER: after require, SCRIPT_NAME=" . ($_SERVER['SCRIPT_NAME'] ?? ''));