<?php
/**
 * Global Helper Functions
 * Restaurant Management System
 */

use Config\Database;

// ============================================
// APPLICATION HELPERS
// ============================================

function app(): array
{
    static $config = null;
    if ($config === null) {
        $config = require __DIR__ . '/../config/app.php';
    }
    return $config;
}

function db(): Database
{
    return Database::getInstance();
}

function config(string $key, $default = null)
{
    // Allow database-stored settings to override the static config file so
    // that changes made in the admin panel are reflected on the website.
    $dbValue = dbConfigValue($key);
    if ($dbValue !== null) {
        return $dbValue;
    }

    $keys = explode('.', $key);
    $value = app();
    
    foreach ($keys as $segment) {
        if (!isset($value[$segment])) {
            return $default;
        }
        $value = $value[$segment];
    }
    
    return $value;
}

/**
 * Settings stored in the database that map to config() keys.
 * Returns the DB value if present, otherwise null to fall back to config file.
 */
function dbConfigValue(string $key)
{
    static $map = [
        'tax.rate'               => 'tax_rate',
        'service_charge'         => 'service_charge',
        'delivery.fee'           => 'delivery_fee',
        'delivery.free_above'    => 'free_delivery_above',
        'currency.symbol'        => 'currency_symbol',
        'currency.code'          => 'currency_code',
        'restaurant.name'        => 'restaurant_name',
        'restaurant.tagline'     => 'restaurant_tagline',
        'restaurant.phone'       => 'restaurant_phone',
        'restaurant.email'       => 'restaurant_email',
        'restaurant.address'     => 'restaurant_address',
        'restaurant.opening_hours' => 'opening_hours',
        'loyalty.points_per_ghs' => 'loyalty_points_per_ghs',
        'loyalty.welcome_points' => 'welcome_points',
        'app.accent_color'       => 'accent_color',
        'app.primary_color'      => 'primary_color',
    ];

    if (!isset($map[$key])) {
        return null;
    }

    try {
        return getSetting($map[$key]);
    } catch (\Throwable $e) {
        return null;
    }
}

// ============================================
// URL & ROUTING HELPERS
// ============================================

function detectBaseUrl(): string
{
    // Allow config override (e.g. a fixed production URL)
    $configured = config('url');
    if (!empty($configured) && $configured !== 'http://localhost/restaurant') {
        return rtrim($configured, '/');
    }

    // Otherwise derive dynamically from the request
    if (!empty($_SERVER['HTTP_HOST'])) {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $script = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        $basePath = rtrim(dirname($script), '/');
        return $scheme . '://' . $host . $basePath;
    }

    return rtrim($configured ?: 'http://localhost/restaurant', '/');
}

function baseUrl(string $path = ''): string
{
    static $base = null;
    if ($base === null) {
        $base = detectBaseUrl();
    }
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

function redirect(string $url): void
{
    header("Location: {$url}");
    exit;
}

function back(): void
{
    $referer = $_SERVER['HTTP_REFERER'] ?? baseUrl();
    redirect($referer);
}

function currentUrl(): string
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return "{$protocol}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
}

// ============================================
// SESSION HELPERS
// ============================================

function session(): array
{
    return $_SESSION;
}

function sessionSet(string $key, $value): void
{
    $_SESSION[$key] = $value;
}

function sessionGet(string $key, $default = null)
{
    return $_SESSION[$key] ?? $default;
}

function sessionFlash(string $key, $value = null)
{
    if ($value !== null) {
        $_SESSION['_flash'][$key] = $value;
        return;
    }
    
    $flash = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $flash;
}

function sessionHas(string $key): bool
{
    return isset($_SESSION[$key]);
}

function sessionRemove(string $key): void
{
    unset($_SESSION[$key]);
}

function sessionDestroy(): void
{
    $_SESSION = [];
    session_destroy();
}

// ============================================
// AUTHENTICATION HELPERS
// ============================================

function auth(): ?object
{
    if (!sessionHas('user_id')) {
        return null;
    }
    
    $user = db()->fetch(
        "SELECT u.*, r.name as role_name, r.slug as role_slug 
         FROM users u 
         JOIN roles r ON u.role_id = r.id 
         WHERE u.id = ? AND u.status = 'active'",
        [sessionGet('user_id')]
    );
    
    return $user ?: null;
}

function isAdmin(): bool
{
    $user = auth();
    return $user && $user->role_slug === 'admin';
}

function isStaff(): bool
{
    $user = auth();
    return $user && in_array($user->role_slug, ['admin', 'staff']);
}

function isCustomer(): bool
{
    $user = auth();
    return $user && $user->role_slug === 'customer';
}

function requireAuth(): void
{
    if (!auth()) {
        sessionFlash('error', 'Please login to continue');
        redirect(baseUrl('login'));
    }
}

function requireAdmin(): void
{
    if (!isAdmin()) {
        sessionFlash('error', 'Access denied');
        redirect(baseUrl());
    }
}

function requireStaff(): void
{
    if (!isStaff()) {
        sessionFlash('error', 'Access denied');
        redirect(baseUrl());
    }
}

// ============================================
// CSRF PROTECTION
// ============================================

function csrfToken(): string
{
    if (!sessionHas('_csrf_token')) {
        $token = bin2hex(random_bytes(32));
        sessionSet('_csrf_token', $token);
    }
    return sessionGet('_csrf_token');
}

function csrfField(): string
{
    return '<input type="hidden" name="_csrf_token" value="' . csrfToken() . '">';
}

function verifyCsrf(?string $token = null): bool
{
    $token = $token ?? ($_POST['_csrf_token'] ?? '');
    $stored = sessionGet('_csrf_token');
    
    if (empty($token) || empty($stored)) {
        return false;
    }
    
    return hash_equals($stored, $token);
}

// ============================================
// INPUT VALIDATION
// ============================================

function input(string $key, $default = null, string $method = 'post')
{
    $source = strtoupper($method) === 'GET' ? $_GET : $_POST;
    return $source[$key] ?? $default;
}

function inputAll(string $method = 'post'): array
{
    $source = strtoupper($method) === 'GET' ? $_GET : $_POST;
    // Remove CSRF token from input
    unset($source['_csrf_token']);
    return $source;
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function sanitize($value): string
{
    if ($value === null) {
        return '';
    }
    return strip_tags(trim((string) $value));
}

function validateEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validatePhone(string $phone): bool
{
    return preg_match('/^\+?[\d\s\-()]{7,20}$/', $phone) === 1;
}

function validateRequired(string $value): bool
{
    return trim($value) !== '';
}

function validateMinLength(string $value, int $min): bool
{
    return mb_strlen(trim($value)) >= $min;
}

function validateMaxLength(string $value, int $max): bool
{
    return mb_strlen(trim($value)) <= $max;
}

// ============================================
// STRING HELPERS
// ============================================

function slugify($text): string
{
    $text = (string) $text;
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    
    return empty($text) ? 'n-a' : $text;
}

function truncate(string $text, int $length = 100, string $suffix = '...'): string
{
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    
    return mb_substr($text, 0, $length - mb_strlen($suffix)) . $suffix;
}

function randomString(int $length = 10): string
{
    return bin2hex(random_bytes($length / 2));
}

function generateOrderNumber(): string
{
    return 'ORD-' . strtoupper(bin2hex(random_bytes(4))) . '-' . date('Ymd');
}

function generateReservationNumber(): string
{
    return 'RSV-' . strtoupper(bin2hex(random_bytes(3))) . '-' . date('Ymd');
}

function generateInvoiceNumber(): string
{
    return 'INV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
}

// ============================================
// FORMATTING HELPERS
// ============================================

function formatPrice(float $amount): string
{
    $symbol = config('currency.symbol', '₵');
    return $symbol . number_format($amount, 2);
}

function formatDate(string $date, string $format = 'M d, Y'): string
{
    return date($format, strtotime($date));
}

function formatTime(string $time, string $format = 'h:i A'): string
{
    return date($format, strtotime($time));
}

function formatDateTime(string $datetime, string $format = 'M d, Y h:i A'): string
{
    return date($format, strtotime($datetime));
}

function timeAgo(string $datetime): string
{
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    $intervals = [
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    ];
    
    foreach ($intervals as $seconds => $label) {
        $count = floor($diff / $seconds);
        if ($count >= 1) {
            return $count . ' ' . $label . ($count > 1 ? 's' : '') . ' ago';
        }
    }
    
    return 'just now';
}

// ============================================
// FILE UPLOAD HELPERS
// ============================================

function uploadFile(array $file, string $directory = 'images'): ?string
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    $allowedTypes = config('upload.allowed_types', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    $maxSize = config('upload.max_size', 5242880);
    $uploadPath = config('upload.path', __DIR__ . '/../uploads') . '/' . $directory;
    
    // Create directory if not exists
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($extension, $allowedTypes)) {
        return null;
    }
    
    if ($file['size'] > $maxSize) {
        return null;
    }
    
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadPath . '/' . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return 'uploads/' . $directory . '/' . $filename;
    }
    
    return null;
}

function deleteFile(?string $path): bool
{
    if (empty($path)) {
        return false;
    }
    
    $fullPath = __DIR__ . '/../' . $path;
    if (file_exists($fullPath)) {
        return unlink($fullPath);
    }
    
    return false;
}

// ============================================
// RESPONSE HELPERS
// ============================================

function jsonResponse(array $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function jsonSuccess($data = null, string $message = 'Success'): void
{
    jsonResponse([
        'success' => true,
        'message' => $message,
        'data' => $data
    ]);
}

function jsonError(string $message = 'Error', int $statusCode = 400, $errors = null): void
{
    jsonResponse([
        'success' => false,
        'message' => $message,
        'errors' => $errors
    ], $statusCode);
}

// ============================================
// NOTIFICATION HELPERS
// ============================================

function createNotification(?int $userId, string $type, string $title, string $message, ?string $link = null): int
{
    return db()->insert('notifications', [
        'user_id' => $userId,
        'type' => $type,
        'title' => $title,
        'message' => $message,
        'link' => $link
    ]);
}

function getUnreadNotifications(int $userId): array
{
    return db()->fetchAll(
        "SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC LIMIT 10",
        [$userId]
    );
}

function getUnreadNotificationCount(int $userId): int
{
    $result = db()->fetch(
        "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0",
        [$userId]
    );
    return $result ? (int)$result->count : 0;
}

// ============================================
// ACTIVITY LOGGING
// ============================================

function logActivity(string $action, string $module, string $description, ?int $userId = null): void
{
    db()->insert('activity_logs', [
        'user_id' => $userId ?? sessionGet('user_id'),
        'action' => $action,
        'module' => $module,
        'description' => $description,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);
}

// ============================================
// CART HELPERS
// ============================================

function getCartCount(): int
{
    if (auth()) {
        $result = db()->fetch(
            "SELECT SUM(quantity) as count FROM carts WHERE user_id = ?",
            [auth()->id]
        );
        return $result ? (int)$result->count : 0;
    }
    
    $cart = $_SESSION['cart'] ?? [];
    return array_sum(array_column($cart, 'quantity'));
}

function getCartTotal(): float
{
    if (auth()) {
        $result = db()->fetch(
            "SELECT SUM(total_price) as total FROM carts WHERE user_id = ?",
            [auth()->id]
        );
        return $result ? (float)$result->total : 0;
    }
    
    $cart = $_SESSION['cart'] ?? [];
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// ============================================
// LOYALTY POINTS
// ============================================

function addLoyaltyPoints(int $userId, int $points, string $type = 'earned', ?string $description = null, ?string $referenceType = null, ?int $referenceId = null): void
{
    db()->insert('loyalty_points', [
        'user_id' => $userId,
        'points' => $points,
        'type' => $type,
        'description' => $description,
        'reference_type' => $referenceType,
        'reference_id' => $referenceId
    ]);
}

function getLoyaltyPoints(int $userId): int
{
    $earned = db()->fetch(
        "SELECT COALESCE(SUM(CASE WHEN type IN ('earned', 'bonus') THEN points ELSE 0 END), 0) as earned,
                COALESCE(SUM(CASE WHEN type = 'redeemed' THEN points ELSE 0 END), 0) as redeemed
         FROM loyalty_points WHERE user_id = ?",
        [$userId]
    );
    
    return $earned ? (int)($earned->earned - $earned->redeemed) : 0;
}

// ============================================
// STATUS COLOR HELPER (Admin UI)
// ============================================

function matchStatusColor(string $status): string
{
    return match ($status) {
        'delivered', 'completed', 'paid', 'approved', 'active', 'confirmed' => 'success',
        'cancelled', 'rejected', 'failed', 'expired', 'banned', 'no_show' => 'danger',
        'pending', 'in_stock' => 'warning',
        'ready', 'preparing', 'cooking', 'accepted', 'reviewed', 'attended' => 'info',
        default => 'secondary'
    };
}

// ============================================
// PAGINATION
// ============================================

function paginate(string $query, string $countQuery, array $params = [], int $perPage = 12): array
{
    $page = max(1, (int)($_GET['page'] ?? 1));
    $offset = ($page - 1) * $perPage;
    
    $totalResult = db()->fetch($countQuery, $params);
    $total = $totalResult ? (int)$totalResult->count : 0;
    $totalPages = max(1, ceil($total / $perPage));
    
    $items = db()->fetchAll(
        $query . " LIMIT {$perPage} OFFSET {$offset}",
        $params
    );
    
    return [
        'items' => $items,
        'current_page' => $page,
        'per_page' => $perPage,
        'total' => $total,
        'total_pages' => $totalPages,
        'has_prev' => $page > 1,
        'has_next' => $page < $totalPages,
        'prev_page' => $page - 1,
        'next_page' => $page + 1,
    ];
}

function paginationLinks(array $paginator, string $url = ''): string
{
    if ($paginator['total_pages'] <= 1) {
        return '';
    }
    
    $url = $url ?: currentUrl();
    $url = preg_replace('/[?&]page=\d+/', '', $url);
    $separator = strpos($url, '?') === false ? '?' : '&';
    
    $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
    
    // Previous
    $prevClass = $paginator['has_prev'] ? '' : ' disabled';
    $html .= '<li class="page-item' . $prevClass . '">';
    if ($paginator['has_prev']) {
        $html .= '<a class="page-link" href="' . $url . $separator . 'page=' . $paginator['prev_page'] . '">Previous</a>';
    } else {
        $html .= '<span class="page-link">Previous</span>';
    }
    $html .= '</li>';
    
    // Pages
    for ($i = 1; $i <= $paginator['total_pages']; $i++) {
        $activeClass = $i === $paginator['current_page'] ? ' active' : '';
        $html .= '<li class="page-item' . $activeClass . '">';
        $html .= '<a class="page-link" href="' . $url . $separator . 'page=' . $i . '">' . $i . '</a>';
        $html .= '</li>';
    }
    
    // Next
    $nextClass = $paginator['has_next'] ? '' : ' disabled';
    $html .= '<li class="page-item' . $nextClass . '">';
    if ($paginator['has_next']) {
        $html .= '<a class="page-link" href="' . $url . $separator . 'page=' . $paginator['next_page'] . '">Next</a>';
    } else {
        $html .= '<span class="page-link">Next</span>';
    }
    $html .= '</li>';
    
    $html .= '</ul></nav>';
    
    return $html;
}

// ============================================
// SETTINGS HELPERS
// ============================================

function getSetting(string $key, $default = null)
{
    $result = db()->fetch("SELECT value FROM settings WHERE `key` = ?", [$key]);
    return $result ? $result->value : $default;
}

function setSetting(string $key, $value): void
{
    $existing = db()->fetch("SELECT id FROM settings WHERE `key` = ?", [$key]);
    if ($existing) {
        db()->update('settings', ['value' => $value], 'id = :id', ['id' => $existing->id]);
    } else {
        db()->insert('settings', ['key' => $key, 'value' => $value]);
    }
}

// ============================================
// VIEW HELPERS
// ============================================

function view(string $view, array $data = []): void
{
    extract($data);
    
    $viewPath = __DIR__ . '/../views/' . $view . '.php';
    
    if (!file_exists($viewPath)) {
        throw new \RuntimeException("View not found: {$view}");
    }
    
    require $viewPath;
}

function partial(string $partial, array $data = []): void
{
    extract($data);
    
    $partialPath = __DIR__ . '/../views/partials/' . $partial . '.php';
    
    if (!file_exists($partialPath)) {
        throw new \RuntimeException("Partial not found: {$partial}");
    }
    
    require $partialPath;
}

function asset(string $path): string
{
    return baseUrl('assets/' . ltrim($path, '/'));
}

function icon(string $name, array $attrs = []): string
{
    static $map = [
        'search' => 'search.svg',
        'cart' => 'cart.svg',
        'message' => 'message.svg',
        'send' => 'send.svg',
        'user' => 'user.svg',
        'heart' => 'heart.svg',
        'lock' => 'lock.svg',
        'clock' => 'clock.svg',
        'star' => 'star.svg',
        'utensils' => 'utensils.svg',
        'calendar-check' => 'calendar-check.svg',
        'user-plus' => 'user-plus.svg',
        'bell' => 'bell.svg',
        'bars' => 'bars.svg',
        'cog' => 'cog.svg',
        'sign-out' => 'sign-out.svg',
        'home' => 'home.svg',
        'map-marker' => 'map-marker.svg',
        'phone' => 'phone.svg',
        'search-location' => 'search-location.svg',
        'times' => 'times.svg',
        'check' => 'check.svg',
        'exclamation' => 'exclamation.svg',
        'info' => 'info.svg',
        'quote-left' => 'quote-left.svg',
        'gem' => 'gem.svg',
        'shield' => 'shield.svg',
        'moon' => 'moon.svg',
        'search-plus' => 'search-plus.svg',
        'receipt' => 'receipt.svg',
        'times-circle' => 'times.svg',
        'times' => 'times.svg',
        'exclamation-triangle' => 'exclamation.svg',
        'gauge' => 'gauge.svg',
        'leaf' => 'leaf.svg',
        'location-dot' => 'location-dot.svg',
        'bookmark' => 'bookmark.svg',
        'circle' => 'circle.svg',
        'briefcase' => 'briefcase.svg',
        'paint-brush' => 'utensils.svg',
        'hand-holding-heart' => 'heart.svg',
        'building' => 'home.svg',
        'store' => 'home.svg',
        'warehouse' => 'box.svg',
        'truck-loading' => 'cart.svg',
        'clipboard-check' => 'check.svg',
        'tasks' => 'check.svg',
        'user-check' => 'user.svg',
        'user-edit' => 'user.svg',
        'user-times' => 'user.svg',
        'file-invoice' => 'receipt.svg',
        'print' => 'receipt.svg',
        'ban' => 'times.svg',
        'redo' => 'arrow-up.svg',
        'sync' => 'arrow-up.svg',
        'undo' => 'sign-out.svg',
        'filter' => 'search.svg',
        'plus' => 'plus.svg',
        'edit' => 'cog.svg',
        'trash' => 'trash.svg',
        'eye' => 'search.svg',
        'download' => 'download.svg',
        'upload' => 'send.svg',
        'file-alt' => 'receipt.svg',
        'home' => 'home.svg',
        'globe' => 'home.svg',
        'sort-amount-down' => 'chart-bar.svg',
        'calendar' => 'calendar-check.svg',
        'fire' => 'fire.svg',
        'plus-circle' => 'plus-circle.svg',
        'chart-area' => 'chart-area.svg',
        'chart-line' => 'chart-line.svg',
        'chart-pie' => 'chart-pie.svg',
        'arrow-left' => 'arrow-left.svg',
        'kitchen-set' => 'kitchen-set.svg',
    ];

    $file = $map[$name] ?? 'search.svg';
    $src = asset('images/icons/' . $file);

    $html = '<img src="' . $src . '" alt="' . $name . '" class="icon icon-' . $name . '"';
    foreach ($attrs as $k => $v) {
        $html .= ' ' . $k . '="' . htmlspecialchars($v, ENT_QUOTES) . '"';
    }
    $html .= '>';

    return $html;
}

function uploadUrl(?string $path): string
{
    if (empty($path)) {
        return asset('images/food-placeholder.svg');
    }
    return baseUrl($path);
}

function menuImageUrl(object $food): string
{
    $image = $food->image ?? '';
    if (!empty($image)) {
        return uploadUrl($image);
    }

    $slug = $food->slug ?? '';
    $menuDir = __DIR__ . '/../assets/images/menu/';
    if ($slug && is_dir($menuDir)) {
        foreach (glob($menuDir . $slug . '-*.{jpg,jpeg,png,webp}', GLOB_BRACE) as $file) {
            return asset('images/menu/' . basename($file));
        }
    }

    return asset('images/food-placeholder.svg');
}

// ============================================
// ERROR HANDLING
// ============================================

function showError(int $code = 404, string $message = 'Page not found'): void
{
    http_response_code($code);
    view('errors/' . $code, ['message' => $message]);
    exit;
}

// ============================================
// RATE LIMITING
// ============================================

function checkRateLimit(string $key, int $maxAttempts = 60, int $window = 60): bool
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $cacheKey = "rate_limit:{$key}:{$ip}";
    $cacheFile = __DIR__ . '/../logs/' . md5($cacheKey) . '.cache';
    
    $attempts = [];
    if (file_exists($cacheFile)) {
        $attempts = json_decode(file_get_contents($cacheFile), true) ?? [];
    }
    
    // Remove old attempts
    $attempts = array_filter($attempts, function($time) use ($window) {
        return $time > (time() - $window);
    });
    
    if (count($attempts) >= $maxAttempts) {
        return false;
    }
    
    $attempts[] = time();
    file_put_contents($cacheFile, json_encode($attempts));
    
    return true;
}
