<?php
/**
 * Admin: Employee Create
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><a href="<?= \baseUrl('admin/employees') ?>" class="text-muted small"><?= \icon('arrow-left', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i>Back</a><h4 class="mb-0 mt-1">Add Employee</h4></div>
</div>
<div class="card border-0 shadow-sm"><div class="card-body">
<form method="POST" action="<?= \baseUrl('admin/employees/store') ?>">
    <?= \csrfField() ?>
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Employee Code</label><input type="text" name="employee_code" class="form-control" placeholder="AUTO" value="EMP<?= time() ?>"></div>
        <div class="col-md-6"><label class="form-label">Position</label><select name="position" class="form-select"><?php foreach (['chef','waiter','cashier','manager','admin','cleaner','other'] as $p): ?><option value="<?= $p ?>"><?= ucfirst($p) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-6"><label class="form-label">Department</label><input type="text" name="department" class="form-control"></div>
        <div class="col-md-6"><label class="form-label">Hire Date</label><input type="date" name="hire_date" class="form-control" value="<?= date('Y-m-d') ?>"></div>
        <div class="col-md-6"><label class="form-label">Salary</label><input type="number" step="0.01" name="salary" class="form-control"></div>
        <div class="col-md-6"><label class="form-label">Pay Frequency</label><select name="pay_frequency" class="form-select"><option value="weekly">Weekly</option><option value="biweekly">Biweekly</option><option value="monthly" selected>Monthly</option></select></div>
        <div class="col-md-6"><label class="form-label">Employment Type</label><select name="employment_type" class="form-select"><option value="full_time">Full Time</option><option value="part_time">Part Time</option><option value="contract">Contract</option><option value="intern">Intern</option></select></div>
        <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option><option value="suspended">Suspended</option></select></div>
        <div class="col-md-6"><label class="form-label">Emergency Contact</label><input type="text" name="emergency_contact" class="form-control"></div>
        <div class="col-md-6"><label class="form-label">Emergency Phone</label><input type="text" name="emergency_phone" class="form-control"></div>
    </div>
    <button class="btn btn-gold btn-lg mt-3">Save Employee</button>
</form>
</div></div>
