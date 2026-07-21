<?php
/**
 * Admin: Inventory Ingredients (manage + add stock)
 *
 * @var array $ingredients
 * @var array $suppliers
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Ingredients</h4>
    <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#ingModal"><i class="fas fa-plus me-1"></i>Add Ingredient</button>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white"><h6 class="mb-0"><i class="fas fa-plus-circle me-2 text-gold"></i>Add Stock / Purchase</h6></div>
    <div class="card-body">
        <form method="POST" action="<?= \baseUrl('admin/inventory/stock/add') ?>" class="row g-2 align-items-end">
            <?= \csrfField() ?>
            <div class="col-md"><label class="form-label">Ingredient</label><select name="ingredient_id" class="form-select" required><?php foreach ($ingredients as $i): ?><option value="<?= $i->id ?>"><?= \escape($i->name) ?></option><?php endforeach; ?></select></div>
            <div class="col-md"><label class="form-label">Quantity</label><input type="number" step="0.01" name="quantity" class="form-control" required></div>
            <div class="col-md"><label class="form-label">Unit Price</label><input type="number" step="0.01" name="unit_price" class="form-control" required></div>
            <div class="col-md"><label class="form-label">Supplier</label><select name="supplier_id" class="form-select"><?php foreach ($suppliers as $s): ?><option value="<?= $s->id ?>"><?= \escape($s->name) ?></option><?php endforeach; ?></select></div>
            <div class="col-md"><label class="form-label">Purchase Date</label><input type="date" name="purchase_date" class="form-control" value="<?= date('Y-m-d') ?>"></div>
            <div class="col-md-auto"><button class="btn btn-gold w-100">Add Stock</button></div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Name</th><th>Unit</th><th>Stock</th><th>Min</th><th>Used In</th></tr></thead>
                <tbody>
                    <?php foreach ($ingredients as $i): ?>
                        <tr><td><strong><?= \escape($i->name) ?></strong></td><td><?= $i->unit ?></td><td><?= $i->stock_quantity ?></td><td><?= $i->minimum_stock ?></td><td><?= $i->usage_count ?? 0 ?> recipes</td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="ingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= \baseUrl('admin/inventory/ingredients/store') ?>">
                <?= \csrfField() ?>
                <div class="modal-header"><h5 class="modal-title">Add Ingredient</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Category</label><input type="text" name="category" class="form-control"></div>
                    <div class="row g-2">
                        <div class="col"><label class="form-label">Unit</label><select name="unit" class="form-select"><?php foreach (['kg','g','l','ml','pcs','dozen','pack','box','bag'] as $u): ?><option value="<?= $u ?>"><?= $u ?></option><?php endforeach; ?></select></div>
                        <div class="col"><label class="form-label">Unit Price</label><input type="number" step="0.01" name="unit_price" class="form-control"></div>
                        <div class="col"><label class="form-label">Stock</label><input type="number" step="0.01" name="stock_quantity" class="form-control" value="0"></div>
                        <div class="col"><label class="form-label">Min Stock</label><input type="number" step="0.01" name="minimum_stock" class="form-control" value="10"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-gold">Save</button></div>
            </form>
        </div>
    </div>
</div>
