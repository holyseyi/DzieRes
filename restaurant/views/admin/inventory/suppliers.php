<?php
/**
 * Admin: Inventory Suppliers
 *
 * @var array $suppliers
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Suppliers</h4>
    <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#supModal"><?= \icon('plus', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i>Add Supplier</button>
</div>

<div class="row g-3">
    <?php foreach ($suppliers as $s): ?>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between"><h6 class="mb-0"><?= \escape($s->name) ?></h6><span class="badge bg-<?= \matchStatusColor($s->status) ?>"><?= ucfirst($s->status) ?></span></div>
                    <p class="small text-muted mb-1"><?= \escape($s->contact_person ?? '') ?></p>
                    <p class="small mb-1"><?= \icon('phone', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i><?= \escape($s->phone ?? '') ?></p>
                    <p class="small mb-0"><?= \icon('message', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i><?= \escape($s->email ?? '') ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($suppliers)): ?><div class="col-12 text-center text-muted py-4">No suppliers.</div><?php endif; ?>
</div>

<div class="modal fade" id="supModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= \baseUrl('admin/inventory/suppliers/store') ?>">
                <?= \csrfField() ?>
                <div class="modal-header"><h5 class="modal-title">Add Supplier</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" class="form-control" required></div>
                    <div class="row g-2">
                        <div class="col"><label class="form-label">Contact Person</label><input type="text" name="contact_person" class="form-control"></div>
                        <div class="col"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control"></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Payment Terms</label><input type="text" name="payment_terms" class="form-control" placeholder="Net 30"></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-gold">Save</button></div>
            </form>
        </div>
    </div>
</div>
