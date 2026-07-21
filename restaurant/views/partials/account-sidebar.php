<?php
/**
 * Partial: Account Sidebar
 */
$current = $_SERVER['REQUEST_URI'] ?? '';
$links = [
    'account' => ['label' => 'Dashboard', 'icon' => 'fa-gauge'],
    'account/orders' => ['label' => 'Orders', 'icon' => 'fa-receipt'],
    'account/reservations' => ['label' => 'Reservations', 'icon' => 'fa-calendar-check'],
    'account/favorites' => ['label' => 'Favorites', 'icon' => 'fa-heart'],
    'account/wishlist' => ['label' => 'Wishlist', 'icon' => 'fa-bookmark'],
    'account/reviews' => ['label' => 'Reviews', 'icon' => 'fa-star'],
    'account/loyalty' => ['label' => 'Loyalty', 'icon' => 'fa-gem'],
    'account/addresses' => ['label' => 'Addresses', 'icon' => 'fa-location-dot'],
    'account/profile' => ['label' => 'Profile', 'icon' => 'fa-user'],
];
?>
<div class="account-sidebar glass-card p-3">
    <div class="account-user text-center mb-3 pb-3 border-bottom">
        <div class="account-avatar mx-auto"><?= strtoupper(substr($user->firstname ?? 'U', 0, 1) . substr($user->lastname ?? '', 0, 1)) ?></div>
        <h6 class="mb-0 mt-2"><?= \escape(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) ?></h6>
        <small class="text-muted"><?= \escape($user->email ?? '') ?></small>
    </div>
    <nav class="account-nav">
        <?php foreach ($links as $url => $l): ?>
            <a href="<?= \baseUrl($url) ?>" class="account-nav-link <?= strpos($current, '/' . ltrim($url, '/')) !== false ? 'active' : '' ?>">
                <i class="fas <?= $l['icon'] ?> me-2"></i><?= $l['label'] ?>
            </a>
        <?php endforeach; ?>
        <a href="<?= \baseUrl('logout') ?>" class="account-nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </nav>
</div>
