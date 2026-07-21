<?php
/**
 * Admin: Employees Index
 *
 * @var array $employees
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Employees</h4>
    <a href="<?= \baseUrl('admin/employees/create') ?>" class="btn btn-gold"><i class="fas fa-plus me-1"></i>Add Employee</a>
</div>

<div class="row g-3">
    <?php foreach ($employees as $e): ?>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div><h6 class="mb-0"><?= \escape(($e->firstname ?? '') . ' ' . ($e->lastname ?? '')) ?></h6><small class="text-muted"><?= \escape($e->employee_code) ?></small></div>
                        <span class="badge bg-<?= \matchStatusColor($e->status) ?>"><?= ucfirst($e->status) ?></span>
                    </div>
                    <p class="mb-1 mt-2"><span class="badge bg-gold"><?= ucfirst(str_replace('_', ' ', $e->position)) ?></span></p>
                    <small class="text-muted"><?= \escape($e->department ?? '') ?></small>
                    <div class="mt-2">
                        <a href="<?= \baseUrl('admin/employees/' . $e->id . '/edit') ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <button class="btn btn-sm btn-outline-danger emp-delete" data-id="<?= $e->id ?>">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($employees)): ?><div class="col-12 text-center text-muted py-4">No employees yet.</div><?php endif; ?>
</div>
