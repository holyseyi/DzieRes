<?php
/**
 * Contact View
 */
$old = \sessionFlash('old') ?? [];
$errors = \sessionFlash('errors') ?? [];
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">We'd Love to Hear From You</p>
            <h1 class="page-title">Contact Us</h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="glass-card p-4 h-100">
                    <h4 class="mb-4">Get In Touch</h4>
                    <div class="contact-info-item mb-4">
                        <div class="contact-icon"><?= \icon('map-marker', []) ?></div>
                        <div><strong>Address</strong><p class="text-muted mb-0"><?= \escape(\getSetting('restaurant_address', '123 Independence Avenue, Accra, Ghana')) ?></p></div>
                    </div>
                    <div class="contact-info-item mb-4">
                        <div class="contact-icon"><?= \icon('phone', []) ?></div>
                        <div><strong>Phone</strong><p class="text-muted mb-0"><?= \escape(\getSetting('restaurant_phone', '+233 50 000 0000')) ?></p></div>
                    </div>
                    <div class="contact-info-item mb-4">
                        <div class="contact-icon"><?= \icon('message', []) ?></div>
                        <div><strong>Email</strong><p class="text-muted mb-0"><?= \escape(\getSetting('restaurant_email', 'info@dzieres.com')) ?></p></div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-icon"><?= \icon('clock', []) ?></div>
                        <div><strong>Hours</strong><p class="text-muted mb-0"><?= \escape(\getSetting('opening_hours', 'Mon - Sun: 7:00 AM - 11:00 PM')) ?></p></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="glass-card p-4 p-md-5">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger"><ul class="mb-0">
                            <?php foreach ($errors as $fe): ?><?php foreach ($fe as $e): ?><li><?= \escape($e) ?></li><?php endforeach; ?><?php endforeach; ?>
                        </ul></div>
                    <?php endif; ?>
                    <form method="POST" action="<?= \baseUrl('contact/send') ?>">
                        <?= \csrfField() ?>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" required value="<?= \escape($old['name'] ?? '') ?>"></div>
                            <div class="col-md-6"><label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required value="<?= \escape($old['email'] ?? '') ?>"></div>
                            <div class="col-md-6"><label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="<?= \escape($old['phone'] ?? '') ?>"></div>
                            <div class="col-md-6"><label class="form-label">Subject</label>
                                <input type="text" name="subject" class="form-control" value="<?= \escape($old['subject'] ?? '') ?>"></div>
                            <div class="col-12"><label class="form-label">Message</label>
                                <textarea name="message" class="form-control" rows="5" required><?= \escape($old['message'] ?? '') ?></textarea></div>
                        </div>
                        <button class="btn btn-gold btn-lg w-100 mt-3">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="p-0">
    <img src="<?= \asset('images/placeholders/map-placeholder.jpg') ?>" alt="Restaurant Location" style="width:100%;height:350px;object-fit:cover;">
</section>
