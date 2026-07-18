<?php
/**
 * Admin Dashboard Controller
 * Restaurant Management System
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index(): void
    {
        $db = \db();
        
        // Stats
        $totalOrders = $db->fetch("SELECT COUNT(*) as count FROM orders")->count ?? 0;
        $pendingOrders = $db->fetch("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'")->count ?? 0;
        $totalRevenue = $db->fetch("SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE payment_status = 'paid'")->total ?? 0;
        $totalCustomers = $db->fetch("SELECT COUNT(*) as count FROM users WHERE role_id = 3")->count ?? 0;
        $totalReservations = $db->fetch("SELECT COUNT(*) as count FROM reservations WHERE status = 'confirmed'")->count ?? 0;
        $todayReservations = $db->fetch("SELECT COUNT(*) as count FROM reservations WHERE reservation_date = date('now') AND status = 'confirmed'")->count ?? 0;
        $lowStockItems = $db->fetch("SELECT COUNT(*) as count FROM ingredients WHERE stock_quantity <= minimum_stock")->count ?? 0;
        $totalMenuItems = $db->fetch("SELECT COUNT(*) as count FROM foods WHERE status = 'active'")->count ?? 0;

        // Recent orders
        $recentOrders = $db->fetchAll(
            "SELECT o.*, CONCAT(COALESCE(u.firstname, o.guest_name), ' ', COALESCE(u.lastname, '')) as customer_name
             FROM orders o
             LEFT JOIN users u ON o.user_id = u.id
             ORDER BY o.created_at DESC LIMIT 10"
        );

        // Recent reservations
        $recentReservations = $db->fetchAll(
            "SELECT r.*, t.table_number 
             FROM reservations r
             LEFT JOIN tables t ON r.table_id = t.id
             WHERE r.reservation_date >= date('now')
             ORDER BY r.reservation_date ASC, r.reservation_time ASC
             LIMIT 8"
        );

        $this->renderAdmin('admin/dashboard', [
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'totalRevenue' => $totalRevenue,
            'totalCustomers' => $totalCustomers,
            'totalReservations' => $totalReservations,
            'todayReservations' => $todayReservations,
            'lowStockItems' => $lowStockItems,
            'totalMenuItems' => $totalMenuItems,
            'recentOrders' => $recentOrders,
            'recentReservations' => $recentReservations,
            'metaTitle' => 'Dashboard - DzieRes Admin',
        ]);
    }

    public function stats(): void
    {
        $db = \db();
        
        $stats = [
            'totalOrders' => $db->fetch("SELECT COUNT(*) as count FROM orders")->count ?? 0,
            'pendingOrders' => $db->fetch("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'")->count ?? 0,
            'revenue' => $db->fetch("SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE payment_status = 'paid'")->total ?? 0,
            'customers' => $db->fetch("SELECT COUNT(*) as count FROM users WHERE role_id = 3")->count ?? 0,
            'todayOrders' => $db->fetch("SELECT COUNT(*) as count FROM orders WHERE date(created_at) = date('now')")->count ?? 0,
            'weekOrders' => $db->fetch("SELECT COUNT(*) as count FROM orders WHERE created_at >= datetime('now', '-7 days')")->count ?? 0,
        ];

        $this->success($stats);
    }

    public function chartData(): void
    {
        $db = \db();
        
        // Revenue last 7 days
        $revenueData = $db->fetchAll(
            "SELECT date(created_at) as date, COALESCE(SUM(total_amount), 0) as revenue 
             FROM orders 
             WHERE created_at >= datetime('now', '-7 days') AND payment_status = 'paid'
             GROUP BY date(created_at)
             ORDER BY date ASC"
        );

        // Orders last 7 days
        $orderData = $db->fetchAll(
            "SELECT date(created_at) as date, COUNT(*) as count 
             FROM orders 
             WHERE created_at >= datetime('now', '-7 days')
             GROUP BY date(created_at)
             ORDER BY date ASC"
        );

        // Popular categories
        $popularCategories = $db->fetchAll(
            "SELECT c.name, COUNT(oi.id) as order_count
             FROM order_items oi
             JOIN foods f ON oi.food_id = f.id
             JOIN categories c ON f.category_id = c.id
             GROUP BY c.id
             ORDER BY order_count DESC
             LIMIT 5"
        );

        $this->success([
            'revenue' => $revenueData,
            'orders' => $orderData,
            'popularCategories' => $popularCategories,
        ]);
    }
}