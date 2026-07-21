<?php
/**
 * Admin: Reservation Detail
 *
 * @var object $reservation
 * @var array $tables
 */
$statuses = ['pending', 'confirmed', 'seated', 'completed', 'cancelled', 'no_show'];
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?= \baseUrl('admin/reservations') ?>" class="text-muted small"><?= \icon('arrow-left', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i>Back</a>
        <h4 class="mb-0 mt-1">Reservation #<?= \escape($reservation->reservation_number) ?></h4>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Customer</dt><dd class="col-sm-8"><?= \escape($reservation->customer_name ?? $reservation->guest_name) ?></dd>
                    <dt class="col-sm-4">Email</dt><dd class="col-sm-8"><?= \escape($reservation->guest_email) ?></dd>
                    <dt class="col-sm-4">Phone</dt><dd class="col-sm-8"><?= \escape($reservation->guest_phone) ?></dd>
                    <dt class="col-sm-4">Date</dt><dd class="col-sm-8"><?= \formatDate($reservation->reservation_date) ?></dd>
                    <dt class="col-sm-4">Time</dt><dd class="col-sm-8"><?= \formatTime($reservation->reservation_time) ?></dd>
                    <dt class="col-sm-4">Guests</dt><dd class="col-sm-8"><?= $reservation->number_of_guests ?></dd>
                    <dt class="col-sm-4">Occasion</dt><dd class="col-sm-8"><?= $reservation->occasion ? \escape(ucfirst($reservation->occasion)) : '—' ?></dd>
                    <dt class="col-sm-4">Requests</dt><dd class="col-sm-8"><?= $reservation->special_requests ? \escape($reservation->special_requests) : '—' ?></dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">Manage</h6></div>
            <div class="card-body">
                <form id="resvStatusForm" data-id="<?= $reservation->id ?>">
                    <?= \csrfField() ?>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select mb-3">
                        <?php foreach ($statuses as $s): ?>
                            <option value="<?= $s ?>" <?= $reservation->status === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-gold w-100 mb-3">Update Status</button>
                </form>
                <hr>
                <form id="assignTableForm" data-id="<?= $reservation->id ?>">
                    <?= \csrfField() ?>
                    <label class="form-label">Assign Table</label>
                    <select name="table_id" class="form-select mb-3">
                        <option value="">None</option>
                        <?php foreach ($tables as $t): ?>
                            <option value="<?= $t->id ?>" <?= ($reservation->table_id ?? 0) == $t->id ? 'selected' : '' ?>>T<?= \escape($t->table_number) ?> (<?= $t->capacity ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-outline-gold w-100">Assign Table</button>
                </form>
            </div>
        </div>
    </div>
</div>
