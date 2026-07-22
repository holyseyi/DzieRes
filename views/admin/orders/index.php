<?php
/**
 * Admin: Orders Index
 *
 * @var array $orders
 * @var array $statuses
 * @var string $currentStatus
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Orders</h4>
</div>

<form method="GET" class="mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-auto">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <?php foreach ($statuses as $s): ?>
                    <option value="<?= $s ?>" <?= $currentStatus === $s ? 'selected' : '' ?>><?= ucfirst(str_replace('_', ' ', $s)) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col">
            <input type="text" name="search" class="form-control" placeholder="Search order #, customer, email or phone"
                   value="<?= \escape($search ?? '') ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-gold"><?= \icon('search', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>Search</button>
        </div>
        <div class="col-auto">
            <a href="<?= \baseUrl('admin/orders') ?>" class="btn btn-outline-secondary">Clear</a>
        </div>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th><th>Customer</th><th>Type</th>
                        <th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><a href="<?= \baseUrl('admin/orders/' . $o->id) ?>" class="fw-semibold text-decoration-none">#<?= \escape($o->order_number) ?></a></td>
                            <td><?= \escape($o->customer_name ?? 'Guest') ?></td>
                            <td><?= ucfirst(str_replace('_', ' ', $o->order_type)) ?></td>
                            <td><?= \formatPrice($o->total_amount) ?></td>
                            <td><span class="badge bg-light text-dark"><?= ucwords(str_replace('_', ' ', $o->payment_method)) ?></span></td>
                            <td><span class="badge bg-<?= \matchStatusColor($o->status) ?>"><?= ucfirst(str_replace('_', ' ', $o->status)) ?></span></td>
                            <td><small class="text-muted"><?= \formatDateTime($o->created_at) ?></small></td>
                            <td><a href="<?= \baseUrl('admin/orders/' . $o->id) ?>" class="btn btn-sm btn-outline-gold">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($orders)): ?><tr><td colspan="8" class="text-center text-muted py-4">No orders found.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
