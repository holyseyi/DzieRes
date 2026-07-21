<?php
/**
 * Testimonials View
 *
 * @var array $testimonials
 */
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">What People Say</p>
            <h1 class="page-title">Testimonials</h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <?php if (!empty($testimonials)): ?>
                <?php foreach ($testimonials as $t): ?>
                    <div class="col-md-6 col-lg-4" data-aos="fade-up">
                        <div class="testimonial-card h-100">
                            <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                            <?php if ($t->rating): ?><div class="text-warning mb-2"><?= str_repeat('★', $t->rating) ?><?= str_repeat('☆', 5 - $t->rating) ?></div><?php endif; ?>
                            <p class="testimonial-text"><?= \escape($t->content) ?></p>
                            <div class="testimonial-author">
                                <img src="<?= \uploadUrl($t->image) ?>" alt="<?= \escape($t->guest_name) ?>" class="author-avatar" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($t->guest_name) ?>&background=c9a84c&color=fff'">
                                <div>
                                    <h6 class="mb-0"><?= \escape($t->guest_name) ?></h6>
                                    <small class="text-muted"><?= \escape($t->guest_title ?? 'Guest') ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted">No testimonials yet.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Submit -->
<section class="section-padding bg-light-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="glass-card p-4 p-md-5" data-aos="fade-up">
                    <h4 class="mb-3 text-center">Share Your Experience</h4>
                    <form method="POST" action="<?= \baseUrl('testimonials/submit') ?>">
                        <?= \csrfField() ?>
                        <div class="row g-3">
                            <div class="col-md-6"><input type="text" name="name" class="form-control" placeholder="Your Name" required></div>
                            <div class="col-md-6"><input type="text" name="title" class="form-control" placeholder="Title (e.g. Food Critic)"></div>
                            <div class="col-md-6"><input type="number" name="rating" min="1" max="5" class="form-control" placeholder="Rating 1-5" required></div>
                            <div class="col-12"><textarea name="comment" class="form-control" rows="4" placeholder="Your testimonial" required></textarea></div>
                        </div>
                        <button class="btn btn-gold w-100 btn-lg mt-3">Submit Testimonial</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
