<?php
/**
 * Admin: Activity Logs
 *
 * @var array $logs
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Activity Logs</h4>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Time</th><th>User</th><th>Module</th><th>Action</th><th>Description</th></tr></thead>
                <tbody>
                    <?php foreach ($logs as $l): ?>
                        <tr>
                            <td><small class="text-muted"><?= \formatDateTime($l->created_at) ?></small></td>
                            <td><?= \escape($l->user_name ?? 'System') ?></td>
                            <td><span class="badge bg-light text-dark"><?= \escape($l->module) ?></span></td>
                            <td><span class="badge bg-<?= \matchStatusColor($l->action) ?>"><?= \escape($l->action) ?></span></td>
                            <td class="small"><?= \escape($l->description) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($logs)): ?><tr><td colspan="5" class="text-center text-muted py-4">No activity logged.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
