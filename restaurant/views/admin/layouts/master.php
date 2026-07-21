<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \escape($metaTitle ?? 'Admin - DzieRes') ?></title>
    
    <script>
        window.BASE_URL = '<?= \baseUrl() ?>';
    </script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.min.css" rel="stylesheet">
    <link href="<?= \asset('css/style.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <a href="<?= \baseUrl('admin') ?>" class="text-decoration-none text-white">
                    <span class="brand-text">Dzie<span class="text-gold">Res</span></span>
                    <small class="d-block text-muted">Admin Panel</small>
                </a>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false || $_SERVER['REQUEST_URI'] === \baseUrl('admin') || rtrim($_SERVER['REQUEST_URI'], '/') === \baseUrl('admin') ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin') ?>"><i class="fas fa-chart-pie"></i> Dashboard</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/orders') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/orders') ?>"><i class="fas fa-receipt"></i> Orders</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/kitchen') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/kitchen') ?>"><i class="fas fa-kitchen-set"></i> Kitchen Display</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/reservations') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/reservations') ?>"><i class="fas fa-calendar-check"></i> Reservations</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/menu') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/menu') ?>"><i class="fas fa-utensils"></i> Menu</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/categories') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/categories') ?>"><i class="fas fa-tags"></i> Categories</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/customers') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/customers') ?>"><i class="fas fa-users"></i> Customers</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/employees') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/employees') ?>"><i class="fas fa-user-tie"></i> Employees</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/inventory') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/inventory') ?>"><i class="fas fa-boxes"></i> Inventory</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/tables') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/tables') ?>"><i class="fas fa-chair"></i> Tables</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/coupons') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/coupons') ?>"><i class="fas fa-ticket"></i> Coupons</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/promotions') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/promotions') ?>"><i class="fas fa-bullhorn"></i> Promotions</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/reviews') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/reviews') ?>"><i class="fas fa-star"></i> Reviews</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/gallery') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/gallery') ?>"><i class="fas fa-images"></i> Gallery</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/blog') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/blog') ?>"><i class="fas fa-blog"></i> Blog</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/reports') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/reports') ?>"><i class="fas fa-chart-bar"></i> Reports</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/settings') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/settings') ?>"><i class="fas fa-cog"></i> Settings</a>
                </div>
                <hr class="text-white-50 mx-3">
                <div class="nav-item">
                    <a href="<?= \baseUrl() ?>"><i class="fas fa-globe"></i> View Website</a>
                </div>
                <div class="nav-item">
                    <a href="<?= \baseUrl('logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <header class="admin-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h5 class="mb-0"><?= \escape($pageTitle ?? 'Dashboard') ?></h5>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="dropdown">
                            <button class="btn btn-icon btn-sm position-relative" id="notificationBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <span class="cart-badge" id="notifCount">0</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationBtn" style="width: 320px; max-height: 420px; overflow-y: auto;">
                                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                    <strong>Notifications</strong>
                                    <button class="btn btn-sm btn-link p-0" id="markAllRead">Mark all read</button>
                                </div>
                                <div id="notificationList">
                                    <div class="text-center text-muted py-4">Loading…</div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>
                                <?php $user = \auth(); if ($user): ?>
                                <?= \escape($user->firstname) ?>
                                <?php endif; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= \baseUrl('admin/settings') ?>"><i class="fas fa-cog me-2"></i>Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= \baseUrl('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <div class="admin-content">
                <?php if (isset($contentView)): ?>
                    <?php \view($contentView, $data ?? []); ?>
                <?php else: ?>
                    <?php require $__viewPath ?? ''; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= \asset('js/main.js') ?>"></script>
    <script src="<?= \asset('js/admin.js') ?>"></script>
    
    <script>
        // Sidebar toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.toggle('active');
        });
    </script>
</body>
</html>