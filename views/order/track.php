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
            <div class="col-lg-10">
                <?php if ($currentIndex === -1): ?>
                    <div class="alert alert-danger text-center py-4">
                        <?= \icon('times', ['style' => 'width:2.5em;height:2.5em;']) ?>
                        <h4 class="mt-2">This order was <?= ucfirst($order->status) ?></h4>
                        <p class="mb-0 text-muted">Please contact us if you have any questions.</p>
                    </div>
                <?php else: ?>
                    <div class="order-tracker mb-5">
                        <?php $i = 0; foreach ($steps as $key => $label): ?>
                            <div class="tracker-step <?= $i <= $currentIndex ? 'active' : '' ?>">
                                <?= $label ?>
                            </div>
                            <?php if ($i < count($steps) - 1): ?>
                                <div class="tracker-arrow <?= $i < $currentIndex ? 'active' : '' ?>"></div>
                            <?php endif; ?>
                        <?php $i++; endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="glass-card p-4 mb-4">
                            <h5 class="mb-3"><?= \icon('receipt', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;', 'class' => 'text-gold']) ?>Order Items</h5>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orderItems as $item): ?>
                                            <tr>
                                                <td><?= \escape($item->food_name) ?></td>
                                                <td class="text-center"><?= $item->quantity ?></td>
                                                <td class="text-end"><?= \formatPrice($item->unit_price) ?></td>
                                                <td class="text-end"><strong><?= \formatPrice($item->total_price) ?></strong></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                                            <td class="text-end text-gold"><strong><?= \formatPrice($order->total_amount) ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="small text-muted mt-2">
                                <span class="badge bg-light text-dark"><?= ucfirst(str_replace('_', ' ', $order->order_type)) ?></span>
                                <span class="badge bg-light text-dark"><?= ucwords(str_replace('_', ' ', $order->payment_method)) ?></span>
                                <span class="badge bg-light text-dark"><?= ucfirst($order->payment_status) ?></span>
                            </div>
                        </div>

                        <div class="glass-card p-4">
                            <h6 class="mb-3"><?= \icon('clock-rotate-left', ['style' => 'width:1em;height:1em;margin-right:0.4rem;vertical-align:-0.1em;', 'class' => 'text-gold']) ?>Tracking History</h6>
                            <div class="tracking-history">
                                <?php foreach ($tracking as $t): ?>
                                    <div class="tracking-entry">
                                        <div class="tracking-dot"></div>
                                        <div class="tracking-content">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong class="text-capitalize"><?= str_replace('_', ' ', $t->status) ?></strong>
                                                <small class="text-muted"><?= \formatDateTime($t->created_at) ?></small>
                                            </div>
                                            <div class="small text-muted"><?= \escape($t->description) ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="glass-card p-4 sticky-summary">
                            <h5 class="mb-3">Order Summary</h5>
                            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Order Number</span><strong><?= \escape($order->order_number) ?></strong></div>
                            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Date</span><span><?= \formatDateTime($order->created_at) ?></span></div>
                            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Type</span><span><?= ucfirst(str_replace('_', ' ', $order->order_type)) ?></span></div>
                            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Payment</span><span><?= ucwords(str_replace('_', ' ', $order->payment_method)) ?></span></div>
                            <hr>
                            <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span><?= \formatPrice($order->subtotal) ?></span></div>
                            <div class="d-flex justify-content-between mb-2"><span>Tax</span><span><?= \formatPrice($order->tax_amount) ?></span></div>
                            <div class="d-flex justify-content-between mb-2"><span>Delivery</span><span><?= \formatPrice($order->delivery_fee) ?></span></div>
                            <div class="d-flex justify-content-between mb-2"><span>Service</span><span><?= \formatPrice($order->service_charge) ?></span></div>
                            <?php if ($order->discount_amount > 0): ?>
                                <div class="d-flex justify-content-between mb-2 text-success"><span>Discount</span><span>-<?= \formatPrice($order->discount_amount) ?></span></div>
                            <?php endif; ?>
                            <hr>
                            <div class="d-flex justify-content-between fs-5 fw-bold"><span>Total</span><span class="text-gold"><?= \formatPrice($order->total_amount) ?></span></div>
                        </div>

                        <div class="text-center mt-3">
                            <a href="<?= \baseUrl('order/receipt/' . $order->order_number) ?>" class="btn btn-gold" target="_blank"><?= \icon('print', ['style' => 'width:0.9em;height:0.9em;margin-right:0.5rem;vertical-align:-0.15em;']) ?>Print Receipt</a>
                            <a href="<?= \baseUrl('menu') ?>" class="btn btn-outline-gold"><?= \icon('arrow-left', ['style' => 'width:1em;height:1em;margin-right:0.5rem;vertical-align:-0.15em;']) ?>Back to Menu</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="liveUpdateToast" class="live-toast" style="display:none;">
    <span class="live-dot"></span> Order status updated
</div>

<script>
(function() {
    var orderNumber = <?= json_encode($order->order_number) ?>;
    var apiUrl = '<?= \baseUrl('api/order/track/' . $order->order_number) ?>';

    function updateTracker(newStatus) {
        var steps = <?= json_encode(array_values($steps)) ?>;
        var keys = <?= json_encode(array_keys($steps)) ?>;
        var currentIndex = keys.indexOf(newStatus);

        if (currentIndex === -1) return;

        document.querySelectorAll('.tracker-step').forEach(function(el, idx) {
            el.classList.toggle('active', idx <= currentIndex);
        });

        document.querySelectorAll('.tracker-arrow').forEach(function(el, idx) {
            el.classList.toggle('active', idx < currentIndex);
        });

        var toast = document.getElementById('liveUpdateToast');
        if (toast) {
            toast.style.display = 'block';
            setTimeout(function() { toast.style.display = 'none'; }, 3000);
        }
    }

    function updateTrackingHistory(tracking) {
        var container = document.querySelector('.tracking-history');
        if (!container || !tracking || !tracking.length) return;

        var html = '';
        tracking.forEach(function(t) {
            html += '<div class="tracking-entry">' +
                '<div class="tracking-dot"></div>' +
                '<div class="tracking-content">' +
                '<div class="d-flex justify-content-between align-items-center">' +
                '<strong class="text-capitalize">' + t.status.replace(/_/g, ' ') + '</strong>' +
                '<small class="text-muted">' + t.created_at + '</small>' +
                '</div>' +
                '<div class="small text-muted">' + t.description + '</div>' +
                '</div></div>';
        });

        container.innerHTML = html;
    }

    if (orderNumber) {
        setInterval(function() {
            fetch(apiUrl)
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success && data.data && data.data.found) {
                        updateTracker(data.data.status);
                        updateTrackingHistory(data.data.tracking);
                    }
                })
                .catch(function() {});
        }, 3000);
    }
})();
</script>
