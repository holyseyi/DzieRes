<?php
/**
 * Admin: Categories Index
 *
 * @var array $categories
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Categories</h4>
    <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#catModal"><i class="fas fa-plus me-1"></i>Add Category</button>
</div>

<div class="row g-3">
    <?php foreach ($categories as $c): ?>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0"><i class="fas fa-<?= \escape($c->icon ?? 'utensils') ?> text-gold me-2"></i><?= \escape($c->name) ?></h6>
                        <span class="badge bg-light text-dark"><?= $c->food_count ?? 0 ?> items</span>
                    </div>
                    <p class="small text-muted mt-2 mb-2"><?= \escape($c->description ?? '') ?></p>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary cat-edit" data-id="<?= $c->id ?>" data-name="<?= \escape($c->name) ?>" data-icon="<?= \escape($c->icon ?? '') ?>" data-desc="<?= \escape($c->description ?? '') ?>" data-status="<?= $c->status ?>">Edit</button>
                        <button class="btn btn-outline-danger cat-delete" data-id="<?= $c->id ?>">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="catModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="catForm">
                <?= \csrfField() ?>
                <div class="modal-header"><h5 class="modal-title">Add Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Icon (FontAwesome)</label><input type="text" name="icon" class="form-control" value="utensils" placeholder="utensils"></div>
                    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                    <div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-gold">Save</button></div>
            </form>
        </div>
    </div>
</div>
