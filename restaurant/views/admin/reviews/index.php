<?php
/**
 * Admin: Reviews (Moderation)
 *
 * @var array $reviews
 * @var string $currentStatus
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Customer Reviews</h4>
</div>

<form method="GET" class="mb-3">
    <select name="status" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
        <option value="">All Statuses</option>
        <?php foreach (['pending','approved','rejected'] as $s): ?>
            <option value="<?= $s ?>" <?= $currentStatus === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
        <?php endforeach; ?>
    </select>
</form>

<div class="row g-3">
    <?php foreach ($reviews as $r): ?>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <strong><?= \escape($r->customer_name ?? $r->guest_name ?? 'Guest') ?></strong>
                        <span class="badge bg-<?= \matchStatusColor($r->status) ?>"><?= ucfirst($r->status) ?></span>
                    </div>
                    <div class="text-warning my-1"><?= str_repeat('★', $r->rating) ?><?= str_repeat('☆', 5 - $r->rating) ?></div>
                    <?php if ($r->title): ?><div class="fw-semibold small"><?= \escape($r->title) ?></div><?php endif; ?>
                    <p class="text-muted small mb-2"><?= \escape($r->comment) ?></p>
                    <?php if ($r->staff_reply): ?><div class="small border-start border-gold ps-2 mb-2"><strong>Reply:</strong> <?= \escape($r->staff_reply) ?></div><?php endif; ?>
                    <div class="btn-group btn-group-sm">
                        <?php if ($r->status !== 'approved'): ?><button class="btn btn-outline-success review-approve" data-id="<?= $r->id ?>">Approve</button><?php endif; ?>
                        <?php if ($r->status !== 'rejected'): ?><button class="btn btn-outline-danger review-reject" data-id="<?= $r->id ?>">Reject</button><?php endif; ?>
                        <button class="btn btn-outline-secondary review-reply" data-id="<?= $r->id ?>">Reply</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($reviews)): ?><div class="col-12 text-center text-muted py-4">No reviews.</div><?php endif; ?>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="replyForm">
                <?= \csrfField() ?>
                <div class="modal-header"><h5 class="modal-title">Reply to Review</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body"><input type="hidden" name="review_id" id="replyReviewId"><textarea name="reply" class="form-control" rows="4" placeholder="Management reply..."></textarea></div>
                <div class="modal-footer"><button type="submit" class="btn btn-gold">Send Reply</button></div>
            </form>
        </div>
    </div>
</div>
