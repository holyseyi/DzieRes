<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \escape($metaTitle ?? 'Admin - DzieRes') ?></title>
    
    <script>
        window.BASE_URL = '<?= \baseUrl() ?>';
    </script>
    
    <link href="<?= \asset('vendor/bootstrap/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= \asset('vendor/fontawesome/all.min.css') ?>" rel="stylesheet">
    <link href="<?= \asset('vendor/fonts/local-fonts.css') ?>" rel="stylesheet">
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
                    <a href="<?= \baseUrl('admin') ?>"><?= \icon('chart-pie', []) ?> Dashboard</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/orders') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/orders') ?>"><?= \icon('receipt', []) ?> Orders</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/kitchen') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/kitchen') ?>"><?= \icon('kitchen-set', []) ?> Kitchen</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/menu') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/menu') ?>"><?= \icon('utensils', []) ?> Menu</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/reservations') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/reservations') ?>"><?= \icon('calendar-check', []) ?> Reservations</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/customers') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/customers') ?>"><?= \icon('user-plus', []) ?> Customers</a>
                </div>
                <div class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/settings') !== false ? 'active' : '' ?>">
                    <a href="<?= \baseUrl('admin/settings') ?>"><?= \icon('cog', []) ?> Settings</a>
                </div>
                <hr class="text-white-50 mx-3">
                <div class="nav-item">
                    <a href="<?= \baseUrl() ?>"><?= \icon('globe', []) ?> View Website</a>
                </div>
                <div class="nav-item">
                    <a href="<?= \baseUrl('logout') ?>"><?= \icon('sign-out', []) ?> Logout</a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <header class="admin-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle">
                            <?= \icon('bars', []) ?>
                        </button>
                        <h5 class="mb-0"><?= \escape($pageTitle ?? 'Dashboard') ?></h5>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="dropdown">
                            <button class="btn btn-icon btn-sm position-relative" id="notificationBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= \icon('bell', []) ?>
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
                                <?= \icon('user', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>
                                <?php $user = \auth(); if ($user): ?>
                                <?= \escape($user->firstname) ?>
                                <?php endif; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= \baseUrl('admin/settings') ?>"><?= \icon('cog', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;']) ?>Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= \baseUrl('logout') ?>"><?= \icon('sign-out', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;']) ?>Logout</a></li>
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

    <script src="<?= \asset('vendor/bootstrap/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= \asset('vendor/chart.js/chart.min.js') ?>"></script>
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