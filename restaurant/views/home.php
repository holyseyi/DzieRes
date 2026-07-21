<!-- ============================================ -->
<!-- HERO -->
<!-- ============================================ -->
<section class="hero-section" id="home">
    <div class="hero-bg"></div>
    <div class="hero-content" data-aos="fade-up">
        <div class="hero-badge">Welcome to DzieRes</div>
        <h1 class="hero-title">Where Every Meal<br>Tells a Story</h1>
        <p class="hero-subtitle">Experience Fine Dining at Its Best</p>
        <div class="hero-buttons">
            <a href="<?= \baseUrl('menu') ?>" class="btn btn-gold btn-lg">
                <?= \icon('utensils', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;']) ?>Order Food
            </a>
            <a href="<?= \baseUrl('reservations') ?>" class="btn btn-outline-light btn-lg">
                <?= \icon('calendar-check', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;']) ?>Book a Table
            </a>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- POPULAR MENU -->
<!-- ============================================ -->
<section class="section-padding" id="menu">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <p class="section-subtitle">Our Menu</p>
            <h2 class="section-title">Popular Dishes</h2>
            <div class="section-divider mx-auto"></div>
            <p class="text-muted">Explore our complete selection of dishes</p>
        </div>
        
        <div class="row g-4">
            <?php if (!empty($allFoods)): ?>
                <?php foreach ($allFoods as $food): ?>
                <div class="col-md-6 col-lg-4 col-xl-3" data-aos="fade-up">
                    <div class="food-card">
                        <div class="food-image">
                            <a href="<?= \baseUrl('menu/' . $food->slug) ?>">
                                <img src="<?= \menuImageUrl($food) ?>" alt="<?= \escape($food->name) ?>" loading="lazy">
                            </a>
                            <div class="food-overlay">
                                <button class="btn btn-gold btn-sm add-to-cart" data-food-id="<?= $food->id ?>">
                                    <?= \icon('cart', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>Add to Cart
                                </button>
                            </div>
                        </div>
                        <div class="food-body">
                            <p class="food-category"><?= \escape($food->category_name ?? '') ?></p>
                            <h5 class="food-name">
                                <a href="<?= \baseUrl('menu/' . $food->slug) ?>" class="text-decoration-none text-reset stretched-link-target">
                                    <?= \escape($food->name) ?>
                                </a>
                            </h5>
                            <p class="food-description"><?= \truncate($food->description ?? '', 70) ?></p>
                        </div>
                        <div class="food-footer">
                            <div class="food-price">
                                <?= \formatPrice($food->final_price ?? $food->price) ?>
                            </div>
                            <button class="btn btn-light-gold btn-sm favorite-btn" data-food-id="<?= $food->id ?>">
                                <?= \icon('heart', []) ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <?= \icon('utensils', ['style' => 'width:3em;height:3em;color:#6c757d;']) ?>
                    <p class="text-muted">No menu items available yet.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="<?= \baseUrl('menu') ?>" class="btn btn-gold btn-lg">
                <?= \icon('utensils', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;']) ?>View Full Menu
            </a>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- TESTIMONIALS -->
<!-- ============================================ -->
<section class="section-padding bg-light-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <p class="section-subtitle">What Our Guests Say</p>
            <h2 class="section-title">Testimonials</h2>
            <div class="section-divider mx-auto"></div>
        </div>
        
        <div class="row g-4">
            <?php if (!empty($testimonials)): ?>
                <?php foreach ($testimonials as $testimonial): ?>
                <div class="col-md-6 col-lg-4" data-aos="fade-up">
                    <div class="testimonial-card">
                        <div class="quote-icon"><?= \icon('quote-left', []) ?></div>
                        <p class="testimonial-text"><?= \escape($testimonial->content) ?></p>
                        <div class="testimonial-author">
                            <img src="<?= \uploadUrl($testimonial->image) ?>" alt="<?= \escape($testimonial->guest_name) ?>" class="author-avatar">
                            <div>
                                <h6 class="mb-0"><?= \escape($testimonial->guest_name) ?></h6>
                                <small class="text-muted"><?= \escape($testimonial->guest_title ?? 'Regular Guest') ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php 
                $defaultTestimonials = [
                    ['name' => 'Sarah Johnson', 'title' => 'Food Critic', 'content' => 'An absolutely remarkable dining experience. The flavors, presentation, and service were all world-class. DzieRes has set a new standard for fine dining in Accra.'],
                    ['name' => 'James Mensah', 'title' => 'Regular Guest', 'content' => 'I have been coming here for years and the quality never disappoints. The chef\'s special is always a masterpiece. Highly recommended for any special occasion.'],
                    ['name' => 'Emily Osei', 'title' => 'Event Planner', 'content' => 'We hosted our company dinner at DzieRes and it was perfect. The private dining area, the attentive staff, and the incredible menu made it an unforgettable evening.'],
                ];
                foreach ($defaultTestimonials as $t): 
                ?>
                <div class="col-md-6 col-lg-4" data-aos="fade-up">
                    <div class="testimonial-card">
                        <div class="quote-icon"><?= \icon('quote-left', []) ?></div>
                        <p class="testimonial-text"><?= $t['content'] ?></p>
                        <div class="testimonial-author">
                            <img src="<?= \asset('images/placeholders/testimonial-avatar.jpg') ?>" alt="<?= $t['name'] ?>" class="author-avatar">
                            <div>
                                <h6 class="mb-0"><?= $t['name'] ?></h6>
                                <small class="text-muted"><?= $t['title'] ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- NEWSLETTER -->
<!-- ============================================ -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content text-center" data-aos="fade-up">
            <p class="section-subtitle">Stay Connected</p>
            <h2 class="section-title text-white mb-3">Subscribe to Our Newsletter</h2>
            <p class="text-white-50 mb-4">Get exclusive offers and new menu updates delivered to your inbox.</p>
            
            <form class="newsletter-form" method="POST" action="<?= \baseUrl('newsletter/subscribe') ?>">
                <?= \csrfField() ?>
                <div class="input-group">
                    <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                    <button class="btn btn-gold" type="submit">
                        <?= \icon('send', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;']) ?>Subscribe
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
