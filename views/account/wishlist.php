<?php
/**
 * Account Wishlist View
 *
 * @var array $wishlist
 */
?>
<section class="page-hero" style="min-height:auto;padding:70px 0;"><div class="container"><h1 class="page-title">My Wishlist</h1></div></section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3"><?php \partial('account-sidebar', ['user' => \auth()]); ?></div>
            <div class="col-lg-9">
                <?php if (!empty($wishlist)): ?>
                    <div class="row g-3">
                        <?php foreach ($wishlist as $w): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="food-card">
                                    <div class="food-image">
                                        <a href="<?= \baseUrl('menu/' . ($w->slug ?? '')) ?>"><img src="<?= \uploadUrl($w->image) ?>" alt="<?= \escape($w->name) ?>"></a>
                                    </div>
                                    <div class="food-body"><h6 class="food-name"><a href="<?= \baseUrl('menu/' . ($w->slug ?? '')) ?>" class="text-decoration-none text-reset"><?= \escape($w->name) ?></a></h6></div>
                                    <div class="food-footer"><div class="food-price"><?= \formatPrice($w->final_price) ?></div><button class="btn btn-light-gold btn-sm add-to-cart" data-food-id="<?= $w->food_id ?>"><?= \icon('cart', []) ?></button></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="glass-card p-4 text-center text-muted">Your wishlist is empty.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
