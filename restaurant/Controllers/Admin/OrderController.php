<?php
/**
 * Admin: Order Controller
 * Restaurant Management System
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class OrderController extends BaseController
{
    public function index(): void
    {
        $status = \sanitize($_GET['status'] ?? '');
        $search = \sanitize($_GET['search'] ?? '');

        $where = [];
        $params = [];
        if ($status) {
            $where[] = "o.status = ?";
            $params[] = $status;
        }
        if ($search) {
            $like = "%{$search}%";
            $where[] = "(o.order_number LIKE ? OR CONCAT(COALESCE(u.firstname, o.guest_name), ' ', COALESCE(u.lastname, '')) LIKE ? OR o.guest_email LIKE ? OR o.guest_phone LIKE ?)";
            $params = array_merge($params, [$like, $like, $like, $like]);
        }
        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $orders = \db()->fetchAll(
            "SELECT o.*, CONCAT(COALESCE(u.firstname, o.guest_name), ' ', COALESCE(u.lastname, '')) as customer_name
             FROM orders o
             LEFT JOIN users u ON o.user_id = u.id
             {$whereSql}
             ORDER BY o.created_at DESC
             LIMIT 200",
            $params
        );

        $statuses = ['pending','accepted','preparing','cooking','ready','delivered','cancelled','rejected'];

        $this->renderAdmin('admin/orders/index', [
            'orders' => $orders,
            'statuses' => $statuses,
            'currentStatus' => $status,
            'search' => $search,
            'pageTitle' => 'Orders',
            'metaTitle' => 'Orders - DzieRes Admin',
        ]);
    }

    public function show(int $id): void
    {
        $order = \db()->fetch(
            "SELECT o.*, CONCAT(COALESCE(u.firstname, o.guest_name), ' ', COALESCE(u.lastname, '')) as customer_name
             FROM orders o
             LEFT JOIN users u ON o.user_id = u.id
             WHERE o.id = ?",
            [$id]
        );
        if (!$order) {
            \showError(404, 'Order not found');
            return;
        }
        $items = \db()->fetchAll("SELECT * FROM order_items WHERE order_id = ?", [$id]);
        $tracking = \db()->fetchAll("SELECT * FROM order_tracking WHERE order_id = ? ORDER BY created_at ASC", [$id]);

        $this->renderAdmin('admin/orders/show', [
            'order' => $order,
            'items' => $items,
            'tracking' => $tracking,
            'pageTitle' => 'Order #' . $order->order_number,
        ]);
    }

    public function updateStatus(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }

        $status = \sanitize($_POST['status'] ?? '');
        $notes = \sanitize($_POST['notes'] ?? '');

        $order = \db()->fetch("SELECT * FROM orders WHERE id = ?", [$id]);
        if (!$order) {
            $this->error('Order not found');
            return;
        }

        \db()->update('orders', ['status' => $status], 'id = :id', ['id' => $id]);
        \db()->insert('order_tracking', [
            'order_id' => $id,
            'status' => $status,
            'description' => $notes ?: "Status changed to {$status}",
            'created_by' => \auth()->id,
        ]);

        if ($status === 'delivered') {
            \db()->update('orders', ['payment_status' => 'paid'], 'id = :id', ['id' => $id]);
        }

        // Notify customer
        if ($order->user_id) {
            \createNotification(
                $order->user_id,
                'order',
                'Order Update',
                "Your order #{$order->order_number} is now: " . ucfirst($status),
                \baseUrl('order/track/' . $order->order_number)
            );
        }

        \logActivity('order_status', 'orders', "Order #{$order->order_number} -> {$status}", \auth()->id);

        $this->success([], 'Order status updated');
    }

    public function receipt(int $id): void
    {
        $order = \db()->fetch("SELECT * FROM orders WHERE id = ?", [$id]);
        if (!$order) {
            \showError(404, 'Order not found');
            return;
        }
        $items = \db()->fetchAll("SELECT * FROM order_items WHERE order_id = ?", [$id]);
        $this->renderAdmin('admin/orders/receipt', [
            'order' => $order,
            'items' => $items,
            'pageTitle' => 'Receipt #' . $order->order_number,
        ]);
    }

    public function recent(): void
    {
        $orders = \db()->fetchAll(
            "SELECT o.*, CONCAT(COALESCE(u.firstname, o.guest_name), ' ', COALESCE(u.lastname, '')) as customer_name
             FROM orders o
             LEFT JOIN users u ON o.user_id = u.id
             ORDER BY o.created_at DESC LIMIT 10"
        );
        $this->success($orders);
    }
}
