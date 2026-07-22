<?php
/**
 * Rider Dashboard
 *
 * @var array $orders
 * @var array $statuses
 */
$user = \auth();
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">Delivery Hub</p>
            <h1 class="page-title">Rider Dashboard</h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="glass-card p-4 text-center">
                    <div class="text-gold fw-bold fs-3"><?= count($orders) ?></div>
                    <div class="text-muted">Assigned Orders</div>
                </div>
            </div>
            <div class="col-lg-9">
                <?php if (empty($orders)): ?>
                    <div class="glass-card p-5 text-center">
                        <?= \icon('motorcycle', ['style' => 'width:3em;height:3em;color:#6c757d;']) ?>
                        <h4 class="mt-3">No orders assigned</h4>
                        <p class="text-muted">Check back later for new delivery assignments.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($orders as $order): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="glass-card p-4 h-100">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="mb-1"><?= \escape($order->order_number) ?></h6>
                                            <small class="text-muted"><?= ucfirst(str_replace('_', ' ', $order->order_type)) ?></small>
                                        </div>
                                        <span class="badge bg-<?= $order->status === 'ready' ? 'success' : 'info' ?>">
                                            <?= ucfirst(str_replace('_', ' ', $order->status)) ?>
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="small text-muted mb-1">Customer</div>
                                        <div><?= \escape($order->customer_name ?? 'Guest') ?></div>
                                        <div class="small"><?= \escape($order->delivery_phone ?? $order->guest_phone) ?></div>
                                    </div>

                                    <?php if ($order->delivery_address): ?>
                                        <div class="mb-3">
                                            <div class="small text-muted mb-1">Address</div>
                                            <div class="small"><?= \escape($order->delivery_address) ?>, <?= \escape($order->delivery_city ?? '') ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="mb-3">
                                        <div class="small text-muted mb-1">Items</div>
                                        <div class="small"><?= $order->item_count ?> item(s) - <?= \formatPrice($order->total_amount) ?></div>
                                    </div>

                                    <?php if ($order->special_notes): ?>
                                        <div class="mb-3">
                                            <div class="small text-muted mb-1">Notes</div>
                                            <div class="small text-warning"><?= \escape($order->special_notes) ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="d-grid gap-2 mt-3">
                                        <?php if ($order->status === 'ready' || $order->status === 'assigned'): ?>
                                            <form method="POST" action="<?= \baseUrl('rider/order/' . $order->id . '/accept') ?>">
                                                <?= \csrfField() ?>
                                                <button type="submit" class="btn btn-gold w-100">Accept Order</button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <?php if ($order->status === 'assigned' || $order->status === 'ready'): ?>
                                            <form method="POST" action="<?= \baseUrl('rider/order/' . $order->id . '/pickup') ?>">
                                                <?= \csrfField() ?>
                                                <button type="submit" class="btn btn-outline-gold w-100">Mark Picked Up</button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <?php if ($order->status === 'picked_up' || $order->status === 'assigned'): ?>
                                            <form method="POST" action="<?= \baseUrl('rider/order/' . $order->id . '/deliver') ?>">
                                                <?= \csrfField() ?>
                                                <textarea name="notes" class="form-control mb-2" rows="2" placeholder="Delivery notes (optional)"></textarea>
                                                <button type="submit" class="btn btn-success w-100">Mark Delivered</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
