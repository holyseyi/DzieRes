<?php
/**
 * Auth: Login View (content only; wrapped by main layout)
 */
?>
<section class="auth-section section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="auth-card glass-card" data-aos="fade-up">
                    <div class="text-center mb-4">
                        <h2 class="section-title mb-1">Welcome Back</h2>
                        <p class="text-muted">Sign in to your DzieRes account</p>
                    </div>

                    <?php $errors = \sessionFlash('errors') ?? []; ?>
                    <?php $old = \sessionFlash('old') ?? []; ?>

                    <form method="POST" action="<?= \baseUrl('login') ?>" class="auth-form">
                        <?= \csrfField() ?>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><?= \icon('message', []) ?>></i></span>
                                <input type="email" name="email" class="form-control" required
                                       value="<?= \escape($old['email'] ?? '') ?>" placeholder="you@example.com">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><?= \icon('lock', []) ?>></i></span>
                                <input type="password" name="password" class="form-control" required placeholder="••••••••">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <a href="<?= \baseUrl('forgot-password') ?>" class="text-gold">Forgot password?</a>
                        </div>

                        <button type="submit" class="btn btn-gold w-100 btn-lg">Sign In</button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">Don't have an account? <a href="<?= \baseUrl('register') ?>" class="text-gold">Sign Up</a></p>
                    </div>

                    <div class="auth-demo mt-4 p-3 rounded bg-light-section">
                        <small class="text-muted d-block mb-1"><?= \icon('info', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i>Demo Accounts</small>
                        <small><strong>Admin:</strong> admin@dzieres.com / admin123</small><br>
                        <small><strong>Customer:</strong> customer@dzieres.com / customer123</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
