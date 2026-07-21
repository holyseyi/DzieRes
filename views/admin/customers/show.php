<?php
/**
 * Admin: Customer Detail
 *
 * @var object $customer
 * @var array $orders
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><a href="<?= \baseUrl('admin/customers') ?>" class="text-muted small"><?= \icon('arrow-left', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i>Back</a><h4 class="mb-0 mt-1"><?= \escape($customer->firstname . ' ' . $customer->lastname) ?></h4></div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <dl class="mb-0">
                    <dt>Email</dt><dd><?= \escape($customer->email) ?></dd>
                    <dt>Phone</dt><dd><?= \escape($customer->phone ?? '—') ?></dd>
                    <dt>Address</dt><dd><?= \escape($customer->address ?? '—') ?></dd>
                    <dt>City</dt><dd><?= \escape($customer->city ?? '—') ?></dd>
                    <dt>Status</dt><dd><span class="badge bg-<?= \matchStatusColor($customer->status) ?>"><?= ucfirst($customer->status) ?></span></dd>
                    <dt>Joined</dt><dd><?= \formatDate($customer->created_at) ?></dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">Order History</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light"><tr><th>Order</th><th>Date</th><th>Total</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php foreach ($orders as $o): ?>
                                <tr><td>#<?= \escape($o->order_number) ?></td><td><?= \formatDate($o->created_at) ?></td><td><?= \formatPrice($o->total_amount) ?></td><td><span class="badge bg-<?= \matchStatusColor($o->status) ?>"><?= ucfirst(str_replace('_',' ',$o->status)) ?></span></td></tr>
                            <?php endforeach; ?>
                            <?php if (empty($orders)): ?><tr><td colspan="4" class="text-center text-muted py-3">No orders.</td></tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
