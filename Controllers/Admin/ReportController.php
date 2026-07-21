<?php
/**
 * Admin: Report / Analytics Controller
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class ReportController extends BaseController
{
    public function index(): void
    {
        $this->renderAdmin('admin/reports/index', [
            'pageTitle' => 'Reports & Analytics',
        ]);
    }

    public function sales(): void
    {
        $range = \sanitize($_GET['range'] ?? '7');
        $days = (int)$range ?: 7;
        $sales = \db()->fetchAll(
            "SELECT date(created_at) as date,
                    COUNT(*) as orders,
                    COALESCE(SUM(total_amount),0) as revenue,
                    COALESCE(SUM(subtotal),0) as subtotal
             FROM orders
             WHERE created_at >= datetime('now', '-{$days} days') AND payment_status = 'paid'
             GROUP BY date(created_at) ORDER BY date ASC"
        );
        $this->success($sales);
    }

    public function revenue(): void
    {
        $total = \db()->fetch("SELECT COALESCE(SUM(total_amount),0) as total FROM orders WHERE payment_status = 'paid'")->total ?? 0;
        $month = \db()->fetch("SELECT COALESCE(SUM(total_amount),0) as total FROM orders WHERE payment_status = 'paid' AND created_at >= datetime('now','start of month')")->total ?? 0;
        $today = \db()->fetch("SELECT COALESCE(SUM(total_amount),0) as total FROM orders WHERE payment_status = 'paid' AND date(created_at) = date('now')")->total ?? 0;

        // Top meals
        $topMeals = \db()->fetchAll(
            "SELECT f.name, SUM(oi.quantity) as qty, SUM(oi.total_price) as revenue
             FROM order_items oi
             JOIN foods f ON oi.food_id = f.id
             JOIN orders o ON oi.order_id = o.id
             WHERE o.payment_status = 'paid'
             GROUP BY f.id ORDER BY qty DESC LIMIT 5"
        );

        // Peak hours
        $peakHours = \db()->fetchAll(
            "SELECT strftime('%H', created_at) as hour, COUNT(*) as orders
             FROM orders GROUP BY hour ORDER BY orders DESC LIMIT 6"
        );

        // Reservation trends (next 14 days)
        $reservationTrends = \db()->fetchAll(
            "SELECT reservation_date, COUNT(*) as count
             FROM reservations
             WHERE reservation_date >= date('now')
             GROUP BY reservation_date ORDER BY reservation_date ASC LIMIT 14"
        );

        // Customer growth (last 6 months)
        $customerGrowth = \db()->fetchAll(
            "SELECT strftime('%Y-%m', created_at) as month, COUNT(*) as count
             FROM users WHERE role_id = 3
             GROUP BY month ORDER BY month DESC LIMIT 6"
        );

        $this->success([
            'total' => $total,
            'month' => $month,
            'today' => $today,
            'top_meals' => $topMeals,
            'peak_hours' => $peakHours,
            'reservation_trends' => $reservationTrends,
            'customer_growth' => $customerGrowth,
        ]);
    }
}
