<?php
/**
 * Account Orders View
 *
 * @var array $orders
 */
?>
<section class="page-hero" style="min-height:auto;padding:70px 0;"><div class="container"><h1 class="page-title">My Orders</h1></div></section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3"><?php \partial('account-sidebar', ['user' => \auth()]); ?></div>
            <div class="col-lg-9">
                <div class="glass-card p-4">
                    <?php if (!empty($orders)): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead><tr><th>Order #</th><th>Date</th><th>Type</th><th>Total</th><th>Status</th><th></th></tr></thead>
                                <tbody>
                                    <?php foreach ($orders as $o): ?>
                                        <tr>
                                            <td>#<?= \escape($o->order_number) ?></td>
                                            <td><?= \formatDate($o->created_at) ?></td>
                                            <td><?= ucfirst(str_replace('_', ' ', $o->order_type)) ?></td>
                                            <td><?= \formatPrice($o->total_amount) ?></td>
                                            <td><span class="badge bg-secondary"><?= ucfirst(str_replace('_', ' ', $o->status)) ?></span></td>
                                            <td><a href="<?= \baseUrl('account/orders/' . $o->id) ?>" class="btn btn-sm btn-outline-gold">View</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">You have no orders yet.</p>
                            <a href="<?= \baseUrl('menu') ?>" class="btn btn-gold">Order Now</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
