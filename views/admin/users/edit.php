<?php
/**
 * Admin: User Edit
 *
 * @var object $user
 * @var array $roles
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><a href="<?= \baseUrl('admin/users') ?>" class="text-muted small"><?= \icon('arrow-left', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i>Back</a><h4 class="mb-0 mt-1">Edit User</h4></div>
</div>
<div class="card border-0 shadow-sm"><div class="card-body">
<form method="POST" action="<?= \baseUrl('admin/users/' . $user->id . '/update') ?>">
    <?= \csrfField() ?>
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">First Name</label><input type="text" name="firstname" class="form-control" value="<?= \escape($user->firstname) ?>" required></div>
        <div class="col-md-6"><label class="form-label">Last Name</label><input type="text" name="lastname" class="form-control" value="<?= \escape($user->lastname) ?>" required></div>
        <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= \escape($user->email) ?>" required></div>
        <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="<?= \escape($user->phone ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">Role</label><select name="role_id" class="form-select"><?php foreach ($roles as $r): ?><option value="<?= $r->id ?>" <?= $user->role_id==$r->id?'selected':'' ?>><?= \escape($r->name) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-4"><label class="form-label">New Password</label><input type="password" name="password" class="form-control" placeholder="Leave blank to keep"></div>
        <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" <?= $user->status==='active'?'selected':'' ?>>Active</option><option value="inactive" <?= $user->status==='inactive'?'selected':'' ?>>Inactive</option><option value="banned" <?= $user->status==='banned'?'selected':'' ?>>Banned</option></select></div>
    </div>
    <button class="btn btn-gold btn-lg mt-3">Update User</button>
</form>
</div></div>
