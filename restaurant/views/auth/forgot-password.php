<?php
/**
 * Auth: Forgot Password View
 */
?>
<section class="auth-section section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="auth-card glass-card" data-aos="fade-up">
                    <div class="text-center mb-4">
                        <h2 class="section-title mb-1">Reset Password</h2>
                        <p class="text-muted">Enter your email to receive a reset link</p>
                    </div>

                    <form method="POST" action="<?= \baseUrl('forgot-password') ?>" class="auth-form">
                        <?= \csrfField() ?>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" required placeholder="you@example.com">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-gold w-100 btn-lg">Send Reset Link</button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="<?= \baseUrl('login') ?>" class="text-gold"><i class="fas fa-arrow-left me-1"></i>Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
