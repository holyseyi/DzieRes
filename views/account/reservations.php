<?php
/**
 * Account Reservations View
 *
 * @var array $reservations
 */
?>
<section class="page-hero" style="min-height:auto;padding:70px 0;"><div class="container"><h1 class="page-title">My Reservations</h1></div></section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3"><?php \partial('account-sidebar', ['user' => \auth()]); ?></div>
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Upcoming & Past</h5>
                    <a href="<?= \baseUrl('reservations') ?>" class="btn btn-gold">New Reservation</a>
                </div>
                <?php if (!empty($reservations)): ?>
                    <div class="row g-3">
                        <?php foreach ($reservations as $r): ?>
                            <div class="col-md-6">
                                <div class="glass-card p-3">
                                    <div class="d-flex justify-content-between">
                                        <strong>#<?= \escape($r->reservation_number) ?></strong>
                                        <span class="badge bg-warning text-dark"><?= ucfirst($r->status) ?></span>
                                    </div>
                                    <div class="small text-muted mt-2">
                                        <div><?= \icon('calendar', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?><?= \formatDate($r->reservation_date) ?> at <?= \formatTime($r->reservation_time) ?></div>
                                        <div><?= \icon('user-plus', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?><?= $r->number_of_guests ?> guests</div>
                                        <?php if ($r->table_number): ?><div><?= \icon('chair', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>Table <?= \escape($r->table_number) ?></div><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="glass-card p-4 text-center text-muted">No reservations yet.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
