<?php
/**
 * Account Addresses View
 *
 * @var object $user
 */
?>
<section class="page-hero" style="min-height:auto;padding:70px 0;"><div class="container"><h1 class="page-title">Addresses</h1></div></section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3"><?php \partial('account-sidebar', ['user' => $user]); ?></div>
            <div class="col-lg-9">
                <div class="glass-card p-4">
                    <h5 class="mb-3">Delivery Address</h5>
                    <form method="POST" action="<?= \baseUrl('account/addresses/save') ?>">
                        <?= \csrfField() ?>
                        <div class="mb-3">
                            <label class="form-label">Street Address</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Enter your delivery address"><?= \escape($user->address ?? '') ?></textarea>
                        </div>
                        <button class="btn btn-gold">Save Address</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
