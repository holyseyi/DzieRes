<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card-admin">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <?= \icon('receipt', []) ?>
            </div>
            <div class="stat-number"><?= number_format($totalOrders) ?></div>
            <div class="text-muted">Total Orders</div>
            <small class="text-warning"><?= $pendingOrders ?> pending</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <?= \icon('money-bill-wave', []) ?>
            </div>
            <div class="stat-number"><?= \formatPrice($totalRevenue) ?></div>
            <div class="text-muted">Total Revenue</div>
            <small class="text-success">All time</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin">
            <div class="stat-icon bg-info bg-opacity-10 text-info">
                <?= \icon('user-plus', []) ?>
            </div>
            <div class="stat-number"><?= number_format($totalCustomers) ?></div>
            <div class="text-muted">Customers</div>
            <small class="text-info">Registered users</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin">
            <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                <?= \icon('exclamation-triangle', []) ?>
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
                <h6 class="mb-0"><?= \icon('chart-line', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;', 'class' => 'text-gold']) ?>Revenue Overview</h6>
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
                <h6 class="mb-0"><?= \icon('chart-pie', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;', 'class' => 'text-gold']) ?>Popular Categories</h6>
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
                <h6 class="mb-0"><?= \icon('clock', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;', 'class' => 'text-gold']) ?>Recent Orders</h6>
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
                <h6 class="mb-0"><?= \icon('calendar-check', ['style' => 'width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;', 'class' => 'text-gold']) ?>Upcoming Reservations</h6>
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
                                borderColor: '#001a4a',
                                backgroundColor: 'rgba(0, 26, 74, 0.1)',
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
                                backgroundColor: ['#001a4a', '#003380', '#000d26', '#1a365d', '#2a4a7f']
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