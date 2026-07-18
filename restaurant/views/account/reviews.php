<?php
/**
 * Account Reviews View
 *
 * @var array $reviews
 */
?>
<section class="page-hero" style="min-height:auto;padding:70px 0;"><div class="container"><h1 class="page-title">My Reviews</h1></div></section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3"><?php \partial('account-sidebar', ['user' => \auth()]); ?></div>
            <div class="col-lg-9">
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $r): ?>
                        <div class="glass-card p-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <strong><?= \escape($r->food_name ?? 'Item') ?></strong>
                                <span class="text-warning"><?= str_repeat('★', $r->rating) ?><?= str_repeat('☆', 5 - $r->rating) ?></span>
                            </div>
                            <?php if ($r->title): ?><div class="fw-semibold small"><?= \escape($r->title) ?></div><?php endif; ?>
                            <p class="text-muted small mb-1"><?= \escape($r->comment) ?></p>
                            <span class="badge bg-<?= $r->status === 'approved' ? 'success' : 'warning' ?> small"><?= ucfirst($r->status) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="glass-card p-4 text-center text-muted">You haven't reviewed any dishes yet.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
