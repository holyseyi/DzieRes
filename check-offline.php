<?php
/**
 * Offline Readiness Check
 *
 * Run this from the project root: php restaurant/check-offline.php
 */

$errors = [];
$warnings = [];

// Check vendor assets
$vendorFiles = [
    'restaurant/assets/vendor/bootstrap/bootstrap.min.css',
    'restaurant/assets/vendor/bootstrap/bootstrap.bundle.min.js',
    'restaurant/assets/vendor/fontawesome/all.min.css',
    'restaurant/assets/vendor/fontawesome/webfonts/fa-solid-900.woff2',
    'restaurant/assets/vendor/aos/aos.css',
    'restaurant/assets/vendor/aos/aos.js',
    'restaurant/assets/vendor/chart.js/chart.min.js',
    'restaurant/assets/vendor/fonts/local-fonts.css',
    'restaurant/assets/vendor/fonts/inter/400.ttf',
    'restaurant/assets/vendor/fonts/playfairdisplay/400.ttf',
    'restaurant/assets/vendor/fonts/dancingscript/400.ttf',
];

foreach ($vendorFiles as $file) {
    if (!file_exists(__DIR__ . '/' . $file)) {
        $errors[] = "Missing vendor asset: $file";
    }
}

// Check local image placeholders
$placeholders = [
    'restaurant/assets/images/placeholders/auth-bg.svg',
    'restaurant/assets/images/placeholders/gallery-1.svg',
    'restaurant/assets/images/placeholders/chef.jpg',
    'restaurant/assets/images/placeholders/about.jpg',
    'restaurant/assets/images/placeholders/map-placeholder.svg',
    'restaurant/assets/images/placeholders/testimonial-avatar.svg',
    'restaurant/assets/images/placeholders/featured-dish.svg',
];

foreach ($placeholders as $img) {
    if (!file_exists(__DIR__ . '/' . $img)) {
        $errors[] = "Missing placeholder: $img";
    }
}

// Check writable directories
$writableDirs = [
    'restaurant/database',
    'restaurant/uploads',
    'restaurant/logs',
    '/tmp/dzieres',
];

foreach ($writableDirs as $dir) {
    $path = ($dir[0] === '/') ? $dir : __DIR__ . '/' . $dir;
    if (!is_dir($path)) {
        $warnings[] = "Directory missing (will be created at runtime): $dir";
    } elseif (!is_writable($path)) {
        $errors[] = "Directory not writable: $dir";
    }
}

// Check PHP extensions
$requiredExtensions = ['pdo', 'pdo_sqlite', 'sqlite3', 'session', 'gd'];
foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $errors[] = "Missing PHP extension: $ext";
    }
}

// Check server.php exists
if (!file_exists(__DIR__ . '/restaurant/server.php')) {
    $errors[] = "Missing restaurant/server.php (built-in server router)";
}

echo "=== DzieRes Offline Readiness Check ===\n\n";

if (empty($errors)) {
    echo "✅ All checks passed. The app is ready to run offline.\n\n";
} else {
    echo "❌ Errors:\n";
    foreach ($errors as $e) {
        echo "  - $e\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "⚠️  Warnings:\n";
    foreach ($warnings as $w) {
        echo "  - $w\n";
    }
    echo "\n";
}

if (empty($errors)) {
    echo "To start the app locally (no internet required):\n";
    echo "  cd restaurant && php -S localhost:8000 server.php\n";
    echo "  OR\n";
    echo "  bash start-local.sh\n";
    echo "\nThen open http://localhost:8000 in your browser.\n";
    echo "\nDefault accounts:\n";
    echo "  Admin:    admin@dzieres.com / admin123\n";
    echo "  Staff:    staff@dzieres.com / staff123\n";
    echo "  Customer: customer@dzieres.com / customer123\n";
}

exit(empty($errors) ? 0 : 1);
