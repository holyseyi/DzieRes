<?php
/**
 * Admin: Inventory Index (Ingredients)
 *
 * @var array $ingredients
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Inventory</h4>
    <div>
        <a href="<?= \baseUrl('admin/inventory/suppliers') ?>" class="btn btn-outline-gold me-2">Suppliers</a>
        <a href="<?= \baseUrl('admin/inventory/ingredients') ?>" class="btn btn-gold"><i class="fas fa-plus me-1"></i>Manage Ingredients</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Ingredient</th><th>Category</th><th>Stock</th><th>Min</th><th>Unit Price</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($ingredients as $i): ?>
                        <tr>
                            <td><strong><?= \escape($i->name) ?></strong></td>
                            <td><?= \escape($i->category ?? '—') ?></td>
                            <td><?= $i->stock_quantity ?> <?= $i->unit ?></td>
                            <td><?= $i->minimum_stock ?></td>
                            <td><?= \formatPrice($i->unit_price) ?></td>
                            <td>
                                <?php if ($i->stock_quantity <= $i->minimum_stock): ?>
                                    <span class="badge bg-danger">Low Stock</span>
                                <?php else: ?>
                                    <span class="badge bg-success">OK</span>
                                <?php endif; ?>
                                <?php if ($i->expiry_date && $i->expiry_date < date('Y-m-d')): ?><span class="badge bg-warning">Expired</span><?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($ingredients)): ?><tr><td colspan="6" class="text-center text-muted py-4">No ingredients. <a href="<?= \baseUrl('admin/inventory/ingredients') ?>">Add one</a>.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
