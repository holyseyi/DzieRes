<?php
/**
 * Partial: Flash Messages (toasts)
 * Reusable across all pages.
 */
?>
<?php if (isset($_SESSION['_flash'])): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;">
        <?php foreach ($_SESSION['_flash'] as $type => $message): ?>
            <?php if (in_array($type, ['success', 'error', 'info', 'warning'])): ?>
                <?php
                $bg = match ($type) {
                    'success' => 'text-bg-success',
                    'error' => 'text-bg-danger',
                    'warning' => 'text-bg-warning',
                    'info' => 'text-bg-info',
                    default => 'text-bg-secondary'
                };
                $icon = match ($type) {
                    'success' => 'fa-check-circle',
                    'error' => 'fa-exclamation-circle',
                    'warning' => 'fa-exclamation-triangle',
                    'info' => 'fa-info-circle',
                    default => 'fa-circle-info'
                };
                ?>
                <div class="toast show align-items-center border-0 <?= $bg ?>" role="alert" data-bs-delay="5000">
                    <div class="d-flex">
                        <div class="toast-body"><i class="fas <?= $icon ?> me-2"></i><?= \escape($message) ?></div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
