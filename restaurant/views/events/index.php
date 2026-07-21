<?php
/**
 * Events Index View
 *
 * @var array $events
 */
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">Join The Experience</p>
            <h1 class="page-title">Events</h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <div class="col-md-6 col-lg-4" data-aos="fade-up">
                        <div class="event-card h-100">
                            <div class="event-image">
                                <img src="<?= \uploadUrl($event->image) ?>" alt="<?= \escape($event->title) ?>" loading="lazy">
                                <span class="event-date-badge">
                                    <strong><?= date('d', strtotime($event->event_date)) ?></strong>
                                    <?= date('M', strtotime($event->event_date)) ?>
                                </span>
                            </div>
                            <div class="event-body">
                                <span class="badge bg-gold mb-2"><?= \escape(ucfirst($event->type)) ?></span>
                                <h5><a href="<?= \baseUrl('events/' . $event->slug) ?>" class="text-decoration-none text-reset"><?= \escape($event->title) ?></a></h5>
                                <p class="text-muted small mb-2">
                                    <i class="far fa-calendar me-1"></i><?= \formatDate($event->event_date) ?>
                                    <?php if ($event->event_time): ?><i class="far fa-clock ms-2 me-1"></i><?= \formatTime($event->event_time) ?><?php endif; ?>
                                </p>
                                <p class="small"><?= \truncate($event->description ?? '', 90) ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-gold"><?= $event->price ? \formatPrice($event->price) . ' /ticket' : 'Free' ?></span>
                                    <a href="<?= \baseUrl('events/' . $event->slug) ?>" class="btn btn-sm btn-outline-gold">Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-5">No upcoming events at the moment.</div>
            <?php endif; ?>
        </div>
    </div>
</section>
