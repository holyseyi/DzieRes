<?php
/**
 * Order Tracking View
 *
 * @var object $order
 * @var array $orderItems
 * @var array $tracking
 */
$steps = ['pending' => 'Order Placed', 'accepted' => 'Accepted', 'preparing' => 'Preparing', 'cooking' => 'Cooking', 'ready' => 'Ready', 'delivered' => 'Delivered'];
$currentIndex = array_search($order->status, array_keys($steps));
if ($order->status === 'cancelled' || $order->status === 'rejected') {
    $currentIndex = -1;
}
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">Track Your Order</p>
            <h1 class="page-title">Order #<?= \escape($order->order_number) ?></h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if ($currentIndex === -1): ?>
                    <div class="alert alert-danger text-center">
                        <?= \icon('times', ['style' => 'width:2em;height:2em;']) ?>
                        <h4>This order was <?= ucfirst($order->status) ?></h4>
                    </div>
                <?php else: ?>
                    <div class="order-tracker mb-5">
                        <?php $i = 0; foreach ($steps as $key => $label): ?>
                            <div class="tracker-step <?= $i <= $currentIndex ? 'active' : '' ?>">
                                <div class="tracker-icon"><?= \icon($i <= $currentIndex ? 'check' : 'circle', ['style' => 'width:2em;height:2em;']) ?></div>
                                <span><?= $label ?></span>
                            </div>
                            <?php if ($i < count($steps) - 1): ?><div class="tracker-line <?= $i < $currentIndex ? 'active' : '' ?>"></div><?php endif; ?>
                        <?php $i++; endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="glass-card p-4">
                    <h5 class="mb-3">Items</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead><tr><th>Item</th><th>Qty</th><th class="text-end">Total</th></tr></thead>
                            <tbody>
                                <?php foreach ($orderItems as $item): ?>
                                    <tr><td><?= \escape($item->food_name) ?></td><td><?= $item->quantity ?></td><td class="text-end"><?= \formatPrice($item->total_price) ?></td></tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr><td colspan="2"><strong>Total</strong></td><td class="text-end"><strong><?= \formatPrice($order->total_amount) ?></strong></td></tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="small text-muted">
                        Order Type: <?= ucfirst(str_replace('_', ' ', $order->order_type)) ?> |
                        Payment: <?= ucwords(str_replace('_', ' ', $order->payment_method)) ?> (<?= ucfirst($order->payment_status) ?>)
                    </div>
                </div>

                <div class="glass-card p-4 mt-3">
                    <h6 class="mb-3">History</h6>
                    <?php foreach ($tracking as $t): ?>
                        <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                            <div>
                                <strong class="text-capitalize"><?= str_replace('_', ' ', $t->status) ?></strong>
                                <div class="small text-muted"><?= \escape($t->description) ?></div>
                            </div>
                            <small class="text-muted"><?= \formatDateTime($t->created_at) ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="text-center mt-4">
                    <a href="<?= \baseUrl() ?>" class="btn btn-outline-gold">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</section>
