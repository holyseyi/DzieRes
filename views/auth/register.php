<?php
/**
 * Auth: Register View
 */
?>
<section class="auth-section section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="auth-card glass-card" data-aos="fade-up">
                    <div class="text-center mb-4">
                        <h2 class="section-title mb-1">Create Rider Account</h2>
                        <p class="text-muted">Join DzieRes delivery team</p>
                    </div>

                    <?php $errors = \sessionFlash('errors') ?? []; ?>
                    <?php $old = \sessionFlash('old') ?? []; ?>
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $fieldErrors): ?>
                                    <?php foreach ($fieldErrors as $e): ?><li><?= \escape($e) ?></li><?php endforeach; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= \baseUrl('register') ?>" class="auth-form">
                        <?= \csrfField() ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" name="firstname" class="form-control" required
                                       value="<?= \escape($old['firstname'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="lastname" class="form-control" required
                                       value="<?= \escape($old['lastname'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required
                                       value="<?= \escape($old['email'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control"
                                       value="<?= \escape($old['phone'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required minlength="8">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirm" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-gold w-100 btn-lg mt-3">Create Account</button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">Already have an account? <a href="<?= \baseUrl('login') ?>" class="text-gold">Sign In</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
