<?php
/**
 * Admin: Tables (Floor Management)
 *
 * @var array $tables
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Tables & Floor Plan</h4>
    <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#tableModal"><?= \icon('plus', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>Add Table</button>
</div>

<div class="row g-3 mb-4">
    <?php foreach ($tables as $t): ?>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="table-card-admin status-<?= $t->status ?>" data-id="<?= $t->id ?>">
                <div class="table-no">T<?= \escape($t->table_number) ?></div>
                <div class="table-cap"><?= $t->capacity ?> seats</div>
                <span class="badge bg-light text-dark table-status-badge"><?= ucfirst($t->status) ?></span>
                <div class="d-flex gap-2 mt-2">
                    <div class="dropdown flex-grow-1">
                        <button class="btn btn-sm btn-outline-light dropdown-toggle w-100" data-bs-toggle="dropdown">Set Status</button>
                        <ul class="dropdown-menu">
                            <?php foreach (['available','reserved','occupied','cleaning','maintenance'] as $st): ?>
                                <li><a class="dropdown-item table-status" href="#" data-id="<?= $t->id ?>" data-status="<?= $st ?>"><?= ucfirst($st) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <button class="btn btn-sm btn-outline-danger table-del" href="#" data-id="<?= $t->id ?>" title="Delete table">
                        <?= \icon('trash', []) ?>
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($tables)): ?><div class="col-12 text-center text-muted py-4">No tables.</div><?php endif; ?>
</div>

<div class="modal fade" id="tableModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="tableForm">
                <?= \csrfField() ?>
                <div class="modal-header"><h5 class="modal-title">Add Table</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col"><label class="form-label">Table #</label><input type="text" name="table_number" class="form-control" required></div>
                        <div class="col"><label class="form-label">Capacity</label><input type="number" name="capacity" class="form-control" value="2" required></div>
                        <div class="col"><label class="form-label">Min</label><input type="number" name="min_capacity" class="form-control" value="1"></div>
                    </div>
                    <div class="mb-3 mt-2"><label class="form-label">Location</label><select name="location" class="form-select"><option value="indoor">Indoor</option><option value="outdoor">Outdoor</option><option value="vip">VIP</option><option value="bar">Bar</option></select></div>
                    <div class="mb-3"><label class="form-label">Description</label><input type="text" name="description" class="form-control"></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-gold">Save</button></div>
            </form>
        </div>
    </div>
</div>
