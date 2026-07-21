<?php
/**
 * Admin: Settings
 *
 * @var array $grouped
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Settings</h4>
    <button class="btn btn-gold" id="saveSettings"><i class="fas fa-save me-1"></i>Save Settings</button>
</div>

<form id="settingsForm" method="POST" action="<?= \baseUrl('admin/settings/update') ?>">
    <?= \csrfField() ?>
    <?php foreach ($grouped as $group => $items): ?>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white"><h6 class="mb-0 text-capitalize"><i class="fas fa-cog text-gold me-2"></i><?= \escape(str_replace('_', ' ', $group)) ?></h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ($items as $s): ?>
                        <div class="col-md-6">
                            <label class="form-label"><?= \escape(ucwords(str_replace('_', ' ', $s->key))); ?></label>
                            <?php if ($s->type === 'textarea'): ?>
                                <textarea name="<?= \escape($s->key) ?>" class="form-control" rows="2"><?= \escape($s->value) ?></textarea>
                            <?php elseif ($s->type === 'color'): ?>
                                <input type="color" name="<?= \escape($s->key) ?>" class="form-control form-control-color" value="<?= \escape($s->value) ?>">
                            <?php elseif ($s->type === 'checkbox'): ?>
                                <select name="<?= \escape($s->key) ?>" class="form-select"><option value="1" <?= $s->value=='1'?'selected':'' ?>>Enabled</option><option value="0" <?= $s->value!='1'?'selected':'' ?>>Disabled</option></select>
                            <?php elseif ($s->type === 'image'): ?>
                                <input type="text" name="<?= \escape($s->key) ?>" class="form-control" value="<?= \escape($s->value) ?>">
                            <?php else: ?>
                                <input type="text" name="<?= \escape($s->key) ?>" class="form-control" value="<?= \escape($s->value) ?>">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($grouped)): ?><div class="text-center text-muted py-4">No settings configured. Run the seeder to populate default settings.</div><?php endif; ?>
</form>
