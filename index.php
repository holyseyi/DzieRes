<?php
/**
 * Restaurant Management System - Entry Point
 * Version 1.0.0
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', (getenv('APP_DEBUG') ?: '1') === '0' ? '0' : '1');

// Use a writable temp dir for sessions/uploads and SQLite WAL siblings.
// Many PaaS runtimes (Wasmer) ship a read-only default session dir, which
// makes session_start() fatal with a 500. Point it at the system temp dir.
$writableTmp = rtrim(sys_get_temp_dir(), '/') . '/dzieres';
if (!is_dir($writableTmp)) {
    @mkdir($writableTmp, 0755, true);
}
if (is_dir($writableTmp) && is_writable($writableTmp)) {
    if (empty(session_save_path()) || !is_writable(session_save_path())) {
        session_save_path($writableTmp);
    }
    if (empty(ini_get('upload_tmp_dir')) || !is_writable(ini_get('upload_tmp_dir'))) {
        ini_set('upload_tmp_dir', $writableTmp);
    }
}

// Surface real errors instead of a blank 500 so failures are diagnosable.
$errorLog = __DIR__ . '/logs/error.log';
set_exception_handler(function (\Throwable $e) use ($errorLog): void {
    $msg = date('Y-m-d H:i:s') . ' ' . get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() . "\n";
    @file_put_contents($errorLog, $msg, FILE_APPEND);
    
    http_response_code(500);
    echo '<pre style="padding:20px;font:13px monospace;white-space:pre-wrap">'
        . '500 Internal Server Error' . "\n\n"
        . get_class($e) . ': ' . $e->getMessage() . "\n"
        . 'in ' . $e->getFile() . ':' . $e->getLine() . "\n\n"
        . $e->getTraceAsString() . '</pre>';
    exit;
});

// Timezone
date_default_timezone_set('Africa/Accra');

// Start session
session_start();

// Autoload
spl_autoload_register(function ($class) {
    $map = [
        'Config\\'      => 'config/',
        'Controllers\\' => 'Controllers/',
        'Models\\'      => 'Models/',
        'Middleware\\'  => 'middleware/',
        'Api\\'         => 'api/',
    ];
    $baseDir = __DIR__ . '/';

    foreach ($map as $prefix => $dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relativeClass = substr($class, $len);
            $file = $baseDir . $dir . str_replace('\\', '/', $relativeClass) . '.php';

            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
});

// Load helpers
require_once __DIR__ . '/helpers/functions.php';

// Load Router
require_once __DIR__ . '/Router.php';

// Initialize database
try {
    $db = \Config\Database::getInstance();
    
    // Run schema if tables don't exist
    if (!$db->tableExists('users')) {
        $schema = file_get_contents(__DIR__ . '/database/schema.sql');
        if ($schema === false) {
            throw new \RuntimeException('Failed to read database schema file');
        }
        $db->getConnection()->exec($schema);
    }
    
    // Ensure default users exist so login/admin works even if the database
    // is recreated or wiped (common on ephemeral/read-only PaaS like Wasmer).
    $userCount = (int)$db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($userCount === 0) {
        $users = [
            ['Admin', 'User', 'admin@dzieres.com', '+233 50 000 0001', password_hash('admin123', PASSWORD_DEFAULT), 1],
            ['Staff', 'User', 'staff@dzieres.com', '+233 50 000 0002', password_hash('staff123', PASSWORD_DEFAULT), 2],
            ['Test', 'Customer', 'customer@dzieres.com', '+233 50 000 0003', password_hash('customer123', PASSWORD_DEFAULT), 3],
        ];
        $stmt = $db->getConnection()->prepare('INSERT OR IGNORE INTO users (firstname, lastname, email, phone, password, role_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
        foreach ($users as $u) {
            $stmt->execute([...$u, 'active']);
        }
    }
    
    // Ensure default categories exist if foods were seeded but categories were not.
    $catCount = (int)$db->query('SELECT COUNT(*) FROM categories')->fetchColumn();
    if ($catCount === 0) {
        $categories = [
            ['Breakfast', 'breakfast'], ['Lunch', 'lunch'], ['Dinner', 'dinner'],
            ['Sides', 'sides'], ['Desserts', 'desserts'], ['Drinks', 'drinks'],
        ];
        $stmt = $db->getConnection()->prepare('INSERT OR IGNORE INTO categories (name, slug, sort_order) VALUES (?, ?, ?)');
        foreach ($categories as $i => $c) {
            $stmt->execute([...$c, $i]);
        }
    }
    
    // Ensure sample foods exist so the menu/cart/checkout flows work on a fresh
    // database (common on read-only/Ephemeral PaaS like Wasmer).
    $foodCount = (int)$db->query('SELECT COUNT(*) FROM foods')->fetchColumn();
    if ($foodCount === 0) {
        $foods = [
            ['Koko With Koose', 'koko-with-koose', 'Delicious koko with koose.', 25, 320, 10],
            ['Waakye', 'waakye', 'Classic waakye with all the fixings.', 35, 450, 20],
            ['Jollof Rice', 'jollof-rice', 'Signature jollof rice with chicken.', 40, 520, 25],
            ['Fufu', 'fufu', 'Fresh fufu with light soup.', 30, 380, 15],
            ['Banku And Tilapia', 'banku-and-tilapia', 'Grilled tilapia with banku.', 60, 650, 30],
            ['Red Red', 'red-red', 'Traditional red red with plantain.', 28, 400, 15],
            ['Kelewele', 'kelewele', 'Spiced fried plantain.', 15, 250, 10],
            ['Peanut Soup', 'peanut-soup', 'Rich peanut soup with assorted meat.', 35, 420, 20],
        ];
        $catId = (int)$db->query("SELECT id FROM categories WHERE slug='breakfast'")->fetchColumn();
        if (!$catId) {
            $catId = (int)$db->query('SELECT MIN(id) FROM categories')->fetchColumn();
        }
        $stmt = $db->getConnection()->prepare('INSERT INTO foods (category_id, name, slug, description, price, final_price, calories, preparation_time, spice_level, availability, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        foreach ($foods as $f) {
            $stmt->execute([$catId, $f[0], $f[1], $f[2], $f[3], $f[3], $f[4], $f[5], 'mild', 'available', 'active']);
        }
    }
} catch (Exception $e) {
    $msg = 'Database initialization failed: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() . "\n";
    @file_put_contents($errorLog, date('Y-m-d H:i:s') . ' ' . $msg, FILE_APPEND);
    die('Database initialization failed: ' . $e->getMessage());
}

// Create Router instance
$router = new Router();

// ============================================
// FRONTEND ROUTES
// ============================================

// Home
$router->get('/', 'HomeController@index');
$router->get('/home', 'HomeController@index');

// About
$router->get('/about', 'PageController@about');
$router->get('/our-story', 'PageController@ourStory');
$router->get('/our-chef', 'PageController@ourChef');

// Menu
$router->get('/menu', 'MenuController@index');
$router->get('/menu/category/{slug}', 'MenuController@category');
$router->get('/menu/{slug}', 'MenuController@show');
$router->get('/api/menu/search', 'MenuController@search');
$router->get('/api/menu/filter', 'MenuController@filter');

// Cart
$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/update', 'CartController@update');
$router->post('/cart/remove', 'CartController@remove');
$router->post('/cart/apply-coupon', 'CartController@applyCoupon');
$router->get('/api/cart/count', 'CartController@getCount');

// Checkout
$router->get('/checkout', 'OrderController@checkout');
$router->post('/checkout/place', 'OrderController@placeOrder');
$router->get('/order/confirmation/{id}', 'OrderController@confirmation');
$router->get('/order/track/{number}', 'OrderController@track');

// Reservations
$router->get('/reservations', 'ReservationController@index');
$router->post('/reservations/book', 'ReservationController@book');
$router->get('/reservations/confirm/{number}', 'ReservationController@confirm');
$router->get('/api/tables/available', 'ReservationController@availableTables');

// Gallery
$router->get('/gallery', 'GalleryController@index');

// Testimonials
$router->get('/testimonials', 'TestimonialController@index');
$router->post('/testimonials/submit', 'TestimonialController@submit');

// Events
$router->get('/events', 'EventController@index');
$router->get('/events/{slug}', 'EventController@show');
$router->post('/events/book', 'EventController@book');

// Blog
$router->get('/blog', 'BlogController@index');
$router->get('/blog/{slug}', 'BlogController@show');
$router->get('/blog/category/{slug}', 'BlogController@category');

// Careers
$router->get('/careers', 'CareerController@index');
$router->get('/careers/{slug}', 'CareerController@show');
$router->post('/careers/apply', 'CareerController@apply');

// Contact
$router->get('/contact', 'ContactController@index');
$router->post('/contact/send', 'ContactController@send');
$router->post('/newsletter/subscribe', 'ContactController@subscribe');

// Static Pages
$router->get('/faqs', 'PageController@faqs');
$router->get('/privacy-policy', 'PageController@privacy');
$router->get('/terms', 'PageController@terms');

// ============================================
// AUTH ROUTES
// ============================================

$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@authenticate');
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@store');
$router->get('/logout', 'AuthController@logout');
$router->get('/forgot-password', 'AuthController@forgotPassword');
$router->post('/forgot-password', 'AuthController@sendResetLink');
$router->get('/reset-password/{token}', 'AuthController@resetPassword');
$router->post('/reset-password', 'AuthController@updatePassword');

// ============================================
// CUSTOMER ACCOUNT ROUTES
// ============================================

$router->group('/account', function($router) {
    $router->get('', 'AccountController@dashboard');
    $router->get('/profile', 'AccountController@profile');
    $router->post('/profile/update', 'AccountController@updateProfile');
    $router->get('/orders', 'AccountController@orders');
    $router->get('/orders/{id}', 'AccountController@orderDetail');
    $router->get('/reservations', 'AccountController@reservations');
    $router->get('/favorites', 'AccountController@favorites');
    $router->post('/favorites/toggle', 'AccountController@toggleFavorite');
    $router->get('/reviews', 'AccountController@reviews');
    $router->post('/reviews/submit', 'AccountController@submitReview');
    $router->get('/wishlist', 'AccountController@wishlist');
    $router->get('/loyalty', 'AccountController@loyalty');
    $router->get('/addresses', 'AccountController@addresses');
    $router->post('/addresses/save', 'AccountController@saveAddress');
}, ['requireAuth']);

// ============================================
// ADMIN ROUTES
// ============================================

$router->group('/admin', function($router) {
    // Dashboard
    $router->get('', 'Admin\DashboardController@index');
    $router->get('/dashboard', 'Admin\DashboardController@index');
    $router->get('/api/dashboard/stats', 'Admin\DashboardController@stats');
    $router->get('/api/dashboard/charts', 'Admin\DashboardController@chartData');
    
    // Orders
    $router->get('/orders', 'Admin\OrderController@index');
    $router->get('/orders/{id}', 'Admin\OrderController@show');
    $router->post('/orders/{id}/status', 'Admin\OrderController@updateStatus');
    $router->get('/orders/{id}/receipt', 'Admin\OrderController@receipt');
    $router->get('/api/orders/recent', 'Admin\OrderController@recent');
    
    // Kitchen Display
    $router->get('/kitchen', 'Admin\KitchenController@index');
    $router->get('/api/kitchen/orders', 'Admin\KitchenController@orders');
    $router->post('/api/kitchen/update-status', 'Admin\KitchenController@updateStatus');
    
    // Reservations
    $router->get('/reservations', 'Admin\ReservationController@index');
    $router->get('/reservations/{id}', 'Admin\ReservationController@show');
    $router->post('/reservations/{id}/status', 'Admin\ReservationController@updateStatus');
    $router->post('/reservations/{id}/assign-table', 'Admin\ReservationController@assignTable');
    
    // Menu Management
    $router->get('/menu', 'Admin\MenuController@index');
    $router->get('/menu/create', 'Admin\MenuController@create');
    $router->post('/menu/store', 'Admin\MenuController@store');
    $router->get('/menu/{id}/edit', 'Admin\MenuController@edit');
    $router->post('/menu/{id}/update', 'Admin\MenuController@update');
    $router->post('/menu/{id}/delete', 'Admin\MenuController@delete');
    
    // Categories
    $router->get('/categories', 'Admin\CategoryController@index');
    $router->post('/categories/store', 'Admin\CategoryController@store');
    $router->post('/categories/{id}/update', 'Admin\CategoryController@update');
    $router->post('/categories/{id}/delete', 'Admin\CategoryController@delete');
    
    // Customers
    $router->get('/customers', 'Admin\CustomerController@index');
    $router->get('/customers/{id}', 'Admin\CustomerController@show');
    
    // Employees
    $router->get('/employees', 'Admin\EmployeeController@index');
    $router->get('/employees/create', 'Admin\EmployeeController@create');
    $router->post('/employees/store', 'Admin\EmployeeController@store');
    $router->get('/employees/{id}/edit', 'Admin\EmployeeController@edit');
    $router->post('/employees/{id}/update', 'Admin\EmployeeController@update');
    $router->post('/employees/{id}/delete', 'Admin\EmployeeController@delete');
    $router->get('/api/employees/attendance', 'Admin\EmployeeController@attendance');
    
    // Inventory
    $router->get('/inventory', 'Admin\InventoryController@index');
    $router->get('/inventory/ingredients', 'Admin\InventoryController@ingredients');
    $router->post('/inventory/ingredients/store', 'Admin\InventoryController@storeIngredient');
    $router->post('/inventory/stock/add', 'Admin\InventoryController@addStock');
    $router->get('/inventory/suppliers', 'Admin\InventoryController@suppliers');
    $router->post('/inventory/suppliers/store', 'Admin\InventoryController@storeSupplier');
    $router->get('/api/inventory/low-stock', 'Admin\InventoryController@lowStock');
    
    // Tables
    $router->get('/tables', 'Admin\TableController@index');
    $router->post('/tables/store', 'Admin\TableController@store');
    $router->post('/tables/{id}/update', 'Admin\TableController@update');
    $router->post('/tables/{id}/delete', 'Admin\TableController@delete');
    $router->get('/api/tables/status', 'Admin\TableController@status');
    
    // Coupons
    $router->get('/coupons', 'Admin\CouponController@index');
    $router->post('/coupons/store', 'Admin\CouponController@store');
    $router->post('/coupons/{id}/update', 'Admin\CouponController@update');
    $router->post('/coupons/{id}/delete', 'Admin\CouponController@delete');
    
    // Promotions
    $router->get('/promotions', 'Admin\PromotionController@index');
    $router->post('/promotions/store', 'Admin\PromotionController@store');
    $router->post('/promotions/{id}/update', 'Admin\PromotionController@update');
    $router->post('/promotions/{id}/delete', 'Admin\PromotionController@delete');
    
    // Reviews
    $router->get('/reviews', 'Admin\ReviewController@index');
    $router->post('/reviews/{id}/status', 'Admin\ReviewController@updateStatus');
    $router->post('/reviews/{id}/reply', 'Admin\ReviewController@reply');
    
    // Gallery
    $router->get('/gallery', 'Admin\GalleryController@index');
    $router->post('/gallery/upload', 'Admin\GalleryController@upload');
    $router->post('/gallery/{id}/delete', 'Admin\GalleryController@delete');
    
    // Blog
    $router->get('/blog', 'Admin\BlogController@index');
    $router->get('/blog/create', 'Admin\BlogController@create');
    $router->post('/blog/store', 'Admin\BlogController@store');
    $router->get('/blog/{id}/edit', 'Admin\BlogController@edit');
    $router->post('/blog/{id}/update', 'Admin\BlogController@update');
    $router->post('/blog/{id}/delete', 'Admin\BlogController@delete');
    
    // Settings
    $router->get('/settings', 'Admin\SettingController@index');
    $router->post('/settings/update', 'Admin\SettingController@update');
    
    // Users & Roles
    $router->get('/users', 'Admin\UserController@index');
    $router->get('/users/create', 'Admin\UserController@create');
    $router->post('/users/store', 'Admin\UserController@store');
    $router->get('/users/{id}/edit', 'Admin\UserController@edit');
    $router->post('/users/{id}/update', 'Admin\UserController@update');
    $router->post('/users/{id}/delete', 'Admin\UserController@delete');
    $router->get('/roles', 'Admin\RoleController@index');
    $router->post('/roles/store', 'Admin\RoleController@store');
    $router->post('/roles/{id}/permissions', 'Admin\RoleController@updatePermissions');
    
    // Activity Logs
    $router->get('/activity-logs', 'Admin\ActivityLogController@index');
    
    // Backups
    $router->get('/backups', 'Admin\BackupController@index');
    $router->post('/backups/create', 'Admin\BackupController@create');
    $router->post('/backups/{id}/restore', 'Admin\BackupController@restore');
    $router->post('/backups/{id}/delete', 'Admin\BackupController@delete');
    
    // Notifications
    $router->get('/api/notifications', 'Admin\NotificationController@index');
    $router->post('/api/notifications/read', 'Admin\NotificationController@markRead');
    
    // Reports
    $router->get('/reports', 'Admin\ReportController@index');
    $router->get('/api/reports/sales', 'Admin\ReportController@sales');
    $router->get('/api/reports/revenue', 'Admin\ReportController@revenue');
}, ['requireAdmin']);

// ============================================
// API ROUTES
// ============================================

$router->get('/api/foods/popular', 'Api\FoodController@popular');
$router->get('/api/foods/featured', 'Api\FoodController@featured');
$router->get('/api/foods/todays-special', 'Api\FoodController@todaysSpecial');
$router->get('/api/foods/chef-recommendations', 'Api\FoodController@chefRecommendations');
$router->get('/api/categories', 'Api\CategoryController@index');
$router->get('/api/testimonials', 'Api\TestimonialController@index');
$router->get('/api/gallery', 'Api\GalleryController@index');
$router->get('/api/events/upcoming', 'Api\EventController@upcoming');
$router->get('/api/blog/latest', 'Api\BlogController@latest');
$router->get('/api/search', 'Api\SearchController@index');

// 404 handler
$router->setNotFound('PageController@notFound');

// Dispatch the request
$router->dispatch();