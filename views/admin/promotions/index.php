<?php
/**
 * Admin: Promotions
 *
 * @var array $promotions
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Promotions</h4>
    <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#promoModal"><?= \icon('plus', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i>Add Promotion</button>
</div>

<div class="row g-3">
    <?php foreach ($promotions as $p): ?>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between"><h6 class="mb-0"><?= \escape($p->title) ?></h6><span class="badge bg-<?= \matchStatusColor($p->status) ?>"><?= ucfirst($p->status) ?></span></div>
                    <p class="small text-muted mt-2 mb-2"><?= \escape($p->description ?? '') ?></p>
                    <?php if ($p->discount_percent): ?><span class="badge bg-gold"><?= $p->discount_percent ?>% off</span><?php endif; ?>
                    <div class="mt-2"><button class="btn btn-sm btn-outline-danger promo-del" data-id="<?= $p->id ?>"><?= \icon('trash', []) ?>></i> Delete</button></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($promotions)): ?><div class="col-12 text-center text-muted py-4">No promotions.</div><?php endif; ?>
</div>

<div class="modal fade" id="promoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= \baseUrl('admin/promotions/store') ?>">
                <?= \csrfField() ?>
                <div class="modal-header"><h5 class="modal-title">Add Promotion</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Title</label><input type="text" name="title" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                    <div class="row g-2">
                        <div class="col"><label class="form-label">Type</label><select name="type" class="form-select"><option value="discount">Discount</option><option value="bogo">BOGO</option><option value="free_item">Free Item</option><option value="combo">Combo</option></select></div>
                        <div class="col"><label class="form-label">Discount %</label><input type="number" step="0.01" name="discount_percent" class="form-control" value="0"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-gold">Save</button></div>
            </form>
        </div>
    </div>
</div>
