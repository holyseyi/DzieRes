<?php
/**
 * Order Confirmation View
 *
 * @var object $order
 * @var array $orderItems
 * @var array $tracking
 */
$statusClass = match ($order->status) {
    'delivered' => 'success',
    'cancelled', 'rejected' => 'danger',
    'ready' => 'info',
    'default' => 'warning'
};
?>
<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="text-center mb-4" data-aos="fade-up">
                    <div class="success-check"><?= \icon('check', []) ?>></i></div>
                    <h2 class="mt-3">Order Confirmed!</h2>
                    <p class="text-muted">Thank you! Your order has been received.</p>
                </div>

                <!-- Receipt / Tracking Code -->
                <div class="glass-card p-4 mb-4 text-center" style="border: 2px dashed #001a4a;">
                    <h5 class="mb-2">Your Tracking Code</h5>
                    <div class="display-4 fw-bold text-gold mb-2" style="letter-spacing: 2px;"><?= \escape($order->order_number) ?></div>
                    <p class="text-muted mb-3">Use this code to track your order. Save it or screenshot this page.</p>
                    <button class="btn btn-outline-gold btn-sm" onclick="navigator.clipboard.writeText('<?= \escape($order->order_number) ?>').then(()=>alert('Tracking code copied!'))">
                        <?= \icon('copy', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i>Copy Code
                    </button>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="glass-card p-4">
                            <h5 class="mb-3">Order Details</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr><th>Item</th><th>Qty</th><th class="text-end">Total</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orderItems as $item): ?>
                                            <tr>
                                                <td><?= \escape($item->food_name) ?></td>
                                                <td><?= $item->quantity ?></td>
                                                <td class="text-end"><?= \formatPrice($item->total_price) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr><td colspan="2">Subtotal</td><td class="text-end"><?= \formatPrice($order->subtotal) ?></td></tr>
                                        <tr><td colspan="2">Tax</td><td class="text-end"><?= \formatPrice($order->tax_amount) ?></td></tr>
                                        <tr><td colspan="2">Delivery</td><td class="text-end"><?= \formatPrice($order->delivery_fee) ?></td></tr>
                                        <tr><td colspan="2"><strong>Total</strong></td><td class="text-end"><strong><?= \formatPrice($order->total_amount) ?></strong></td></tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="glass-card p-4">
                            <h5 class="mb-3">Status</h5>
                            <span class="badge bg-<?= $statusClass ?> fs-6"><?= ucfirst(str_replace('_', ' ', $order->status)) ?></span>
                            <dl class="mt-3 mb-0 small">
                                <dt>Order Type</dt><dd><?= ucfirst(str_replace('_', ' ', $order->order_type)) ?></dd>
                                <dt>Payment</dt><dd><?= ucwords(str_replace('_', ' ', $order->payment_method)) ?> (<?= ucfirst($order->payment_status) ?>)</dd>
                                <?php if ($order->order_type === 'delivery' && $order->delivery_address): ?>
                                    <dt>Address</dt><dd><?= \escape($order->delivery_address) ?>, <?= \escape($order->delivery_city ?? '') ?></dd>
                                <?php endif; ?>
                                <?php if ($order->order_type === 'pickup' && $order->pickup_location): ?>
                                    <dt>Pickup Location</dt><dd><?= \escape($order->pickup_location) ?></dd>
                                <?php endif; ?>
                                <?php if ($order->special_notes): ?>
                                    <dt>Notes</dt><dd><?= \escape($order->special_notes) ?></dd>
                                <?php endif; ?>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Tracking Timeline -->
                <div class="glass-card p-4 mt-4">
                    <h5 class="mb-3">Order Tracking</h5>
                    <div class="tracking-timeline">
                        <?php foreach ($tracking as $t): ?>
                            <div class="tracking-step completed">
                                <div class="step-dot"></div>
                                <div class="step-content">
                                    <strong class="text-capitalize"><?= str_replace('_', ' ', $t->status) ?></strong>
                                    <div class="small text-muted"><?= \formatDateTime($t->created_at) ?></div>
                                    <div class="small"><?= \escape($t->description) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="<?= \baseUrl('order/track/' . $order->order_number) ?>" class="btn btn-outline-gold me-2">Track Order</a>
                    <a href="<?= \baseUrl('menu') ?>" class="btn btn-gold">Order More</a>
                </div>
            </div>
        </div>
    </div>
</section>
