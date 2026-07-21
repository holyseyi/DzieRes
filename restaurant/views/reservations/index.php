<?php
/**
 * Reservations View
 *
 * @var array $tables
 * @var string $today
 * @var object|null $existing
 */
$old = \sessionFlash('old') ?? [];
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">Book Your Experience</p>
            <h1 class="page-title">Reserve a Table</h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-8">
                <div class="glass-card p-4 p-md-5" data-aos="fade-up">
                    <?php $errors = \sessionFlash('errors') ?? []; ?>
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger"><ul class="mb-0">
                            <?php foreach ($errors as $fe): ?><?php foreach ($fe as $e): ?><li><?= \escape($e) ?></li><?php endforeach; ?><?php endforeach; ?>
                        </ul></div>
                    <?php endif; ?>

                    <form id="reservationForm" method="POST" action="<?= \baseUrl('reservations/book') ?>">
                        <?= \csrfField() ?>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" required value="<?= \escape($old['name'] ?? ($existing->guest_name ?? \auth()->firstname ?? '')) ?>"></div>
                            <div class="col-md-6"><label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required value="<?= \escape($old['email'] ?? (\auth() ? \auth()->email : '')) ?>"></div>
                            <div class="col-md-6"><label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="<?= \escape($old['phone'] ?? (\auth() ? \auth()->phone : '')) ?>"></div>
                            <div class="col-md-3"><label class="form-label">Date</label>
                                <input type="date" name="date" class="form-control" required min="<?= $today ?>" value="<?= \escape($old['date'] ?? $today) ?>"></div>
                            <div class="col-md-3"><label class="form-label">Time</label>
                                <input type="time" name="time" class="form-control" required value="<?= \escape($old['time'] ?? '19:00') ?>"></div>
                            <div class="col-md-4"><label class="form-label">Guests</label>
                                <select name="guests" class="form-select" id="guestCount" required>
                                    <?php for ($i = 1; $i <= 20; $i++): ?><option value="<?= $i ?>" <?= ($old['guests'] ?? 2) == $i ? 'selected' : '' ?>><?= $i ?> <?= $i === 1 ? 'Guest' : 'Guests' ?></option><?php endfor; ?>
                                </select></div>
                            <div class="col-md-8"><label class="form-label">Preferred Table</label>
                                <select name="table_id" class="form-select" id="tableSelect">
                                    <option value="">No preference</option>
                                    <?php foreach ($tables as $t): ?>
                                        <option value="<?= $t->id ?>" data-cap="<?= $t->capacity ?>"><?= \escape($t->table_number) ?> (<?= $t->capacity ?> seats - <?= ucfirst($t->location) ?>)</option>
                                    <?php endforeach; ?>
                                </select></div>
                            <div class="col-md-6"><label class="form-label">Occasion</label>
                                <select name="occasion" class="form-select">
                                    <option value="">None</option>
                                    <option value="birthday">Birthday</option>
                                    <option value="anniversary">Anniversary</option>
                                    <option value="business">Business</option>
                                    <option value="other">Other</option>
                                </select></div>
                            <div class="col-12"><label class="form-label">Special Requests</label>
                                <textarea name="requests" class="form-control" rows="3" placeholder="Allergies, decorations, seating preferences..."><?= \escape($old['requests'] ?? '') ?></textarea></div>
                        </div>
                        <button type="submit" class="btn btn-gold btn-lg w-100 mt-3">Request Reservation</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="glass-card p-4">
                    <h5 class="mb-3">Reservation Info</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3"><?= \icon('clock', ['class=" text-gold" style="width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;"']) ?>></i><?= \escape(\getSetting('opening_hours', 'Open Daily 7:00 AM - 11:00 PM')) ?></li>
                        <li class="mb-3"><?= \icon('phone', ['class=" text-gold" style="width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;"']) ?>></i><?= \escape(\getSetting('restaurant_phone', '+233 50 000 0000')) ?></li>
                        <li class="mb-3"><?= \icon('map-marker', ['class=" text-gold" style="width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;"']) ?>></i><?= \escape(\getSetting('restaurant_address', '123 Independence Avenue, Accra')) ?></li>
                        <li><?= \icon('info', ['class=" text-gold" style="width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;"']) ?>></i>Confirmation sent by email</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
