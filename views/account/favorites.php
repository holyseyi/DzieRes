<?php
/**
 * Account Favorites View
 *
 * @var array $favorites
 */
?>
<section class="page-hero" style="min-height:auto;padding:70px 0;"><div class="container"><h1 class="page-title">Favorites</h1></div></section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3"><?php \partial('account-sidebar', ['user' => \auth()]); ?></div>
            <div class="col-lg-9">
                <?php if (!empty($favorites)): ?>
                    <div class="row g-3">
                        <?php foreach ($favorites as $f): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="food-card">
                                    <div class="food-image">
                                        <a href="<?= \baseUrl('menu/' . $f->slug) ?>"><img src="<?= \uploadUrl($f->image) ?>" alt="<?= \escape($f->name) ?>"></a>
                                        <button class="btn btn-sm btn-light-gold favorite-btn active position-absolute top-0 end-0 m-2" data-food-id="<?= $f->id ?>"><?= \icon('heart', []) ?></button>
                                    </div>
                                    <div class="food-body"><p class="food-category"><?= \escape($f->category_name ?? '') ?></p><h6 class="food-name"><a href="<?= \baseUrl('menu/' . $f->slug) ?>" class="text-decoration-none text-reset"><?= \escape($f->name) ?></a></h6></div>
                                    <div class="food-footer"><div class="food-price"><?= \formatPrice($f->final_price ?? $f->price) ?></div><button class="btn btn-light-gold btn-sm add-to-cart" data-food-id="<?= $f->id ?>"><?= \icon('cart', []) ?></button></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="glass-card p-4 text-center text-muted">No favorites yet. Tap the heart on any dish to save it.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
