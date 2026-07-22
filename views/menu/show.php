<?php
/**
 * Menu Show (Food Detail) View
 *
 * @var object $food
 * @var object|null $nutrition
 * @var array $relatedFoods
 * @var array $reviews
 */
$tags = is_array($food->tags) ? $food->tags : json_decode($food->tags ?? '[]', true);
$ingredientsList = is_array($food->ingredients_list ?? []) ? $food->ingredients_list : json_decode($food->ingredients ?? '[]', true);
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle"><?= \escape($food->category_name ?? 'Menu') ?></p>
            <h1 class="page-title"><?= \escape($food->name) ?></h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="food-detail-image rounded-4 overflow-hidden shadow">
                    <img src="<?= \menuImageUrl($food) ?>" alt="<?= \escape($food->name) ?>" class="img-fluid w-100">
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <?php if ($food->is_todays_special): ?><span class="badge bg-gold">Today's Special</span><?php endif; ?>
                    <?php if ($food->is_chef_recommendation): ?><span class="badge bg-warning text-dark">Chef's Pick</span><?php endif; ?>
                    <?php if ($food->is_featured): ?><span class="badge bg-info">Featured</span><?php endif; ?>
                </div>

                <h2 class="mb-3"><?= \escape($food->name) ?></h2>
                <p class="text-muted lead"><?= \escape($food->description ?? '') ?></p>

                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="food-price fs-3">
                        <?= \formatPrice($food->final_price ?? $food->price) ?>
                        <?php if ($food->discount_percent > 0): ?>
                            <span class="original-price fs-5"><?= \formatPrice($food->price) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if ($food->availability === 'sold_out'): ?>
                        <span class="badge bg-danger">Sold Out</span>
                    <?php elseif ($food->availability === 'unavailable'): ?>
                        <span class="badge bg-warning text-dark">Unavailable</span>
                    <?php else: ?>
                        <span class="badge bg-success">Available</span>
                    <?php endif; ?>
                </div>

                <!-- Meta info -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="info-box text-center">
                            <?= \icon('clock', ['class=" text-gold"']) ?>
                            <div class="fw-bold"><?= $food->preparation_time ?? 15 ?> min</div>
                            <small class="text-muted">Prep Time</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="info-box text-center">
                            <?= \icon('fire', ['class=" text-gold"']) ?>
                            <div class="fw-bold"><?= $food->calories ?? 0 ?></div>
                            <small class="text-muted">Calories</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="info-box text-center">
                            <?= \icon('pepper-hot', ['class=" text-gold"']) ?>
                            <div class="fw-bold text-capitalize"><?= str_replace('_', ' ', $food->spice_level ?? 'mild') ?></div>
                            <small class="text-muted">Spice</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="info-box text-center">
                            <?= \icon('utensils', ['class=" text-gold"']) ?>
                            <div class="fw-bold text-capitalize"><?= $food->unit ?? 'plate' ?></div>
                            <small class="text-muted">Serving</small>
                        </div>
                    </div>
                </div>

                <?php if (!empty($ingredientsList)): ?>
                    <h6 class="mb-2">Ingredients</h6>
                    <div class="mb-4">
                        <?php foreach ($ingredientsList as $ing): ?>
                            <span class="badge bg-light text-dark me-2 mb-2"><?= \escape(is_string($ing) ? $ing : ($ing['name'] ?? '')) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($tags)): ?>
                    <div class="mb-4">
                        <?php foreach ($tags as $tag): ?>
                            <span class="tag-pill"><?= \escape($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Quantity & Add to cart -->
                <?php if ($food->availability === 'available'): ?>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="quantity-control qty-group">
                            <button type="button" class="qty-btn" data-action="dec">−</button>
                            <input type="number" class="qty-input" id="detailQty" value="1" min="1" max="<?= $food->max_order_qty ?? 20 ?>">
                            <button type="button" class="qty-btn" data-action="inc">+</button>
                        </div>
                        <button class="btn btn-gold btn-lg flex-grow-1 add-to-cart" data-food-id="<?= $food->id ?>" data-quantity-input="detailQty">
                            <?= \icon('cart', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;']) ?>Add to Cart
                        </button>
                        <button class="btn btn-light-gold btn-lg favorite-btn" data-food-id="<?= $food->id ?>">
                            <?= \icon('heart', []) ?>
                        </button>
                    </div>
                <?php else: ?>
                    <button class="btn btn-secondary btn-lg w-100" disabled>Currently Unavailable</button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Nutrition -->
        <?php if ($nutrition): ?>
            <div class="row mt-5">
                <div class="col-12">
                    <h4 class="mb-3">Nutrition Facts</h4>
                    <div class="row g-3">
                        <?php foreach (['protein' => 'Protein', 'carbohydrates' => 'Carbs', 'fat' => 'Fat', 'fiber' => 'Fiber', 'sugar' => 'Sugar', 'sodium' => 'Sodium'] as $k => $label): ?>
                            <?php if (isset($nutrition->$k) && $nutrition->$k !== null): ?>
                                <div class="col-6 col-md-2">
                                    <div class="info-box text-center">
                                        <div class="fw-bold"><?= $nutrition->$k ?>g</div>
                                        <small class="text-muted"><?= $label ?></small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Reviews -->
        <div class="row mt-5">
            <div class="col-lg-8">
                <h4 class="mb-3">Customer Reviews</h4>
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $r): ?>
                        <div class="review-item">
                            <div class="d-flex justify-content-between">
                                <strong><?= \escape($r->user_name ?? $r->guest_name ?? 'Guest') ?></strong>
                                <span class="text-warning"><?= str_repeat('★', $r->rating) ?><?= str_repeat('☆', 5 - $r->rating) ?></span>
                            </div>
                            <?php if (!empty($r->title)): ?><div class="fw-semibold"><?= \escape($r->title) ?></div><?php endif; ?>
                            <p class="text-muted mb-0"><?= \escape($r->comment) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No reviews yet. Be the first to review!</p>
                <?php endif; ?>
            </div>
            <div class="col-lg-4">
                    <div class="glass-card p-4">
                        <h5>Write a Review</h5>
                        <form id="reviewForm">
                            <?= \csrfField() ?>
                            <input type="hidden" name="food_id" value="<?= $food->id ?>">
                            <div class="mb-2">
                                <select name="rating" class="form-select" required>
                                    <option value="">Select rating</option>
                                    <?php for ($i = 5; $i >= 1; $i--): ?><option value="<?= $i ?>"><?= $i ?> Star<?= $i > 1 ? 's' : '' ?></option><?php endfor; ?>
                                </select>
                            </div>
                            <div class="mb-2"><input type="text" name="name" class="form-control" placeholder="Your name" required></div>
                            <div class="mb-2"><input type="tel" name="phone" class="form-control" placeholder="Phone number" required></div>
                            <div class="mb-2"><input type="text" name="title" class="form-control" placeholder="Title (optional)"></div>
                            <div class="mb-2"><textarea name="comment" class="form-control" rows="3" placeholder="Your review" required></textarea></div>
                            <button type="submit" class="btn btn-gold w-100">Submit Review</button>
                        </form>
                    </div>
            </div>
        </div>

        <!-- Related -->
        <?php if (!empty($relatedFoods)): ?>
            <div class="mt-5">
                <h4 class="mb-3">You May Also Like</h4>
                <div class="row g-4">
                    <?php foreach ($relatedFoods as $rf): ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="food-card">
                                <div class="food-image">
                                    <a href="<?= \baseUrl('menu/' . $rf->slug) ?>">
                                        <img src="<?= \menuImageUrl($rf) ?>" alt="<?= \escape($rf->name) ?>" loading="lazy">
                                    </a>
                                </div>
                                <div class="food-body">
                                    <p class="food-category"><?= \escape($rf->category_name ?? '') ?></p>
                                    <h6 class="food-name"><a href="<?= \baseUrl('menu/' . $rf->slug) ?>" class="text-decoration-none text-reset"><?= \escape($rf->name) ?></a></h6>
                                </div>
                                <div class="food-footer">
                                    <div class="food-price"><?= \formatPrice($rf->final_price ?? $rf->price) ?></div>
                                    <button class="btn btn-light-gold btn-sm add-to-cart" data-food-id="<?= $rf->id ?>"><?= \icon('cart', []) ?></button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
