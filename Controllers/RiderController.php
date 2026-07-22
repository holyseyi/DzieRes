<?php
/**
 * Rider Controller
 * Restaurant Management System
 */

namespace Controllers;

class RiderController extends BaseController
{
    public function index(): void
    {
        $user = \auth();
        if (!$user || $user->role_slug !== 'rider') {
            \sessionFlash('error', 'Access denied');
            $this->redirect(\baseUrl('login'));
            return;
        }

        $statuses = ['assigned', 'picked_up', 'delivered'];
        
        $orders = \db()->fetchAll(
            "SELECT o.*, 
                    CONCAT(COALESCE(u.firstname, o.guest_name), ' ', COALESCE(u.lastname, '')) as customer_name,
                    COUNT(oi.id) as item_count
             FROM orders o
             LEFT JOIN users u ON o.user_id = u.id
             LEFT JOIN order_items oi ON o.id = oi.order_id
             WHERE o.rider_id = ? 
             AND o.order_type = 'delivery'
             AND o.status IN ('accepted', 'preparing', 'ready', 'assigned', 'picked_up')
             GROUP BY o.id
             ORDER BY o.created_at DESC",
            [$user->id]
        );

        $this->renderWithLayout('rider/dashboard', [
            'orders' => $orders,
            'statuses' => $statuses,
            'metaTitle' => 'Rider Dashboard - DzieRes',
        ]);
    }

    public function acceptOrder(int $id): void
    {
        $user = \auth();
        if (!$user || $user->role_slug !== 'rider') {
            $this->error('Access denied', 403);
            return;
        }

        $order = \db()->fetch("SELECT * FROM orders WHERE id = ?", [$id]);
        if (!$order) {
            $this->error('Order not found');
            return;
        }

        if ($order->order_type !== 'delivery') {
            $this->error('This order is not a delivery order');
            return;
        }

        if ($order->rider_id && $order->rider_id !== $user->id) {
            $this->error('This order is already assigned to another rider');
            return;
        }

        \db()->update('orders', [
            'rider_id' => $user->id,
            'status' => 'assigned'
        ], 'id = :id', ['id' => $id]);

        \db()->insert('order_tracking', [
            'order_id' => $id,
            'status' => 'assigned',
            'description' => 'Rider assigned and accepted the order',
            'created_by' => $user->id,
        ]);

        \logActivity('rider_assigned', 'orders', "Order #{$order->order_number} assigned to rider #{$user->id}", $user->id);

        $this->success([], 'Order accepted successfully');
    }

    public function updateStatus(int $id): void
    {
        $user = \auth();
        if (!$user || $user->role_slug !== 'rider') {
            $this->error('Access denied', 403);
            return;
        }

        $order = \db()->fetch("SELECT * FROM orders WHERE id = ? AND rider_id = ?", [$id, $user->id]);
        if (!$order) {
            $this->error('Order not found or not assigned to you');
            return;
        }

        $status = \sanitize($_POST['status'] ?? '');
        $notes = \sanitize($_POST['notes'] ?? '');

        $allowedStatuses = ['picked_up', 'delivered'];
        if (!in_array($status, $allowedStatuses)) {
            $this->error('Invalid status for rider');
            return;
        }

        \db()->update('orders', ['status' => $status], 'id = :id', ['id' => $id]);
        
        if ($status === 'delivered') {
            \db()->update('orders', [
                'payment_status' => 'paid',
                'actual_delivery_time' => date('Y-m-d H:i:s')
            ], 'id = :id', ['id' => $id]);
        }

        \db()->insert('order_tracking', [
            'order_id' => $id,
            'status' => $status,
            'description' => $notes ?: "Rider updated status to {$status}",
            'created_by' => $user->id,
        ]);

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

        \logActivity('rider_status', 'orders', "Order #{$order->order_number} -> {$status} by rider", $user->id);

        $this->success([], 'Order status updated');
    }
}
