<?php
/**
 * Partial: Account Sidebar
 */
$current = $_SERVER['REQUEST_URI'] ?? '';
$user = \auth();
$isRider = $user && $user->role_slug === 'rider';
$links = [
    ($isRider ? 'rider' : 'account') => ['label' => 'Dashboard', 'icon' => 'home'],
    'account/orders' => ['label' => 'Orders', 'icon' => 'receipt'],
    'account/reservations' => ['label' => 'Reservations', 'icon' => 'calendar-check'],
    'account/favorites' => ['label' => 'Favorites', 'icon' => 'heart'],
    'account/wishlist' => ['label' => 'Wishlist', 'icon' => 'star'],
    'account/reviews' => ['label' => 'Reviews', 'icon' => 'star'],
    'account/loyalty' => ['label' => 'Loyalty', 'icon' => 'gem'],
    'account/addresses' => ['label' => 'Addresses', 'icon' => 'map-marker'],
    'account/profile' => ['label' => 'Profile', 'icon' => 'user'],
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
                <?= \icon($l['icon'], ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;']) ?> <?= $l['label'] ?>
            </a>
        <?php endforeach; ?>
        <a href="<?= \baseUrl('logout') ?>" class="account-nav-link text-danger"><?= \icon('sign-out', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;']) ?> Logout</a>
    </nav>
</div>
