<?php
/**
 * Admin: Order Detail
 *
 * @var object $order
 * @var array $items
 * @var array $tracking
 */
$statuses = ['pending', 'accepted', 'preparing', 'cooking', 'ready', 'delivered', 'cancelled', 'rejected'];
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?= \baseUrl('admin/orders') ?>" class="text-muted small"><?= \icon('arrow-left', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i>Back to Orders</a>
        <h4 class="mb-0 mt-1">Order #<?= \escape($order->order_number) ?></h4>
    </div>
    <button class="btn btn-outline-gold" onclick="window.print()"><?= \icon('print', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i>Print Receipt</button>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">Items</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light"><tr><th>Item</th><th>Qty</th><th class="text-end">Price</th><th class="text-end">Total</th></tr></thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= \escape($item->food_name) ?></td>
                                    <td><?= $item->quantity ?></td>
                                    <td class="text-end"><?= \formatPrice($item->unit_price) ?></td>
                                    <td class="text-end"><?= \formatPrice($item->total_price) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr><td colspan="3" class="text-end">Subtotal</td><td class="text-end"><?= \formatPrice($order->subtotal) ?></td></tr>
                            <tr><td colspan="3" class="text-end">Tax</td><td class="text-end"><?= \formatPrice($order->tax_amount) ?></td></tr>
                            <tr><td colspan="3" class="text-end">Delivery</td><td class="text-end"><?= \formatPrice($order->delivery_fee) ?></td></tr>
                            <tr><td colspan="3" class="text-end">Service</td><td class="text-end"><?= \formatPrice($order->service_charge) ?></td></tr>
                            <?php if ($order->discount_amount > 0): ?><tr><td colspan="3" class="text-end">Discount</td><td class="text-end text-danger">-<?= \formatPrice($order->discount_amount) ?></td></tr><?php endif; ?>
                            <tr class="fw-bold"><td colspan="3" class="text-end">Total</td><td class="text-end"><?= \formatPrice($order->total_amount) ?></td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white"><h6 class="mb-0">Tracking History</h6></div>
            <div class="card-body">
                <?php foreach ($tracking as $t): ?>
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <div><strong class="text-capitalize"><?= str_replace('_', ' ', $t->status) ?></strong><div class="small text-muted"><?= \escape($t->description) ?></div></div>
                        <small class="text-muted"><?= \formatDateTime($t->created_at) ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">Update Status</h6></div>
            <div class="card-body">
                <form id="orderStatusForm" data-order-id="<?= $order->id ?>">
                    <?= \csrfField() ?>
                    <select name="status" class="form-select mb-2">
                        <?php foreach ($statuses as $s): ?>
                            <option value="<?= $s ?>" <?= $order->status === $s ? 'selected' : '' ?>><?= ucfirst(str_replace('_', ' ', $s)) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <textarea name="notes" class="form-control mb-2" rows="2" placeholder="Notes (optional)"></textarea>
                    <button type="submit" class="btn btn-gold w-100">Update</button>
                </form>
                <hr>
                <dl class="small mb-0">
                    <dt>Customer</dt><dd><?= \escape($order->customer_name ?? 'Guest') ?></dd>
                    <dt>Type</dt><dd><?= ucfirst(str_replace('_', ' ', $order->order_type)) ?></dd>
                    <dt>Payment</dt><dd><?= ucwords(str_replace('_', ' ', $order->payment_method)) ?> (<?= ucfirst($order->payment_status) ?>)</dd>
                    <?php if ($order->delivery_address): ?><dt>Address</dt><dd><?= \escape($order->delivery_address) ?>, <?= \escape($order->delivery_city ?? '') ?></dd><?php endif; ?>
                    <?php if ($order->special_notes): ?><dt>Notes</dt><dd><?= \escape($order->special_notes) ?></dd><?php endif; ?>
                </dl>
            </div>
        </div>
    </div>
</div>
