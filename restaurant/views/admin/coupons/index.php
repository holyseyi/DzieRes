<?php
/**
 * Admin: Coupons
 *
 * @var array $coupons
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Coupons</h4>
    <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#couponModal"><i class="fas fa-plus me-1"></i>Add Coupon</button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Code</th><th>Type</th><th>Value</th><th>Min Order</th><th>Used</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($coupons as $c): ?>
                        <tr>
                            <td><strong><?= \escape($c->code) ?></strong></td>
                            <td><?= ucfirst($c->type) ?></td>
                            <td><?= $c->type==='percentage' ? $c->value.'%' : \formatPrice($c->value) ?></td>
                            <td><?= \formatPrice($c->min_order_amount) ?></td>
                            <td><?= $c->used_count ?>/<?= $c->usage_limit ?></td>
                            <td><span class="badge bg-<?= \matchStatusColor($c->status) ?>"><?= ucfirst($c->status) ?></span></td>
                            <td><button class="btn btn-sm btn-outline-danger coupon-del" data-id="<?= $c->id ?>"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($coupons)): ?><tr><td colspan="7" class="text-center text-muted py-4">No coupons.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="couponModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= \baseUrl('admin/coupons/store') ?>">
                <?= \csrfField() ?>
                <div class="modal-header"><h5 class="modal-title">Add Coupon</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col"><label class="form-label">Code</label><input type="text" name="code" class="form-control" required></div>
                        <div class="col"><label class="form-label">Type</label><select name="type" class="form-select"><option value="percentage">Percentage</option><option value="fixed">Fixed</option><option value="free_delivery">Free Delivery</option></select></div>
                        <div class="col"><label class="form-label">Value</label><input type="number" step="0.01" name="value" class="form-control" required></div>
                    </div>
                    <div class="row g-2 mt-1">
                        <div class="col"><label class="form-label">Min Order</label><input type="number" step="0.01" name="min_order_amount" class="form-control" value="0"></div>
                        <div class="col"><label class="form-label">Usage Limit</label><input type="number" name="usage_limit" class="form-control" value="100"></div>
                    </div>
                    <div class="mb-3 mt-2"><label class="form-label">Description</label><input type="text" name="description" class="form-control"></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-gold">Save</button></div>
            </form>
        </div>
    </div>
</div>
