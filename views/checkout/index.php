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
        <?php if (empty($cartItems)): ?>
            <div class="text-center py-5">
                <?= \icon('cart', ['style' => 'width:4em;height:4em;color:#6c757d;']) ?>
                <h4>Your cart is empty</h4>
                <p class="text-muted">Add some delicious items to get started.</p>
                <a href="<?= \baseUrl('menu') ?>" class="btn btn-gold btn-lg mt-2">Browse Menu</a>
            </div>
        <?php else: ?>
        <form id="checkoutForm" method="POST" action="<?= \baseUrl('checkout/place') ?>">
            <?= \csrfField() ?>
            <div class="row g-4">
                <!-- Left: cart items + details -->
                <div class="col-lg-8">
                    <!-- Cart Items -->
                    <div class="glass-card p-4 mb-4">
                        <h5 class="mb-3"><?= \icon('cart', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;', 'class' => 'text-gold']) ?>Your Items</h5>
                        <?php foreach ($cartItems as $item): ?>
                            <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                                <div class="cart-item-img">
                                    <img src="<?= \menuImageUrl($item) ?>" alt="<?= \escape($item->name) ?>">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><?= \escape($item->name) ?></h6>
                                    <small class="text-muted"><?= \formatPrice($item->final_price ?? $item->unit_price) ?> each</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold"><?= \formatPrice($item->total_price) ?></div>
                                    <small class="text-muted">x<?= $item->quantity ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Contact -->
                    <div class="glass-card p-4 mb-4">
                        <h5 class="mb-3"><?= \icon('user', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;', 'class' => 'text-gold']) ?>Your Details</h5>
                        <p class="text-muted small mb-3">No account needed. We'll send order updates to this phone number.</p>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Full Name</label>
                                <input type="text" name="guest_name" class="form-control" required value="<?= \escape($_POST['guest_name'] ?? '') ?>" placeholder="Your name"></div>
                            <div class="col-md-6"><label class="form-label">Phone Number</label>
                                <input type="tel" name="guest_phone" class="form-control" required placeholder="+233 50 000 0000"></div>
                        </div>
                    </div>

                    <!-- Order type -->
                    <div class="glass-card p-4 mb-4">
                        <h5 class="mb-3"><?= \icon('box', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;', 'class' => 'text-gold']) ?>Order Type</h5>
                        <div class="order-type-selector">
                            <label class="order-type-option">
                                <input type="radio" name="order_type" value="delivery" checked>
                                <span><?= \icon('motorcycle', []) ?> Delivery</span>
                            </label>
                            <label class="order-type-option">
                                <input type="radio" name="order_type" value="pickup">
                                <span><?= \icon('cart', []) ?> Pickup</span>
                            </label>
                            <label class="order-type-option">
                                <input type="radio" name="order_type" value="dine_in">
                                <span><?= \icon('chair', []) ?> Dine In</span>
                            </label>
                        </div>

                        <div id="deliveryFields">
                            <div class="row g-3 mt-2">
                                <div class="col-md-8"><label class="form-label">Delivery Address</label>
                                    <input type="text" name="delivery_address" class="form-control" placeholder="Street address"></div>
                                <div class="col-md-4"><label class="form-label">City</label>
                                    <input type="text" name="delivery_city" class="form-control" placeholder="City"></div>
                                <div class="col-md-6"><label class="form-label">Recipient Phone Number</label>
                                    <input type="tel" name="delivery_phone" class="form-control" placeholder="+233 50 000 0000"></div>
                            </div>
                        </div>

                        <div id="pickupFields" style="display:none;">
                            <div class="row g-3 mt-2">
                                <div class="col-12">
                                    <label class="form-label">Pickup Location</label>
                                    <button type="button" id="locateMeBtn" class="btn btn-outline-gold btn-sm mb-2">
                                        <?= \icon('map-marker', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>Use My Location
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
                                    <select name="table_id" id="tableSelect" class="form-select">
                                        <option value="">Loading tables...</option>
                                    </select>
                                    <div id="tableStatus" class="form-text text-muted"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="glass-card p-4 mb-4">
                        <h5 class="mb-3"><?= \icon('credit-card', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;', 'class' => 'text-gold']) ?>Payment Method</h5>
                        <div class="payment-methods mb-3">
                            <?php foreach (['cash' => 'Cash', 'card' => 'Card', 'mobile_money' => 'Mobile Money', 'pay_on_delivery' => 'Pay on Delivery'] as $val => $label): ?>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="<?= $val ?>" <?= $val === 'cash' ? 'checked' : '' ?>>
                                    <span><?= \icon('money-bill-wave', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?><?= $label ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <div id="cardFields" style="display:none;">
                            <div class="row g-3">
                                <div class="col-12"><label class="form-label">Card Number</label>
                                    <input type="text" name="card_number" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19"></div>
                                <div class="col-md-6"><label class="form-label">Expiry</label>
                                    <input type="text" name="card_expiry" class="form-control" placeholder="MM/YY" maxlength="5"></div>
                                <div class="col-md-6"><label class="form-label">CVV</label>
                                    <input type="text" name="card_cvv" class="form-control" placeholder="123" maxlength="4"></div>
                            </div>
                        </div>

                        <div id="momoFields" style="display:none;">
                            <div class="col-12"><label class="form-label">Mobile Money Number</label>
                                <input type="tel" name="mobile_money_number" class="form-control" placeholder="+233 50 000 0000"></div>
                        </div>

                        <p class="text-muted small mt-2 mb-0"><?= \icon('lock', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>This is a demo gateway. No real payment is processed.</p>
                    </div>

                    <!-- Notes -->
                    <div class="glass-card p-4">
                        <h5 class="mb-3"><?= \icon('note-sticky', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;', 'class' => 'text-gold']) ?>Special Instructions</h5>
                        <textarea name="special_notes" class="form-control" rows="3" placeholder="Allergies, preferences, etc."></textarea>
                    </div>
                </div>

                <!-- Right: summary -->
                <div class="col-lg-4">
                    <div class="cart-summary glass-card p-4 sticky-summary">
                        <h5 class="mb-3">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span><span><?= \formatPrice($subtotal) ?></span>
                        </div>
                        <div id="deliveryLine" style="display:none;" class="d-flex justify-content-between mb-2">
                            <span>Delivery</span><span><?= \formatPrice($deliveryFee) ?></span>
                        </div>
                        <?php if ($couponDiscount > 0): ?><div class="d-flex justify-content-between mb-2 text-success"><span>Coupon</span><span>-<?= \formatPrice($couponDiscount) ?></span></div><?php endif; ?>
                        <hr>
                        <div class="d-flex justify-content-between fs-5 fw-bold"><span>Total</span><span class="text-gold" id="checkoutTotal"><?= \formatPrice($total) ?></span></div>
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
    const deliveryLine = document.getElementById('deliveryLine');
    const tableSelect = document.getElementById('tableSelect');
    const tableStatus = document.getElementById('tableStatus');
    const locateMeBtn = document.getElementById('locateMeBtn');
    const pickupSelect = document.getElementById('pickupLocationSelect');
    const locationStatus = document.getElementById('locationStatus');
    const restaurantLocations = <?= json_encode($restaurantLocations) ?>;
    const subtotal = <?= (float)$subtotal ?>;
    const deliveryFee = <?= (float)$deliveryFee ?>;
    const totalEl = document.getElementById('checkoutTotal');

    function updateTotal() {
        let total = subtotal;
        const type = document.querySelector('input[name="order_type"]:checked').value;
        if (type === 'delivery') {
            total += deliveryFee;
        }
        if (totalEl) {
            totalEl.textContent = '₵' + total.toFixed(2);
        }
    }

    function loadAvailableTables() {
        if (!tableSelect) return;
        tableSelect.innerHTML = '<option value="">Loading tables...</option>';
        tableSelect.disabled = true;
        tableStatus.textContent = 'Refreshing from admin dashboard...';
        
        fetch('<?= \baseUrl('api/tables/dinein') ?>')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                const tables = data.data || data || [];
                const available = tables.filter(function(t) { return t.status === 'available'; });
                let html = '<option value="">Choose a table</option>';
                available.forEach(function(t) {
                    html += '<option value="' + t.id + '">Table ' + t.table_number + ' (' + t.capacity + ' seats - ' + (t.location || 'Indoor') + ')</option>';
                });
                tableSelect.innerHTML = html;
                tableSelect.disabled = false;
                tableStatus.textContent = available.length ? 'Showing only available tables from admin dashboard' : 'No tables available right now';
            })
            .catch(function() {
                tableSelect.innerHTML = '<option value="">Failed to load tables</option>';
                tableSelect.disabled = false;
                tableStatus.textContent = 'Please try again later';
            });
    }

    function toggleFields() {
        const type = document.querySelector('input[name="order_type"]:checked').value;
        if (deliveryFields) deliveryFields.style.display = type === 'delivery' ? 'block' : 'none';
        if (pickupFields) pickupFields.style.display = type === 'pickup' ? 'block' : 'none';
        if (dineInFields) {
            dineInFields.style.display = type === 'dine_in' ? 'block' : 'none';
            if (type === 'dine_in') {
                loadAvailableTables();
            }
        }
        if (deliveryLine) deliveryLine.style.display = type === 'delivery' ? 'flex' : 'none';
        updateTotal();
    }

    orderTypeInputs.forEach(function(input) {
        input.addEventListener('change', toggleFields);
    });

    function togglePaymentFields() {
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        const cardFields = document.getElementById('cardFields');
        const momoFields = document.getElementById('momoFields');
        
        if (cardFields) cardFields.style.display = method === 'card' ? 'block' : 'none';
        if (momoFields) momoFields.style.display = method === 'mobile_money' ? 'block' : 'none';
    }

    document.querySelectorAll('input[name="payment_method"]').forEach(function(input) {
        input.addEventListener('change', togglePaymentFields);
    });

    toggleFields();
    togglePaymentFields();

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
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</section>