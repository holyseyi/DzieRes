<?php
/**
 * Cart View
 *
 * @var array $cartItems
 * @var float $subtotal, $taxAmount, $deliveryFee, $serviceCharge, $total
 */
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">Your Selection</p>
            <h1 class="page-title">Shopping Cart</h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <?php if (empty($cartItems)): ?>
            <div class="text-center py-5">
                <?= \icon('cart', ['style' => 'width:4em;height:4em;color:#6c757d;']) ?>></i>
                <h4>Your cart is empty</h4>
                <p class="text-muted">Add some delicious items to get started.</p>
                <a href="<?= \baseUrl('menu') ?>" class="btn btn-gold btn-lg mt-2">Browse Menu</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="cart-items">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="cart-item" data-food-id="<?= $item->food_id ?>">
                                <div class="cart-item-img">
                                    <img src="<?= \uploadUrl($item->image) ?>" alt="<?= \escape($item->name) ?>">
                                </div>
                                <div class="cart-item-info flex-grow-1">
                                    <h6 class="mb-1"><?= \escape($item->name) ?></h6>
                                    <div class="text-muted small"><?= \formatPrice($item->final_price ?? $item->unit_price) ?> each</div>
                                    <?php if (($item->availability ?? 'available') === 'sold_out'): ?>
                                        <span class="badge bg-danger mt-1">Sold Out</span>
                                    <?php endif; ?>
                                </div>
                                <div class="cart-item-qty">
                                    <div class="quantity-control">
                                        <button type="button" class="qty-btn update-qty" data-action="dec" data-id="<?= $item->food_id ?>">−</button>
                                        <input type="number" class="qty-input" value="<?= $item->quantity ?>" min="1" data-id="<?= $item->food_id ?>">
                                        <button type="button" class="qty-btn update-qty" data-action="inc" data-id="<?= $item->food_id ?>">+</button>
                                    </div>
                                </div>
                                <div class="cart-item-total">
                                    <strong><?= \formatPrice($item->total_price) ?></strong>
                                </div>
                                <button class="cart-item-remove remove-item" data-id="<?= $item->food_id ?>" title="Remove">
                                    <?= \icon('trash', []) ?>></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-3">
                        <a href="<?= \baseUrl('menu') ?>" class="btn btn-outline-gold"><?= \icon('arrow-left', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;']) ?>></i>Continue Shopping</a>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="cart-summary glass-card p-4 sticky-summary">
                        <h5 class="mb-3">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span><span><?= \formatPrice($subtotal) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (<?= $taxRate ?>%)</span><span><?= \formatPrice($taxAmount) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Service Charge</span><span><?= \formatPrice($serviceCharge) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Fee</span><span><?= \formatPrice($deliveryFee) ?></span>
                        </div>

                        <!-- Coupon -->
                        <form id="couponForm" class="my-3">
                            <?= \csrfField() ?>
                            <div class="input-group">
                                <input type="text" name="code" class="form-control" placeholder="Coupon code" id="couponCode">
                                <button class="btn btn-gold" type="submit">Apply</button>
                            </div>
                        </form>

                        <hr>
                        <div class="d-flex justify-content-between mb-3 fs-5 fw-bold">
                            <span>Total</span><span class="text-gold"><?= \formatPrice($total) ?></span>
                        </div>
                        <a href="<?= \baseUrl('checkout') ?>" class="btn btn-gold w-100 btn-lg">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
