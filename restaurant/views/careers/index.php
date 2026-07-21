<?php
/**
 * Careers Index View
 *
 * @var array $jobs
 */
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">Join Our Team</p>
            <h1 class="page-title">Careers</h1>
            <div class="section-divider mx-auto"></div>
            <p class="text-white-50">Build your career in hospitality with DzieRes Restaurant</p>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <?php if (!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                    <div class="col-md-6" data-aos="fade-up">
                        <div class="job-card glass-card p-4 h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="mb-0"><?= \escape($job->title) ?></h5>
                                <span class="badge bg-gold"><?= \escape(ucfirst(str_replace('_', ' ', $job->type))) ?></span>
                            </div>
                            <div class="text-muted small mb-2">
                                <?php if ($job->department): ?><?= \icon('building', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i><?= \escape($job->department) ?> · <?php endif; ?>
                                <?php if ($job->location): ?><?= \icon('map-marker', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i><?= \escape($job->location) ?><?php endif; ?>
                            </div>
                            <p class="small"><?= \truncate($job->description ?? '', 120) ?></p>
                            <?php if ($job->salary_range): ?><p class="small text-gold fw-bold mb-3"><?= \icon('money-bill-wave', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i><?= \escape($job->salary_range) ?></p><?php endif; ?>
                            <a href="<?= \baseUrl('careers/' . $job->slug) ?>" class="btn btn-outline-gold">View & Apply</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-5">No open positions right now. Check back soon!</div>
            <?php endif; ?>
        </div>
    </div>
</section>
