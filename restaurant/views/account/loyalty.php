<?php
/**
 * Account Loyalty View
 *
 * @var int $points
 * @var array $history
 * @var array $rewards
 */
?>
<section class="page-hero" style="min-height:auto;padding:70px 0;"><div class="container"><h1 class="page-title">Loyalty & Rewards</h1></div></section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3"><?php \partial('account-sidebar', ['user' => \auth()]); ?></div>
            <div class="col-lg-9">
                <div class="loyalty-banner glass-card p-4 mb-4 text-center">
                    <i class="fas fa-gem fa-2x text-gold mb-2"></i>
                    <div class="stat-number text-gold" data-count="<?= $points ?>">0</div>
                    <p class="mb-0 text-muted">Available Loyalty Points</p>
                    <small class="text-muted"><?= \config('loyalty.points_per_ghs', 10) ?> points earned per ₵1 spent</small>
                </div>

                <h5 class="mb-3">Rewards Catalog</h5>
                <div class="row g-3 mb-4">
                    <?php foreach ($rewards as $rw): ?>
                        <div class="col-md-4">
                            <div class="glass-card p-3 text-center h-100">
                                <h6 class="mb-1"><?= \escape($rw->name) ?></h6>
                                <div class="text-gold fw-bold mb-2"><?= $rw->points_required ?> pts</div>
                                <p class="small text-muted mb-2"><?= \escape($rw->description ?? '') ?></p>
                                <button class="btn btn-sm btn-outline-gold" <?= $points >= $rw->points_required ? '' : 'disabled' ?>>Redeem</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($rewards)): ?><div class="col-12 text-muted">No rewards available yet.</div><?php endif; ?>
                </div>

                <h5 class="mb-3">Points History</h5>
                <div class="glass-card p-3">
                    <?php if (!empty($history)): ?>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead><tr><th>Date</th><th>Type</th><th>Points</th><th>Description</th></tr></thead>
                                <tbody>
                                    <?php foreach ($history as $h): ?>
                                        <tr>
                                            <td><?= \formatDate($h->created_at) ?></td>
                                            <td><span class="badge bg-light text-dark"><?= ucfirst($h->type) ?></span></td>
                                            <td class="<?= $h->points >= 0 ? 'text-success' : 'text-danger' ?>"><?= $h->points >= 0 ? '+' : '' ?><?= $h->points ?></td>
                                            <td class="small"><?= \escape($h->description ?? '') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No points activity yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
