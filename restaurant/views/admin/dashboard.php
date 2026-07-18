<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card-admin">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="stat-number"><?= number_format($totalOrders) ?></div>
            <div class="text-muted">Total Orders</div>
            <small class="text-warning"><?= $pendingOrders ?> pending</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-number"><?= \formatPrice($totalRevenue) ?></div>
            <div class="text-muted">Total Revenue</div>
            <small class="text-success">All time</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin">
            <div class="stat-icon bg-info bg-opacity-10 text-info">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number"><?= number_format($totalCustomers) ?></div>
            <div class="text-muted">Customers</div>
            <small class="text-info">Registered users</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin">
            <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-number"><?= $lowStockItems ?></div>
            <div class="text-muted">Low Stock Items</div>
            <small class="text-danger">Needs attention</small>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0"><i class="fas fa-chart-line me-2 text-gold"></i>Revenue Overview</h6>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-secondary active" data-period="7">7 Days</button>
                    <button class="btn btn-outline-secondary" data-period="30">30 Days</button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0"><i class="fas fa-chart-pie me-2 text-gold"></i>Popular Categories</h6>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0"><i class="fas fa-clock me-2 text-gold"></i>Recent Orders</h6>
                <a href="<?= \baseUrl('admin/orders') ?>" class="btn btn-sm btn-gold">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentOrders)): ?>
                                <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td><a href="<?= \baseUrl('admin/orders/' . $order->id) ?>" class="text-decoration-none"><?= \escape($order->order_number) ?></a></td>
                                    <td><?= \escape($order->customer_name ?? 'Guest') ?></td>
                                    <td><?= \formatPrice($order->total_amount) ?></td>
                                    <td><span class="status-badge status-<?= $order->status ?>"><?= ucfirst($order->status) ?></span></td>
                                    <td><small><?= \formatDateTime($order->created_at, 'M d, H:i') ?></small></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">No orders yet</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0"><i class="fas fa-calendar-check me-2 text-gold"></i>Upcoming Reservations</h6>
                <a href="<?= \baseUrl('admin/reservations') ?>" class="btn btn-sm btn-gold">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Guest</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Guests</th>
                                <th>Table</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentReservations)): ?>
                                <?php foreach ($recentReservations as $res): ?>
                                <tr>
                                    <td><?= \escape($res->guest_name) ?></td>
                                    <td><small><?= \formatDate($res->reservation_date) ?></small></td>
                                    <td><small><?= \formatTime($res->reservation_time) ?></small></td>
                                    <td><?= $res->number_of_guests ?></td>
                                    <td><?= \escape($res->table_number ?? 'N/A') ?></td>
                                    <td><span class="status-badge status-<?= $res->status ?>"><?= ucfirst($res->status) ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center py-4 text-muted">No upcoming reservations</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    fetch('<?= \baseUrl('admin/api/dashboard/charts') ?>')
        .then(r => r.json())
        .then(data => {
            if (data.success && data.data) {
                const revCtx = document.getElementById('revenueChart');
                if (revCtx) {
                    new Chart(revCtx, {
                        type: 'line',
                        data: {
                            labels: data.data.revenue.map(d => d.date),
                            datasets: [{
                                label: 'Revenue',
                                data: data.data.revenue.map(d => d.revenue),
                                borderColor: '#c9a84c',
                                backgroundColor: 'rgba(201, 168, 76, 0.1)',
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                }

                const catCtx = document.getElementById('categoryChart');
                if (catCtx && data.data.popularCategories) {
                    new Chart(catCtx, {
                        type: 'doughnut',
                        data: {
                            labels: data.data.popularCategories.map(c => c.name),
                            datasets: [{
                                data: data.data.popularCategories.map(c => c.order_count),
                                backgroundColor: ['#c9a84c', '#e17055', '#00b894', '#0984e3', '#6c5ce7']
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { position: 'bottom', labels: { padding: 15 } } }
                        }
                    });
                }
            }
        });
});
</script>