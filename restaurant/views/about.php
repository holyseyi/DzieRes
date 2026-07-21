<?php
/**
 * About Us View
 */
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">Our Story</p>
            <h1 class="page-title">About Us</h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <img src="<?= \asset('images/placeholders/about.jpg') ?>" alt="Restaurant interior" class="img-fluid rounded-4 shadow">
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <p class="section-subtitle">Who We Are</p>
                <h2 class="section-title">Crafting Memories Through Food</h2>
                <p class="text-muted">DzieRes Restaurant was born from a simple belief: that great food brings people together. Since opening our doors, we have dedicated ourselves to delivering exceptional dining experiences that celebrate both local Ghanaian flavors and international cuisine.</p>
                <p class="text-muted">Our team of passionate chefs, warm hospitality staff, and visionary leadership work in harmony to make every visit unforgettable.</p>
                <div class="row g-3 mt-2">
                    <div class="col-4 text-center"><div class="stat-number" data-count="5">0</div><small class="text-muted">Years</small></div>
                    <div class="col-4 text-center"><div class="stat-number" data-count="120">0</div><small class="text-muted">Dishes</small></div>
                    <div class="col-4 text-center"><div class="stat-number" data-count="10000">0</div><small class="text-muted">Guests</small></div>
                </div>
                <a href="<?= \baseUrl('menu') ?>" class="btn btn-gold btn-lg mt-4">Explore Our Menu</a>
            </div>
        </div>
    </div>
</section>

<section class="section-padding bg-light-section">
    <div class="container">
        <div class="row g-4 text-center">
            <?php foreach ([
                ['icon' => 'leaf', 'title' => 'Fresh Ingredients', 'text' => 'We source the finest local and seasonal produce daily.'],
                ['icon' => 'award', 'title' => 'Award Winning', 'text' => 'Recognized for culinary excellence and service.'],
                ['icon' => 'heart', 'title' => 'Made With Love', 'text' => 'Every plate is prepared with passion and care.'],
                ['icon' => 'users', 'title' => 'Community First', 'text' => 'We support local farmers and our community.'],
            ] as $v): ?>
                <div class="col-md-3" data-aos="fade-up">
                    <div class="glass-card p-4 h-100">
                        <i class="fas fa-<?= $v['icon'] ?> fa-2x text-gold mb-3"></i>
                        <h5><?= $v['title'] ?></h5>
                        <p class="text-muted small mb-0"><?= $v['text'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
