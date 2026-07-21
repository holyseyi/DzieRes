<?php
/**
 * Account Order Detail View
 *
 * @var object $order
 * @var array $items
 */
?>
<section class="page-hero" style="min-height:auto;padding:70px 0;"><div class="container"><h1 class="page-title">Order #<?= \escape($order->order_number) ?></h1></div></section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3"><?php \partial('account-sidebar', ['user' => \auth()]); ?></div>
            <div class="col-lg-9">
                <div class="glass-card p-4 mb-3">
                    <div class="row g-3">
                        <div class="col-md-3"><strong>Status</strong><div><span class="badge bg-secondary"><?= ucfirst(str_replace('_',' ',$order->status)) ?></span></div></div>
                        <div class="col-md-3"><strong>Type</strong><div class="text-muted"><?= ucfirst(str_replace('_',' ',$order->order_type)) ?></div></div>
                        <div class="col-md-3"><strong>Payment</strong><div class="text-muted"><?= ucwords(str_replace('_',' ',$order->payment_method)) ?></div></div>
                        <div class="col-md-3"><strong>Total</strong><div class="text-gold fw-bold"><?= \formatPrice($order->total_amount) ?></div></div>
                    </div>
                </div>
                <div class="glass-card p-4">
                    <h6 class="mb-3">Items</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead><tr><th>Item</th><th>Qty</th><th class="text-end">Total</th></tr></thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr><td><?= \escape($item->food_name) ?></td><td><?= $item->quantity ?></td><td class="text-end"><?= \formatPrice($item->total_price) ?></td></tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <a href="<?= \baseUrl('order/track/' . $order->order_number) ?>" class="btn btn-outline-gold">Track Order</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
