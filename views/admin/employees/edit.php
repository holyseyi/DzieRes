<?php
/**
 * Admin: Employee Edit
 *
 * @var object $employee
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><a href="<?= \baseUrl('admin/employees') ?>" class="text-muted small"><?= \icon('arrow-left', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>Back</a><h4 class="mb-0 mt-1">Edit Employee</h4></div>
</div>
<div class="card border-0 shadow-sm"><div class="card-body">
<form method="POST" action="<?= \baseUrl('admin/employees/' . $employee->id . '/update') ?>">
    <?= \csrfField() ?>
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Position</label><select name="position" class="form-select"><?php foreach (['chef','waiter','cashier','manager','admin','cleaner','other'] as $p): ?><option value="<?= $p ?>" <?= $employee->position===$p?'selected':'' ?>><?= ucfirst($p) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-6"><label class="form-label">Department</label><input type="text" name="department" class="form-control" value="<?= \escape($employee->department ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">Salary</label><input type="number" step="0.01" name="salary" class="form-control" value="<?= $employee->salary ?>"></div>
        <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" <?= $employee->status==='active'?'selected':'' ?>>Active</option><option value="inactive" <?= $employee->status==='inactive'?'selected':'' ?>>Inactive</option><option value="suspended" <?= $employee->status==='suspended'?'selected':'' ?>>Suspended</option></select></div>
    </div>
    <button class="btn btn-gold btn-lg mt-3">Update</button>
</form>
</div></div>
