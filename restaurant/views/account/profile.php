<?php
/**
 * Account Profile View
 *
 * @var object $user
 */
$errors = \sessionFlash('errors') ?? [];
$old = \sessionFlash('old') ?? [];
?>
<section class="page-hero" style="min-height:auto;padding:70px 0;"><div class="container"><h1 class="page-title">Profile</h1></div></section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3"><?php \partial('account-sidebar', ['user' => $user]); ?></div>
            <div class="col-lg-9">
                <div class="glass-card p-4">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger"><ul class="mb-0">
                            <?php foreach ($errors as $fe): ?><?php foreach ($fe as $e): ?><li><?= \escape($e) ?></li><?php endforeach; ?><?php endforeach; ?>
                        </ul></div>
                    <?php endif; ?>
                    <form method="POST" action="<?= \baseUrl('account/profile/update') ?>">
                        <?= \csrfField() ?>
                        <h5 class="mb-3">Personal Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">First Name</label><input type="text" name="firstname" class="form-control" value="<?= \escape($old['firstname'] ?? $user->firstname) ?>" required></div>
                            <div class="col-md-6"><label class="form-label">Last Name</label><input type="text" name="lastname" class="form-control" value="<?= \escape($old['lastname'] ?? $user->lastname) ?>" required></div>
                            <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" value="<?= \escape($user->email) ?>" disabled></div>
                            <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="<?= \escape($old['phone'] ?? $user->phone) ?>"></div>
                            <div class="col-md-6"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="<?= \escape($old['city'] ?? $user->city) ?>"></div>
                            <div class="col-md-6"><label class="form-label">State/Region</label><input type="text" name="state" class="form-control" value="<?= \escape($old['state'] ?? $user->state) ?>"></div>
                            <div class="col-md-6"><label class="form-label">ZIP</label><input type="text" name="zip" class="form-control" value="<?= \escape($old['zip'] ?? $user->zip) ?>"></div>
                            <div class="col-md-6"><label class="form-label">Country</label><input type="text" name="country" class="form-control" value="<?= \escape($old['country'] ?? $user->country) ?>"></div>
                            <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2"><?= \escape($old['address'] ?? $user->address) ?></textarea></div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Change Password</h5>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">New Password</label><input type="password" name="password" class="form-control" minlength="8"></div>
                            <div class="col-md-6"><label class="form-label">Confirm Password</label><input type="password" name="password_confirm" class="form-control"></div>
                        </div>
                        <button class="btn btn-gold btn-lg mt-4">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
