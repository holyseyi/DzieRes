<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= \escape($metaDescription ?? 'DzieRes Restaurant - Where Every Meal Tells a Story') ?>">
    <meta name="keywords" content="<?= \escape($metaKeywords ?? 'restaurant, fine dining, accra, ghana, food, cuisine') ?>">
    <meta property="og:title" content="<?= \escape($metaTitle ?? 'DzieRes Restaurant') ?>">
    <meta property="og:description" content="<?= \escape($metaDescription ?? 'Experience fine dining at DzieRes Restaurant') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= \currentUrl() ?>">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="<?= \currentUrl() ?>">
    
    <title><?= \escape($metaTitle ?? 'DzieRes Restaurant') ?></title>
    
    <script>
        window.BASE_URL = '<?= \baseUrl() ?>';
    </script>
    <!-- Bootstrap 5 -->
    <link href="<?= \asset('vendor/bootstrap/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= \asset('vendor/fontawesome/all.min.css') ?>" rel="stylesheet">
    <link href="<?= \asset('vendor/fonts/local-fonts.css') ?>" rel="stylesheet">
    <link href="<?= \asset('vendor/aos/aos.css') ?>" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= \asset('css/style.css') ?>" rel="stylesheet">
    
    <link rel="icon" type="image/svg+xml" href="<?= \asset('images/favicon.svg') ?>">
    
    <!-- Schema.org markup -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Restaurant",
        "name": "<?= \escape(\getSetting('restaurant_name', 'DzieRes')) ?>",
        "image": "<?= \asset('images/hero-bg.svg') ?>",
        "description": "<?= \escape(\getSetting('restaurant_tagline', 'Where Every Meal Tells a Story')) ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "<?= \escape(\getSetting('restaurant_address', '123 Independence Avenue, Accra, Ghana')) ?>",
            "addressLocality": "Accra",
            "addressCountry": "GH"
        },
        "telephone": "<?= \escape(\getSetting('restaurant_phone', '+233 50 000 0000')) ?>",
        "servesCuisine": ["Ghanaian", "International", "Continental"],
        "priceRange": "$$$",
        "openingHours": "<?= \escape(\getSetting('opening_hours', 'Mon-Sun: 7:00 AM - 11:00 PM')) ?>"
    }
    </script>
</head>
<body>
    <!-- ============================================ -->
    <!-- NAVBAR -->
    <!-- ============================================ -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="<?= \baseUrl() ?>">
                <span class="brand-text">Dzie<span class="text-gold">Res</span></span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= \baseUrl() ?>">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">About</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= \baseUrl('about') ?>">About Us</a></li>
                            <li><a class="dropdown-item" href="<?= \baseUrl('our-story') ?>">Our Story</a></li>
                            <li><a class="dropdown-item" href="<?= \baseUrl('our-chef') ?>">Our Chef</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="<?= \baseUrl('menu') ?>">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= \baseUrl('reservations') ?>">Reservations</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= \baseUrl('events') ?>">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= \baseUrl('contact') ?>">Contact</a></li>
                </ul>
                
                <div class="navbar-actions d-flex align-items-center gap-2">
                    <!-- Search -->
                    <button class="btn btn-icon" id="searchToggle" title="Search">
                        <i class="fas fa-search"></i>
                    </button>
                    
                    <!-- Cart -->
                    <a href="<?= \baseUrl('cart') ?>" class="btn btn-icon position-relative" title="Cart">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="cart-badge" id="cartCount"><?= \getCartCount() ?></span>
                    </a>
                    
                    <!-- Theme Toggle -->
                    <button class="btn btn-icon" id="themeToggle" title="Toggle theme">
                        <i class="fas fa-moon"></i>
                    </button>
                    
                    <!-- Auth -->
                    <?php if (\auth()): ?>
                    <div class="dropdown">
                        <button class="btn btn-icon dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= \baseUrl('account') ?>"><i class="fas fa-dashboard me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?= \baseUrl('account/orders') ?>"><i class="fas fa-receipt me-2"></i>My Orders</a></li>
                            <li><a class="dropdown-item" href="<?= \baseUrl('account/favorites') ?>"><i class="fas fa-heart me-2"></i>Favorites</a></li>
                            <li><a class="dropdown-item" href="<?= \baseUrl('account/loyalty') ?>"><i class="fas fa-gem me-2"></i>Loyalty Points</a></li>
                            <?php if (\isAdmin()): ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= \baseUrl('admin') ?>"><i class="fas fa-shield-alt me-2"></i>Admin Panel</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= \baseUrl('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                    <?php else: ?>
                    <a href="<?= \baseUrl('login') ?>" class="btn btn-outline-light btn-sm rounded-pill px-3 me-2">
                        <i class="fas fa-lock me-1"></i>Admin
                    </a>
                    <button class="btn btn-gold btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#trackOrderModal">
                        <i class="fas fa-search-location me-1"></i>Track Order
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Search Overlay -->
    <div class="search-overlay" id="searchOverlay">
        <div class="search-overlay-content">
            <button class="btn-close search-close" id="searchClose"></button>
            <div class="search-container">
                <form action="<?= \baseUrl('menu') ?>" method="GET" class="search-form">
                    <div class="input-group input-group-lg">
                        <input type="text" name="search" class="form-control search-input" 
                               placeholder="Search menu items..." autocomplete="off" id="globalSearch">
                        <button class="btn btn-gold" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <div class="search-results mt-3" id="searchResults"></div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- FLASH MESSAGES -->
    <!-- ============================================ -->
    <?php $flash = \sessionFlash('success'); if ($flash): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div class="toast show align-items-center text-bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body"><i class="fas fa-check-circle me-2"></i><?= \escape($flash) ?></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php $flash = \sessionFlash('error'); if ($flash): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div class="toast show align-items-center text-bg-danger border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body"><i class="fas fa-exclamation-circle me-2"></i><?= \escape($flash) ?></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php $flash = \sessionFlash('info'); if ($flash): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div class="toast show align-items-center text-bg-info border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body"><i class="fas fa-info-circle me-2"></i><?= \escape($flash) ?></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ============================================ -->
    <!-- MAIN CONTENT -->
    <!-- ============================================ -->
    <main>
        <?php if (isset($contentView)): ?>
            <?php \view($contentView, $data ?? []); ?>
        <?php else: ?>
            <?php require $__viewPath ?? ''; ?>
        <?php endif; ?>
    </main>

    <!-- ============================================ -->
    <!-- FOOTER -->
    <!-- ============================================ -->
    <footer class="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="footer-brand">
                            <h3 class="brand-text">Dzie<span class="text-gold">Res</span></h3>
                            <p class="mt-3 text-muted"><?= \escape(\getSetting('restaurant_tagline', 'Where Every Meal Tells a Story')) ?>. Experience the finest cuisine in an elegant atmosphere, crafted with passion and served with excellence.</p>
                            <div class="social-links mt-3">
                                <a href="#" class="social-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-link" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-link" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                                <a href="#" class="social-link" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-md-4">
                        <h5 class="footer-title">Quick Links</h5>
                        <ul class="footer-links">
                            <li><a href="<?= \baseUrl('about') ?>">About Us</a></li>
                            <li><a href="<?= \baseUrl('menu') ?>">Our Menu</a></li>
                            <li><a href="<?= \baseUrl('reservations') ?>">Reservations</a></li>
                            <li><a href="<?= \baseUrl('events') ?>">Events</a></li>
                            <li><a href="<?= \baseUrl('gallery') ?>">Gallery</a></li>
                            <li><a href="<?= \baseUrl('blog') ?>">Blog</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-2 col-md-4">
                        <h5 class="footer-title">Support</h5>
                        <ul class="footer-links">
                            <li><a href="<?= \baseUrl('faqs') ?>">FAQs</a></li>
                            <li><a href="<?= \baseUrl('contact') ?>">Contact Us</a></li>
                            <li><a href="<?= \baseUrl('careers') ?>">Careers</a></li>
                            <li><a href="<?= \baseUrl('privacy-policy') ?>">Privacy Policy</a></li>
                            <li><a href="<?= \baseUrl('terms') ?>">Terms of Service</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-4 col-md-4">
                        <h5 class="footer-title">Contact Info</h5>
                        <ul class="footer-contact">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?= \escape(\getSetting('restaurant_address', '123 Independence Avenue, Accra, Ghana')) ?></span>
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <span><?= \escape(\getSetting('restaurant_phone', '+233 50 000 0000')) ?></span>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span><?= \escape(\getSetting('restaurant_email', 'info@dzieres.com')) ?></span>
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                <span><?= \escape(\getSetting('opening_hours', 'Mon - Sun: 7:00 AM - 11:00 PM')) ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; <?= date('Y') ?> DzieRes Restaurant. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0">Crafted with <i class="fas fa-heart text-danger"></i> in Accra</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top -->
    <button id="backToTop" class="btn btn-gold rounded-circle back-to-top" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Track Order Modal -->
    <div class="modal fade" id="trackOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Track Your Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="trackOrderForm">
                        <div class="mb-3">
                            <label class="form-label">Order Tracking Code</label>
                            <input type="text" name="number" class="form-control" placeholder="e.g. ORD-ABC12345-20260721" required>
                            <div class="form-text">Enter the tracking code from your receipt.</div>
                        </div>
                        <button type="submit" class="btn btn-gold w-100">Track Order</button>
                    </form>
                    <script>
                        document.getElementById('trackOrderForm').addEventListener('submit', function(e) {
                            e.preventDefault();
                            var code = this.number.value.trim();
                            if (code) {
                                window.location.href = '<?= \baseUrl('order/track') ?>/' + encodeURIComponent(code);
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?= \asset('vendor/bootstrap/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= \asset('vendor/aos/aos.js') ?>"></script>
    <script src="<?= \asset('vendor/chart.js/chart.min.js') ?>"></script>
    <script src="<?= \asset('js/main.js') ?>"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    </script>
</body>
</html>