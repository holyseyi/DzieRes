<?php
/**
 * Admin: User Create
 *
 * @var array $roles
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><a href="<?= \baseUrl('admin/users') ?>" class="text-muted small"><i class="fas fa-arrow-left me-1"></i>Back</a><h4 class="mb-0 mt-1">Add User</h4></div>
</div>
<div class="card border-0 shadow-sm"><div class="card-body">
<form method="POST" action="<?= \baseUrl('admin/users/store') ?>">
    <?= \csrfField() ?>
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">First Name</label><input type="text" name="firstname" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">Last Name</label><input type="text" name="lastname" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control"></div>
        <div class="col-md-4"><label class="form-label">Role</label><select name="role_id" class="form-select"><?php foreach ($roles as $r): ?><option value="<?= $r->id ?>"><?= \escape($r->name) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-4"><label class="form-label">Password</label><input type="password" name="password" class="form-control" placeholder="default: password123"></div>
        <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
    </div>
    <button class="btn btn-gold btn-lg mt-3">Create User</button>
</form>
</div></div>
