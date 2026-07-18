<?php
/**
 * Reservation Confirmation View
 *
 * @var object $reservation
 */
?>
<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center mb-4">
                    <div class="success-check"><i class="fas fa-check"></i></div>
                    <h2 class="mt-3">Reservation Requested!</h2>
                    <p class="text-muted">Reference: <strong>#<?= \escape($reservation->reservation_number) ?></strong></p>
                </div>

                <div class="glass-card p-4">
                    <div class="row g-3">
                        <div class="col-6"><strong>Name</strong><div class="text-muted"><?= \escape($reservation->guest_name) ?></div></div>
                        <div class="col-6"><strong>Date</strong><div class="text-muted"><?= \formatDate($reservation->reservation_date) ?></div></div>
                        <div class="col-6"><strong>Time</strong><div class="text-muted"><?= \formatTime($reservation->reservation_time) ?></div></div>
                        <div class="col-6"><strong>Guests</strong><div class="text-muted"><?= $reservation->number_of_guests ?></div></div>
                        <div class="col-6"><strong>Table</strong><div class="text-muted"><?= \escape($reservation->table_number ?? 'To be assigned') ?></div></div>
                        <div class="col-6"><strong>Status</strong><div><span class="badge bg-warning text-dark"><?= ucfirst($reservation->status) ?></span></div></div>
                        <?php if ($reservation->occasion): ?>
                            <div class="col-6"><strong>Occasion</strong><div class="text-muted"><?= \escape(ucfirst($reservation->occasion)) ?></div></div>
                        <?php endif; ?>
                    </div>
                    <?php if ($reservation->special_requests): ?>
                        <hr><strong>Special Requests</strong><p class="text-muted mb-0"><?= \escape($reservation->special_requests) ?></p>
                    <?php endif; ?>
                </div>

                <div class="text-center mt-4">
                    <a href="<?= \baseUrl() ?>" class="btn btn-outline-gold">Back to Home</a>
                    <?php if (\auth()): ?><a href="<?= \baseUrl('account/reservations') ?>" class="btn btn-gold">My Reservations</a><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
