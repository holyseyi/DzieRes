<?php
/**
 * Customer Order Receipt (printable)
 *
 * @var object $order
 * @var array $items
 */
?>
<div class="receipt-print" id="printArea">
    <div class="text-center mb-3">
        <h4 class="mb-0">DzieRes Restaurant</h4>
        <small class="text-muted">123 Independence Avenue, Accra</small>
        <p class="mb-0">Tel: +233 50 000 0000</p>
    </div>
    <hr>
    <div class="d-flex justify-content-between small">
        <span>Order: #<?= \escape($order->order_number) ?></span>
        <span><?= \formatDateTime($order->created_at) ?></span>
    </div>
    <div class="small">Customer: <?= \escape($order->guest_name ?? 'Guest') ?></div>
    <div class="small">Type: <?= ucfirst(str_replace('_', ' ', $order->order_type)) ?></div>
    <hr>
    <table class="w-100 small">
        <thead><tr><th>Item</th><th class="text-end">Qty</th><th class="text-end">Total</th></tr></thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr><td><?= \escape($item->food_name) ?></td><td class="text-end"><?= $item->quantity ?></td><td class="text-end"><?= \formatPrice($item->total_price) ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <hr>
    <div class="d-flex justify-content-between small"><span>Subtotal</span><span><?= \formatPrice($order->subtotal) ?></span></div>
    <div class="d-flex justify-content-between small"><span>Tax</span><span><?= \formatPrice($order->tax_amount) ?></span></div>
    <div class="d-flex justify-content-between small"><span>Delivery</span><span><?= \formatPrice($order->delivery_fee) ?></span></div>
    <div class="d-flex justify-content-between small"><span>Service</span><span><?= \formatPrice($order->service_charge) ?></span></div>
    <div class="d-flex justify-content-between fw-bold"><span>TOTAL</span><span><?= \formatPrice($order->total_amount) ?></span></div>
    <hr>
    <p class="text-center small text-muted mb-0">Thank you for dining with us!</p>
</div>
<div class="text-center mt-3 no-print">
    <button class="btn btn-gold" onclick="window.print()"><?= \icon('print', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>Print Receipt</button>
    <a href="<?= \baseUrl('order/track/' . $order->order_number) ?>" class="btn btn-outline-secondary">Back to Tracking</a>
</div>
