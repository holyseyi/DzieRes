<!-- ============================================ -->
<!-- HERO SECTION -->
<!-- ============================================ -->
<section class="hero-section" id="home">
    <div class="hero-bg"></div>
    <div class="hero-content" data-aos="fade-up">
        <div class="hero-badge">Welcome to DzieRes</div>
        <h1 class="hero-title">Where Every Meal<br>Tells a Story</h1>
        <p class="hero-subtitle">Experience Fine Dining at Its Best</p>
        <p class="hero-description">Indulge in exquisite cuisine crafted with passion, served in an elegant atmosphere that transforms every meal into an unforgettable experience.</p>
        <div class="hero-buttons">
            <a href="<?= \baseUrl('reservations') ?>" class="btn btn-gold btn-lg">
                <i class="fas fa-calendar-check me-2"></i>Book a Table
            </a>
            <a href="<?= \baseUrl('menu') ?>" class="btn btn-outline-light btn-lg">
                <i class="fas fa-utensils me-2"></i>Order Food
            </a>
        </div>
    </div>
    
    <div class="hero-stats">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-3">
                    <div class="hero-stat-item" data-aos="fade-up" data-aos-delay="100">
                        <div class="stat-number" data-count="<?= $stats['years']->count ?? 5 ?>">0</div>
                        <div class="stat-label">Years in Business</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="hero-stat-item" data-aos="fade-up" data-aos-delay="200">
                        <div class="stat-number" data-count="<?= $stats['meals_served']->count ?? 50000 ?>">0</div>
                        <div class="stat-label">Meals Served</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="hero-stat-item" data-aos="fade-up" data-aos-delay="300">
                        <div class="stat-number" data-count="<?= $stats['happy_customers']->count ?? 10000 ?>">0</div>
                        <div class="stat-label">Happy Customers</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="hero-stat-item" data-aos="fade-up" data-aos-delay="400">
                        <div class="stat-number" data-count="<?= $stats['branches']->count ?? 3 ?>">0</div>
                        <div class="stat-label">Branches</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- ALL FOODS SECTION -->
<!-- ============================================ -->
<section class="section-padding" id="menu">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <p class="section-subtitle">Discover Our Menu</p>
            <h2 class="section-title">All Foods</h2>
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
                            <?php if ($food->discount_percent > 0): ?>
                                <span class="discount-badge">-<?= $food->discount_percent ?>%</span>
                            <?php endif; ?>
                            <div class="food-overlay">
                                <button class="btn btn-gold btn-sm add-to-cart" data-food-id="<?= $food->id ?>">
                                    <i class="fas fa-shopping-bag me-1"></i>Add to Cart
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
                            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                <span class="badge bg-light text-dark"><i class="far fa-clock me-1"></i><?= $food->preparation_time ?? 15 ?> min</span>
                                <span class="badge bg-light text-dark"><i class="fas fa-fire me-1"></i><?= $food->calories ?? 0 ?> cal</span>
                            </div>
                        </div>
                        <div class="food-footer">
                            <div class="food-price">
                                <?= \formatPrice($food->final_price ?? $food->price) ?>
                                <?php if ($food->discount_percent > 0): ?>
                                    <span class="original-price"><?= \formatPrice($food->price) ?></span>
                                <?php endif; ?>
                            </div>
                            <button class="btn btn-light-gold btn-sm favorite-btn" data-food-id="<?= $food->id ?>">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No menu items available yet.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="<?= \baseUrl('menu') ?>" class="btn btn-gold btn-lg">
                <i class="fas fa-utensils me-2"></i>View Full Menu
            </a>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- TODAY'S SPECIAL -->
<!-- ============================================ -->
<?php if (!empty($todaysSpecial)): ?>
<section class="section-padding bg-light-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <p class="section-subtitle">Chef's Selection</p>
            <h2 class="section-title">Today's Special</h2>
            <div class="section-divider mx-auto"></div>
        </div>
        
        <div class="row g-4">
            <?php foreach ($todaysSpecial as $food): ?>
            <div class="col-md-6 col-lg-3" data-aos="fade-up">
                <div class="food-card">
                    <div class="food-image">
                        <img src="<?= \menuImageUrl($food) ?>" alt="<?= \escape($food->name) ?>">
                        <div class="food-badge">
                            <span class="badge bg-gold">Today's Special</span>
                        </div>
                        <?php if ($food->discount_percent > 0): ?>
                        <span class="discount-badge">-<?= $food->discount_percent ?>%</span>
                        <?php endif; ?>
                        <div class="food-overlay">
                            <button class="btn btn-gold btn-sm add-to-cart" data-food-id="<?= $food->id ?>">
                                <i class="fas fa-shopping-bag me-1"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                    <div class="food-body">
                        <p class="food-category"><?= \escape($food->category_name ?? '') ?></p>
                        <h5 class="food-name"><?= \escape($food->name) ?></h5>
                        <p class="food-description"><?= \truncate($food->description ?? '', 80) ?></p>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-light text-dark"><i class="far fa-clock me-1"></i><?= $food->preparation_time ?? 15 ?> min</span>
                            <span class="badge bg-light text-dark"><i class="fas fa-fire me-1"></i><?= $food->calories ?? 0 ?> cal</span>
                        </div>
                    </div>
                    <div class="food-footer">
                        <div class="food-price">
                            <?= \formatPrice($food->final_price ?? $food->price) ?>
                            <?php if ($food->discount_percent > 0): ?>
                            <span class="original-price"><?= \formatPrice($food->price) ?></span>
                            <?php endif; ?>
                        </div>
                        <button class="btn btn-light-gold btn-sm favorite-btn" data-food-id="<?= $food->id ?>">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============================================ -->
<!-- FEATURED MEALS -->
<!-- ============================================ -->
<section class="section-padding">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-up">
            <div>
                <p class="section-subtitle mb-0">Premium Selection</p>
                <h2 class="section-title mb-0">Featured Meals</h2>
            </div>
            <a href="<?= \baseUrl('menu') ?>" class="btn btn-outline-gold d-none d-md-inline-flex">
                View All Menu <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        
        <div class="row g-4">
            <?php if (!empty($featuredMeals)): ?>
                <?php foreach ($featuredMeals as $food): ?>
                <div class="col-md-6 col-lg-3" data-aos="fade-up">
                    <div class="food-card">
                        <div class="food-image">
                            <img src="<?= \menuImageUrl($food) ?>" alt="<?= \escape($food->name) ?>">
                            <?php if ($food->discount_percent > 0): ?>
                            <span class="discount-badge">-<?= $food->discount_percent ?>%</span>
                            <?php endif; ?>
                            <div class="food-overlay">
                                <button class="btn btn-gold btn-sm add-to-cart" data-food-id="<?= $food->id ?>">
                                    <i class="fas fa-shopping-bag me-1"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                        <div class="food-body">
                            <p class="food-category"><?= \escape($food->category_name ?? '') ?></p>
                            <h5 class="food-name"><?= \escape($food->name) ?></h5>
                            <p class="food-description"><?= \truncate($food->description ?? '', 80) ?></p>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-light text-dark"><i class="far fa-clock me-1"></i><?= $food->preparation_time ?? 15 ?> min</span>
                                <span class="badge bg-light text-dark"><i class="fas fa-fire me-1"></i><?= $food->calories ?? 0 ?> cal</span>
                            </div>
                        </div>
                        <div class="food-footer">
                            <div class="food-price">
                                <?= \formatPrice($food->final_price ?? $food->price) ?>
                                <?php if ($food->discount_percent > 0): ?>
                                <span class="original-price"><?= \formatPrice($food->price) ?></span>
                                <?php endif; ?>
                            </div>
                            <button class="btn btn-light-gold btn-sm favorite-btn" data-food-id="<?= $food->id ?>">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="col-md-6 col-lg-3" data-aos="fade-up">
                    <div class="food-card">
                        <div class="food-image">
                            <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop" alt="Featured Dish">
                            <div class="food-overlay">
                                <button class="btn btn-gold btn-sm"><i class="fas fa-shopping-bag me-1"></i>Add to Cart</button>
                            </div>
                        </div>
                        <div class="food-body">
                            <p class="food-category">Category</p>
                            <h5 class="food-name">Signature Dish Name</h5>
                            <p class="food-description">A delightful culinary creation that will tantalize your taste buds.</p>
                        </div>
                        <div class="food-footer">
                            <div class="food-price">₵45.00</div>
                            <button class="btn btn-light-gold btn-sm"><i class="far fa-heart"></i></button>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-4 d-md-none">
            <a href="<?= \baseUrl('menu') ?>" class="btn btn-outline-gold">View All Menu <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- CHEF RECOMMENDATIONS -->
<!-- ============================================ -->
<?php if (!empty($chefRecommendations)): ?>
<section class="section-padding bg-dark-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <p class="section-subtitle">From Our Kitchen</p>
            <h2 class="section-title text-white">Chef's Recommendations</h2>
            <div class="section-divider mx-auto"></div>
            <p class="text-white-50">Handpicked by our master chefs for an extraordinary dining experience</p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($chefRecommendations as $food): ?>
            <div class="col-md-6 col-lg-3" data-aos="fade-up">
                <div class="food-card">
                    <div class="food-image">
                        <img src="<?= \menuImageUrl($food) ?>" alt="<?= \escape($food->name) ?>">
                        <div class="food-badge">
                            <span class="badge bg-warning text-dark">Chef's Pick</span>
                        </div>
                        <div class="food-overlay">
                            <button class="btn btn-gold btn-sm add-to-cart" data-food-id="<?= $food->id ?>">
                                <i class="fas fa-shopping-bag me-1"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                    <div class="food-body">
                        <p class="food-category"><?= \escape($food->category_name ?? '') ?></p>
                        <h5 class="food-name"><?= \escape($food->name) ?></h5>
                        <p class="food-description"><?= \truncate($food->description ?? '', 80) ?></p>
                    </div>
                    <div class="food-footer">
                        <div class="food-price"><?= \formatPrice($food->final_price ?? $food->price) ?></div>
                        <button class="btn btn-light-gold btn-sm favorite-btn" data-food-id="<?= $food->id ?>">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

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
                        <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
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
                        <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                        <p class="testimonial-text"><?= $t['content'] ?></p>
                        <div class="testimonial-author">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($t['name']) ?>&background=c9a84c&color=fff&size=50" alt="<?= $t['name'] ?>" class="author-avatar">
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
<!-- GALLERY -->
<!-- ============================================ -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <p class="section-subtitle">Visual Journey</p>
            <h2 class="section-title">Our Gallery</h2>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
    
    <div class="container-fluid px-3">
        <div class="gallery-grid" data-aos="fade-up">
            <?php if (!empty($gallery)): ?>
                <?php foreach ($gallery as $index => $image): ?>
                <div class="gallery-item" onclick="openLightbox('<?= \uploadUrl($image->image) ?>', '<?= \escape($image->title ?? '') ?>')">
                    <img src="<?= \uploadUrl($image->image) ?>" alt="<?= \escape($image->title ?? 'Gallery Image') ?>">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus fa-2x text-white"></i>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php 
                $galleryImages = [
                    'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1550966871-3ed3cdb51f3a?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=600&h=400&fit=crop',
                ];
                foreach ($galleryImages as $img): 
                ?>
                <div class="gallery-item" onclick="openLightbox('<?= $img ?>')">
                    <img src="<?= $img ?>" alt="Gallery Image">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus fa-2x text-white"></i>
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
            <p class="text-white-50 mb-4">Get exclusive offers, new menu updates, and special event invitations delivered to your inbox.</p>
            
            <form class="newsletter-form" method="POST" action="<?= \baseUrl('newsletter/subscribe') ?>">
                <?= \csrfField() ?>
                <div class="input-group">
                    <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                    <button class="btn btn-gold" type="submit">
                        <i class="fas fa-paper-plane me-2"></i>Subscribe
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- BLOG PREVIEW -->
<!-- ============================================ -->
<?php if (!empty($blogPosts)): ?>
<section class="section-padding bg-light-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-up">
            <div>
                <p class="section-subtitle mb-0">Latest Updates</p>
                <h2 class="section-title mb-0">From Our Blog</h2>
            </div>
            <a href="<?= \baseUrl('blog') ?>" class="btn btn-outline-gold d-none d-md-inline-flex">
                View All Posts <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        
        <div class="row g-4">
            <?php foreach ($blogPosts as $post): ?>
            <div class="col-md-6 col-lg-4" data-aos="fade-up">
                <div class="food-card">
                    <div class="food-image" style="height: 200px;">
                        <img src="<?= \uploadUrl($post->image) ?>" alt="<?= \escape($post->title) ?>">
                    </div>
                    <div class="food-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-gold"><?= \escape($post->category_name ?? 'General') ?></span>
                            <small class="text-muted"><?= \formatDate($post->published_at ?? $post->created_at) ?></small>
                        </div>
                        <h5 class="food-name"><?= \escape($post->title) ?></h5>
                        <p class="food-description"><?= \truncate($post->excerpt ?? $post->content ?? '', 100) ?></p>
                        <a href="<?= \baseUrl('blog/' . $post->slug) ?>" class="btn btn-link text-gold p-0">
                            Read More <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============================================ -->
<!-- GOOGLE MAPS -->
<!-- ============================================ -->
<section class="section-padding p-0">
    <div class="container-fluid p-0">
        <div style="height: 400px; width: 100%;">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3970.0!2d-0.186964!3d5.603717!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMzYnMTMuNCJOIDDCsDExJzEzLjEiVw!5e0!3m2!1sen!2sgh!4v1" 
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>