<?php
/**
 * Admin: Reservations Index
 *
 * @var array $reservations
 * @var array $statuses
 * @var string $currentStatus
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Reservations</h4>
</div>

<form method="GET" class="mb-3">
    <select name="status" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
        <option value="">All Statuses</option>
        <?php foreach ($statuses as $s): ?>
            <option value="<?= $s ?>" <?= $currentStatus === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
        <?php endforeach; ?>
    </select>
</form>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Ref</th><th>Customer</th><th>Date/Time</th><th>Guests</th><th>Table</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($reservations as $r): ?>
                        <tr>
                            <td><a href="<?= \baseUrl('admin/reservations/' . $r->id) ?>" class="fw-semibold text-decoration-none">#<?= \escape($r->reservation_number) ?></a></td>
                            <td><?= \escape($r->customer_name ?? $r->guest_name) ?><div class="small text-muted"><?= \escape($r->guest_phone) ?></div></td>
                            <td><?= \formatDate($r->reservation_date) ?> <?= \formatTime($r->reservation_time) ?></td>
                            <td><?= $r->number_of_guests ?></td>
                            <td><?= \escape($r->table_number ?? '—') ?></td>
                            <td><span class="badge bg-<?= \matchStatusColor($r->status) ?>"><?= ucfirst($r->status) ?></span></td>
                            <td><a href="<?= \baseUrl('admin/reservations/' . $r->id) ?>" class="btn btn-sm btn-outline-gold">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($reservations)): ?><tr><td colspan="7" class="text-center text-muted py-4">No reservations.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
