<?php
/**
 * Admin: Backups
 *
 * @var array $backups
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Backups</h4>
    <form method="POST" action="<?= \baseUrl('admin/backups/create') ?>" class="d-inline">
        <?= \csrfField() ?>
        <button class="btn btn-gold"><i class="fas fa-download me-1"></i>Create Backup</button>
    </form>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Filename</th><th>Size</th><th>Type</th><th>Created</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($backups as $b): ?>
                        <tr>
                            <td><?= \escape($b->filename) ?></td>
                            <td><?= \number_format($b->filesize / 1024, 1) ?> KB</td>
                            <td><span class="badge bg-light text-dark"><?= ucfirst($b->type) ?></span></td>
                            <td><small class="text-muted"><?= \formatDateTime($b->created_at) ?></small></td>
                            <td>
                                <form method="POST" action="<?= \baseUrl('admin/backups/' . $b->id . '/restore') ?>" class="d-inline"><button class="btn btn-sm btn-outline-gold">Restore</button></form>
                                <form method="POST" action="<?= \baseUrl('admin/backups/' . $b->id . '/delete') ?>" class="d-inline"><button class="btn btn-sm btn-outline-danger">Delete</button></form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($backups)): ?><tr><td colspan="5" class="text-center text-muted py-4">No backups yet. Click "Create Backup" to generate one.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
