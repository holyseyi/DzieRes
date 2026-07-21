<?php
/**
 * Admin: Users
 *
 * @var array $users
 * @var array $roles
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Users</h4>
    <a href="<?= \baseUrl('admin/users/create') ?>" class="btn btn-gold"><?= \icon('plus', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i>Add User</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Joined</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><strong><?= \escape($u->firstname . ' ' . $u->lastname) ?></strong></td>
                            <td><?= \escape($u->email) ?></td>
                            <td><span class="badge bg-info"><?= \escape($u->role_name ?? '') ?></span></td>
                            <td><span class="badge bg-<?= \matchStatusColor($u->status) ?>"><?= ucfirst($u->status) ?></span></td>
                            <td><small class="text-muted"><?= \formatDate($u->created_at) ?></small></td>
                            <td>
                                <a href="<?= \baseUrl('admin/users/' . $u->id . '/edit') ?>" class="btn btn-sm btn-outline-secondary"><?= \icon('edit', []) ?>></i></a>
                                <button class="btn btn-sm btn-outline-danger user-del" data-id="<?= $u->id ?>"><?= \icon('trash', []) ?>></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
