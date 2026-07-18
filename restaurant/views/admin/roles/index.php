<?php
/**
 * Admin: Roles & Permissions (RBAC)
 *
 * @var array $roles
 * @var array $permissions
 * @var array $rolePermissions
 */
// Group permissions by module
$modules = [];
foreach ($permissions as $p) {
    $modules[$p->module ?? 'general'][] = $p;
}
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Roles & Permissions</h4>
    <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#roleModal"><i class="fas fa-plus me-1"></i>Add Role</button>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white"><h6 class="mb-0">Roles</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Role</th><th>Users</th><th>Permissions</th></tr></thead>
                <tbody>
                    <?php foreach ($roles as $role): ?>
                        <tr>
                            <td><strong><?= \escape($role->name) ?></strong><div class="small text-muted"><?= \escape($role->description ?? '') ?></div></td>
                            <td><?= $role->user_count ?></td>
                            <td>
                                <form method="POST" action="<?= \baseUrl('admin/roles/' . $role->id . '/permissions') ?>" class="d-flex flex-wrap gap-1">
                                    <?= \csrfField() ?>
                                    <?php foreach ($permissions as $p): ?>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="<?= $p->id ?>" id="rp<?= $role->id ?>_<?= $p->id ?>" <?= in_array($p->id, ($rolePermissions[$role->id] ?? [])) ? 'checked' : '' ?>>
                                            <label class="form-check-label small" for="rp<?= $role->id ?>_<?= $p->id ?>"><?= \escape($p->name) ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                    <button class="btn btn-sm btn-gold">Save</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= \baseUrl('admin/roles/store') ?>">
                <?= \csrfField() ?>
                <div class="modal-header"><h5 class="modal-title">Add Role</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Slug</label><input type="text" name="slug" class="form-control" placeholder="auto"></div>
                    <div class="mb-3"><label class="form-label">Description</label><input type="text" name="description" class="form-control"></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-gold">Save</button></div>
            </form>
        </div>
    </div>
</div>
