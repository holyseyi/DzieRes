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
    
    <!-- Google AdSense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8947299957289484"
         crossorigin="anonymous"></script>
    
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
                    <li class="nav-item"><a class="nav-link" href="<?= \baseUrl('menu') ?>">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= \baseUrl('about') ?>">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= \baseUrl('reservations') ?>">Reservations</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= \baseUrl('contact') ?>">Contact</a></li>
                </ul>
                
                <div class="navbar-actions d-flex align-items-center gap-2">
                    <button class="btn btn-icon" id="searchToggle" title="Search">
                        <?= \icon('search') ?>
                    </button>
                    
                    <a href="<?= \baseUrl('cart') ?>" class="btn btn-icon position-relative" title="Cart">
                        <?= \icon('cart') ?>
                        <span class="cart-badge" id="cartCount"><?= \getCartCount() ?></span>
                    </a>
                    
                    <?php if (\auth()): ?>
                    <div class="dropdown">
                        <button class="btn btn-icon dropdown-toggle" data-bs-toggle="dropdown">
                            <?= \icon('user') ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= \baseUrl('account') ?>"><?= \icon('home', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;']) ?>Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?= \baseUrl('account/orders') ?>"><?= \icon('clock', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;']) ?>My Orders</a></li>
                            <li><a class="dropdown-item" href="<?= \baseUrl('account/favorites') ?>"><?= \icon('heart', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;']) ?>Favorites</a></li>
                            <li><a class="dropdown-item" href="<?= \baseUrl('account/loyalty') ?>"><?= \icon('star', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;']) ?>Loyalty Points</a></li>
                            <?php if (\isAdmin()): ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= \baseUrl('admin') ?>"><?= \icon('cog', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;']) ?>Admin Panel</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= \baseUrl('logout') ?>"><?= \icon('sign-out', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;']) ?>Logout</a></li>
                        </ul>
                    </div>
                    <?php else: ?>
                    <a href="<?= \baseUrl('login') ?>" class="btn btn-outline-light btn-sm rounded-pill px-3 me-2">
                        <?= \icon('lock', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;']) ?>Admin
                    </a>
                    <button class="btn btn-gold btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#trackOrderModal">
                        <?= \icon('search-location', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;']) ?>Track Order
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- ============================================ -->
    <!-- ADVERTISEMENT BANNER -->
    <!-- ============================================ -->
    <div class="container-fluid px-0" style="background: #f8f9fa; margin-top: 76px; position: relative; height: 100px; z-index: 0; overflow: hidden;">
        <ins class="adsbygoogle"
             style="position:absolute; left:0; right:0; top:0; bottom:0; max-height:100px; overflow:hidden"
             data-ad-client="ca-pub-8947299957289484"
             data-ad-slot="3848114771"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>

    <!-- Search Overlay -->
    <div class="search-overlay" id="searchOverlay">
        <div class="search-overlay-content">
            <button class="search-close" id="searchClose">
                <?= \icon('times', ['style' => 'width:1.5em;height:1.5em;']) ?>
            </button>
            <div class="search-container">
                <form action="<?= \baseUrl('menu') ?>" method="GET" class="search-form">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control search-input" 
                               placeholder="Search menu items..." autocomplete="off" id="globalSearch">
                        <button class="btn btn-gold" type="submit">
                            <?= \icon('search', []) ?>
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
                <div class="toast-body"><?= \icon('check', ['style' => 'width:1.2em;height:1.2em;margin-right:0.5rem;vertical-align:-0.15em;']) ?><?= \escape($flash) ?></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php $flash = \sessionFlash('error'); if ($flash): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div class="toast show align-items-center text-bg-danger border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body"><?= \icon('exclamation', ['style' => 'width:1.2em;height:1.2em;margin-right:0.5rem;vertical-align:-0.15em;']) ?><?= \escape($flash) ?></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php $flash = \sessionFlash('info'); if ($flash): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div class="toast show align-items-center text-bg-info border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body"><?= \icon('info', ['style' => 'width:1.2em;height:1.2em;margin-right:0.5rem;vertical-align:-0.15em;']) ?><?= \escape($flash) ?></div>
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
                    <div class="col-lg-6">
                        <h3 class="brand-text">Dzie<span class="text-gold">Res</span></h3>
                        <p class="text-muted mt-2"><?= \escape(\getSetting('restaurant_tagline', 'Where Every Meal Tells a Story')) ?>. Experience the finest cuisine in an elegant atmosphere.</p>
                    </div>
                    
                    <div class="col-lg-6">
                        <h5 class="footer-title">Contact</h5>
                        <ul class="footer-contact">
                            <li>
                                <i class="icon icon-map-marker" style="width:1.1em;height:1.1em;color:var(--gold);margin-top:4px;flex-shrink:0;"></i>
                                <span><?= \escape(\getSetting('restaurant_address', '123 Independence Avenue, Accra, Ghana')) ?></span>
                            </li>
                            <li>
                                <i class="icon icon-phone" style="width:1.1em;height:1.1em;color:var(--gold);margin-top:4px;flex-shrink:0;"></i>
                                <span><?= \escape(\getSetting('restaurant_phone', '+233 50 000 0000')) ?></span>
                            </li>
                            <li>
                                <i class="icon icon-message" style="width:1.1em;height:1.1em;color:var(--gold);margin-top:4px;flex-shrink:0;"></i>
                                <span><?= \escape(\getSetting('restaurant_email', 'info@dzieres.com')) ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="container">
                <p class="mb-0 text-center">&copy; <?= date('Y') ?> DzieRes Restaurant. Crafted with <i class="icon icon-heart" style="width:1em;height:1em;color:#dc3545;vertical-align:-0.15em;"></i> in Accra</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top -->
    <button id="backToTop" class="btn btn-gold rounded-circle back-to-top" aria-label="Back to top">
        <?= \icon('arrow-up', ['width' => '24', 'height' => '24']) ?>
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
        
        // Search Toggle
        const searchToggle = document.getElementById('searchToggle');
        const searchOverlay = document.getElementById('searchOverlay');
        const searchClose = document.getElementById('searchClose');
        const globalSearch = document.getElementById('globalSearch');
        
        if (searchToggle && searchOverlay) {
            searchToggle.addEventListener('click', function() {
                searchOverlay.classList.add('active');
                setTimeout(function() {
                    if (globalSearch) globalSearch.focus();
                }, 100);
            });
        }
        
        if (searchClose && searchOverlay) {
            searchClose.addEventListener('click', function() {
                searchOverlay.classList.remove('active');
            });
        }
        
        if (searchOverlay) {
            searchOverlay.addEventListener('click', function(e) {
                if (e.target === searchOverlay) {
                    searchOverlay.classList.remove('active');
                }
            });
        }
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchOverlay && searchOverlay.classList.contains('active')) {
                searchOverlay.classList.remove('active');
            }
        });
    </script>
</body>
</html>