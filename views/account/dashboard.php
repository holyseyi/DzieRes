<?php
/**
 * Account Dashboard View
 *
 * @var array $stats
 * @var array $recentOrders
 * @var object $user
 */
?>
<section class="page-hero" style="min-height:auto;padding:70px 0;">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">Welcome Back</p>
            <h1 class="page-title">My Account</h1>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3"><?php \partial('account-sidebar', ['user' => $user]); ?></div>
            <div class="col-lg-9">
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3"><div class="glass-card p-3 text-center"><div class="stat-number" data-count="<?= $stats['orders'] ?>">0</div><small class="text-muted">Orders</small></div></div>
                    <div class="col-6 col-md-3"><div class="glass-card p-3 text-center"><div class="stat-number" data-count="<?= $stats['reservations'] ?>">0</div><small class="text-muted">Reservations</small></div></div>
                    <div class="col-6 col-md-3"><div class="glass-card p-3 text-center"><div class="stat-number" data-count="<?= $stats['favorites'] ?>">0</div><small class="text-muted">Favorites</small></div></div>
                    <div class="col-6 col-md-3"><div class="glass-card p-3 text-center"><div class="stat-number text-gold" data-count="<?= $stats['loyalty'] ?>">0</div><small class="text-muted">Loyalty Pts</small></div></div>
                </div>

                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Recent Orders</h5>
                        <a href="<?= \baseUrl('account/orders') ?>" class="text-gold small">View All</a>
                    </div>
                    <?php if (!empty($recentOrders)): ?>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead><tr><th>Order</th><th>Date</th><th>Total</th><th>Status</th></tr></thead>
                                <tbody>
                                    <?php foreach ($recentOrders as $o): ?>
                                        <tr>
                                            <td><a href="<?= \baseUrl('account/orders/' . $o->id) ?>" class="text-decoration-none">#<?= \escape($o->order_number) ?></a></td>
                                            <td><?= \formatDate($o->created_at) ?></td>
                                            <td><?= \formatPrice($o->total_amount) ?></td>
                                            <td><span class="badge bg-secondary"><?= ucfirst(str_replace('_',' ',$o->status)) ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No orders yet. <a href="<?= \baseUrl('menu') ?>" class="text-gold">Start ordering</a>!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
