<?php
/**
 * Career Detail View
 *
 * @var object $job
 */
?>
<section class="page-hero" style="min-height:auto;padding:80px 0;">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <h1 class="page-title"><?= \escape($job->title) ?></h1>
            <div class="section-divider mx-auto"></div>
            <p class="text-white-50">
                <?= \escape(ucfirst(str_replace('_', ' ', $job->type))) ?>
                <?php if ($job->department): ?> · <?= \escape($job->department) ?><?php endif; ?>
                <?php if ($job->location): ?> · <?= \escape($job->location) ?><?php endif; ?>
                <?php if ($job->salary_range): ?> · <?= \escape($job->salary_range) ?><?php endif; ?>
            </p>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <h4>About the Role</h4>
                <div class="mb-4"><?= nl2br(\escape($job->description ?? '')) ?></div>
                <?php if ($job->requirements): ?>
                    <h4>Requirements</h4>
                    <div class="mb-4"><?= nl2br(\escape($job->requirements)) ?></div>
                <?php endif; ?>
            </div>
            <div class="col-lg-4">
                <div class="glass-card p-4 sticky-summary">
                    <h5 class="mb-3">Apply Now</h5>
                    <form method="POST" action="<?= \baseUrl('careers/apply') ?>">
                        <?= \csrfField() ?>
                        <input type="hidden" name="job_id" value="<?= $job->id ?>">
                        <div class="mb-2"><input type="text" name="firstname" class="form-control" placeholder="First Name" required></div>
                        <div class="mb-2"><input type="text" name="lastname" class="form-control" placeholder="Last Name" required></div>
                        <div class="mb-2"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                        <div class="mb-2"><input type="text" name="phone" class="form-control" placeholder="Phone"></div>
                        <div class="mb-3"><textarea name="cover_letter" class="form-control" rows="4" placeholder="Cover Letter"></textarea></div>
                        <button class="btn btn-gold w-100 btn-lg">Submit Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
