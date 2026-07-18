<?php
/**
 * Admin: Customers Index
 *
 * @var array $customers
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Customers</h4>
</div>

<form method="GET" class="mb-3">
    <div class="row g-2">
        <div class="col-auto"><input type="text" name="search" class="form-control" placeholder="Search name/email" value="<?= \escape($_GET['search'] ?? '') ?>"></div>
        <div class="col-auto"><button class="btn btn-gold">Search</button></div>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Name</th><th>Email</th><th>Phone</th><th>Orders</th><th>Spent</th><th>Joined</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($customers as $c): ?>
                        <tr>
                            <td><strong><?= \escape($c->firstname . ' ' . $c->lastname) ?></strong></td>
                            <td><?= \escape($c->email) ?></td>
                            <td><?= \escape($c->phone ?? '—') ?></td>
                            <td><?= $c->order_count ?></td>
                            <td><?= \formatPrice($c->total_spent) ?></td>
                            <td><small class="text-muted"><?= \formatDate($c->created_at) ?></small></td>
                            <td><a href="<?= \baseUrl('admin/customers/' . $c->id) ?>" class="btn btn-sm btn-outline-gold">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($customers)): ?><tr><td colspan="7" class="text-center text-muted py-4">No customers.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
