<?php
/**
 * Admin: Kitchen Display System Controller
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class KitchenController extends BaseController
{
    public function index(): void
    {
        $this->renderAdmin('admin/kitchen/index', [
            'pageTitle' => 'Kitchen Display',
        ]);
    }

    public function orders(): void
    {
        $statuses = ['pending','accepted','preparing','cooking','ready'];
        $placeholders = str_repeat('?,', count($statuses) - 1) . '?';

        $orders = \db()->fetchAll(
            "SELECT o.*, CONCAT(COALESCE(u.firstname, o.guest_name), ' ', COALESCE(u.lastname, '')) as customer_name
             FROM orders o
             LEFT JOIN users u ON o.user_id = u.id
             WHERE o.status IN ({$placeholders})
             ORDER BY
                CASE WHEN o.status = 'pending' THEN 1
                     WHEN o.status = 'accepted' THEN 2
                     WHEN o.status = 'preparing' THEN 3
                     WHEN o.status = 'cooking' THEN 4
                     WHEN o.status = 'ready' THEN 5 END,
                o.created_at ASC",
            $statuses
        );

        $items = [];
        foreach ($orders as $o) {
            $items[$o->id] = \db()->fetchAll("SELECT * FROM order_items WHERE order_id = ?", [$o->id]);
        }

        $this->success([
            'orders' => $orders,
            'items' => $items,
            'server_time' => date('Y-m-d H:i:s'),
        ]);
    }

    public function updateStatus(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }

        $orderId = (int)($_POST['order_id'] ?? 0);
        $status = \sanitize($_POST['status'] ?? '');

        $order = \db()->fetch("SELECT * FROM orders WHERE id = ?", [$orderId]);
        if (!$order) {
            $this->error('Order not found');
            return;
        }

        \db()->update('orders', ['status' => $status], 'id = :id', ['id' => $orderId]);
        \db()->insert('order_tracking', [
            'order_id' => $orderId,
            'status' => $status,
            'description' => "Kitchen updated to {$status}",
            'created_by' => \auth()->id,
        ]);

        $this->success([], 'Updated');
    }
}
