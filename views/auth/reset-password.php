<?php
/**
 * Auth: Reset Password View
 *
 * @var string $token
 */
?>
<section class="auth-section section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="auth-card glass-card" data-aos="fade-up">
                    <div class="text-center mb-4">
                        <h2 class="section-title mb-1">New Password</h2>
                        <p class="text-muted">Choose a new secure password</p>
                    </div>

                    <form method="POST" action="<?= \baseUrl('reset-password') ?>" class="auth-form">
                        <?= \csrfField() ?>
                        <input type="hidden" name="token" value="<?= \escape($token) ?>">
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" required minlength="8">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirm" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-gold w-100 btn-lg">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
