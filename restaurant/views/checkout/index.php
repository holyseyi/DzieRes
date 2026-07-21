<?php
/**
 * Checkout View
 *
 * @var array $cartItems
 * @var float $subtotal, $taxAmount, $deliveryFee, $serviceCharge, $couponDiscount, $total
 * @var array $restaurantLocations
 */
$user = \auth();
$restaurantLocations = $restaurantLocations ?? [];
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">Almost There</p>
            <h1 class="page-title">Checkout</h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <form id="checkoutForm" method="POST" action="<?= \baseUrl('checkout/place') ?>">
            <?= \csrfField() ?>
            <div class="row g-4">
                <!-- Left: details -->
                <div class="col-lg-8">
                    <!-- Contact -->
                    <div class="glass-card p-4 mb-4">
                        <h5 class="mb-3"><i class="fas fa-user me-2 text-gold"></i>Contact Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Full Name</label>
                                <input type="text" name="guest_name" class="form-control" required value="<?= \escape($_POST['guest_name'] ?? '') ?>"></div>
                            <div class="col-md-6"><label class="form-label">Email</label>
                                <input type="email" name="guest_email" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Phone</label>
                                <input type="text" name="guest_phone" class="form-control" required></div>
                        </div>
                    </div>

                    <!-- Order type -->
                    <div class="glass-card p-4 mb-4">
                        <h5 class="mb-3"><i class="fas fa-box me-2 text-gold"></i>Order Type</h5>
                        <div class="order-type-selector">
                            <label class="order-type-option">
                                <input type="radio" name="order_type" value="delivery" checked>
                                <span><i class="fas fa-motorcycle"></i> Delivery</span>
                            </label>
                            <label class="order-type-option">
                                <input type="radio" name="order_type" value="pickup">
                                <span><i class="fas fa-shopping-bag"></i> Pickup</span>
                            </label>
                            <label class="order-type-option">
                                <input type="radio" name="order_type" value="dine_in">
                                <span><i class="fas fa-chair"></i> Dine In</span>
                            </label>
                        </div>

                        <div id="deliveryFields">
                            <div class="row g-3 mt-2">
                                <div class="col-md-8"><label class="form-label">Delivery Address</label>
                                    <input type="text" name="delivery_address" class="form-control" placeholder="Street address"></div>
                                <div class="col-md-4"><label class="form-label">City</label>
                                    <input type="text" name="delivery_city" class="form-control" placeholder="City"></div>
                                <div class="col-md-6"><label class="form-label">Phone</label>
                                    <input type="text" name="delivery_phone" class="form-control"></div>
                            </div>
                        </div>

                        <div id="pickupFields" style="display:none;">
                            <div class="row g-3 mt-2">
                                <div class="col-12">
                                    <label class="form-label">Pickup Location</label>
                                    <button type="button" id="locateMeBtn" class="btn btn-outline-gold btn-sm mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>Use My Location
                                    </button>
                                    <select name="pickup_location" id="pickupLocationSelect" class="form-select">
                                        <option value="">Select nearest location</option>
                                        <?php foreach ($restaurantLocations as $loc): ?>
                                            <option value="<?= \escape($loc['name']) ?>" data-lat="<?= $loc['lat'] ?>" data-lng="<?= $loc['lng'] ?>">
                                                <?= \escape($loc['name']) ?> - <?= \escape($loc['address']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="delivery_lat" id="deliveryLat">
                                    <input type="hidden" name="delivery_lng" id="deliveryLng">
                                    <div id="locationStatus" class="form-text text-muted"></div>
                                </div>
                            </div>
                        </div>

                        <div id="dineInFields" style="display:none;">
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label class="form-label">Select Table</label>
                                    <select name="table_id" class="form-select">
                                        <option value="">Choose a table</option>
                                        <?php $tables = \db()->fetchAll("SELECT * FROM tables WHERE status = 'available' ORDER BY capacity ASC"); ?>
                                        <?php foreach ($tables as $t): ?>
                                            <option value="<?= $t->id ?>"><?= \escape($t->table_number) ?> (<?= $t->capacity ?> seats)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="glass-card p-4 mb-4">
                        <h5 class="mb-3"><i class="fas fa-credit-card me-2 text-gold"></i>Payment Method</h5>
                        <div class="payment-methods">
                            <?php foreach (['cash' => 'Cash', 'card' => 'Card', 'mobile_money' => 'Mobile Money', 'pay_on_delivery' => 'Pay on Delivery'] as $val => $label): ?>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="<?= $val ?>" <?= $val === 'cash' ? 'checked' : '' ?>>
                                    <span><i class="fas fa-money-bill-wave me-1"></i><?= $label ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-muted small mt-2 mb-0"><i class="fas fa-lock me-1"></i>This is a demo gateway. No real payment is processed.</p>
                    </div>

                    <!-- Notes -->
                    <div class="glass-card p-4">
                        <h5 class="mb-3"><i class="fas fa-note-sticky me-2 text-gold"></i>Special Instructions</h5>
                        <textarea name="special_notes" class="form-control" rows="3" placeholder="Allergies, preferences, etc."></textarea>
                    </div>
                </div>

                <!-- Right: summary -->
                <div class="col-lg-4">
                    <div class="cart-summary glass-card p-4 sticky-summary">
                        <h5 class="mb-3">Your Order</h5>
                        <?php foreach ($cartItems as $item): ?>
                            <div class="d-flex justify-content-between mb-2 small">
                                <span><?= $item->quantity ?>× <?= \escape($item->name) ?></span>
                                <span><?= \formatPrice($item->total_price) ?></span>
                            </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span><?= \formatPrice($subtotal) ?></span></div>
                        <div class="d-flex justify-content-between mb-2"><span>Tax</span><span><?= \formatPrice($taxAmount) ?></span></div>
                        <div class="d-flex justify-content-between mb-2"><span>Service</span><span><?= \formatPrice($serviceCharge) ?></span></div>
                        <div class="d-flex justify-content-between mb-2"><span>Delivery</span><span><?= \formatPrice($deliveryFee) ?></span></div>
                        <?php if ($couponDiscount > 0): ?><div class="d-flex justify-content-between mb-2 text-success"><span>Coupon</span><span>-<?= \formatPrice($couponDiscount) ?></span></div><?php endif; ?>
                        <hr>
                        <div class="d-flex justify-content-between fs-5 fw-bold"><span>Total</span><span class="text-gold"><?= \formatPrice($total) ?></span></div>
                        <button type="submit" class="btn btn-gold w-100 btn-lg mt-3">Place Order</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
(function() {
    const orderTypeInputs = document.querySelectorAll('input[name="order_type"]');
    const deliveryFields = document.getElementById('deliveryFields');
    const pickupFields = document.getElementById('pickupFields');
    const dineInFields = document.getElementById('dineInFields');
    const locateMeBtn = document.getElementById('locateMeBtn');
    const pickupSelect = document.getElementById('pickupLocationSelect');
    const locationStatus = document.getElementById('locationStatus');
    const restaurantLocations = <?= json_encode($restaurantLocations) ?>;

    function toggleFields() {
        const type = document.querySelector('input[name="order_type"]:checked').value;
        if (deliveryFields) deliveryFields.style.display = type === 'delivery' ? 'block' : 'none';
        if (pickupFields) pickupFields.style.display = type === 'pickup' ? 'block' : 'none';
        if (dineInFields) dineInFields.style.display = type === 'dine_in' ? 'block' : 'none';
    }

    orderTypeInputs.forEach(function(input) {
        input.addEventListener('change', toggleFields);
    });

    function getDistance(lat1, lng1, lat2, lng2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLng/2) * Math.sin(dLng/2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    if (locateMeBtn) {
        locateMeBtn.addEventListener('click', function() {
            if (!navigator.geolocation) {
                locationStatus.textContent = 'Geolocation is not supported by your browser.';
                return;
            }
            locationStatus.textContent = 'Detecting your location...';
            navigator.geolocation.getCurrentPosition(function(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                document.getElementById('deliveryLat').value = userLat;
                document.getElementById('deliveryLng').value = userLng;

                let nearest = null;
                let minDist = Infinity;
                restaurantLocations.forEach(function(loc) {
                    const d = getDistance(userLat, userLng, loc.lat, loc.lng);
                    if (d < minDist) {
                        minDist = d;
                        nearest = loc;
                    }
                });

                if (nearest && pickupSelect) {
                    pickupSelect.value = nearest.name;
                    locationStatus.textContent = 'Nearest location: ' + nearest.name + ' (' + nearest.address + ')';
                } else {
                    locationStatus.textContent = 'Could not determine nearest location. Please select manually.';
                }
            }, function() {
                locationStatus.textContent = 'Unable to retrieve your location. Please select a location manually.';
            });
        });
    }
})();
</script>
