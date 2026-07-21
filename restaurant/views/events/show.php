<?php
/**
 * Event Detail View
 *
 * @var object $event
 */
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle"><?= \escape(ucfirst($event->type)) ?> Event</p>
            <h1 class="page-title"><?= \escape($event->title) ?></h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7">
                <img src="<?= \uploadUrl($event->image) ?>" alt="<?= \escape($event->title) ?>" class="img-fluid rounded-4 mb-4 shadow">
                <div class="mb-3">
                    <span class="badge bg-light text-dark me-2"><?= \icon('calendar', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i><?= \formatDate($event->event_date) ?></span>
                    <?php if ($event->event_time): ?><span class="badge bg-light text-dark me-2"><?= \icon('clock', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i><?= \formatTime($event->event_time) ?></span><?php endif; ?>
                    <?php if ($event->location): ?><span class="badge bg-light text-dark"><?= \icon('map-marker', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i><?= \escape($event->location) ?></span><?php endif; ?>
                </div>
                <div class="event-content">
                    <?= nl2br(\escape($event->content ?? $event->description ?? '')) ?>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="glass-card p-4 sticky-summary">
                    <h5 class="mb-3">Book This Event</h5>
                    <div class="d-flex justify-content-between mb-2"><span>Price per ticket</span><strong class="text-gold"><?= $event->price ? \formatPrice($event->price) : 'Free' ?></strong></div>
                    <div class="d-flex justify-content-between mb-3"><span>Capacity</span><span><?= $event->capacity ?: 'Unlimited' ?></span></div>
                    <form method="POST" action="<?= \baseUrl('events/book') ?>">
                        <?= \csrfField() ?>
                        <input type="hidden" name="event_id" value="<?= $event->id ?>">
                        <div class="mb-2"><input type="text" name="name" class="form-control" placeholder="Your Name" required></div>
                        <div class="mb-2"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                        <div class="mb-3"><input type="number" name="tickets" class="form-control" value="1" min="1" max="<?= $event->capacity ?: 20 ?>"></div>
                        <button class="btn btn-gold w-100 btn-lg">Book Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
