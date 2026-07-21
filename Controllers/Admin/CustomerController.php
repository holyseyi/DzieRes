<?php
/**
 * Admin: Customer Controller
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class CustomerController extends BaseController
{
    public function index(): void
    {
        $search = \sanitize($_GET['search'] ?? '');
        $where = "WHERE u.role_id = 3";
        $params = [];
        if ($search) {
            $where .= " AND (u.firstname LIKE ? OR u.lastname LIKE ? OR u.email LIKE ?)";
            $params = ["%{$search}%", "%{$search}%", "%{$search}%"];
        }

        $customers = \db()->fetchAll(
            "SELECT u.*,
                    (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as order_count,
                    (SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE user_id = u.id AND payment_status='paid') as total_spent
             FROM users u {$where} ORDER BY u.created_at DESC LIMIT 200",
            $params
        );

        $this->renderAdmin('admin/customers/index', [
            'customers' => $customers,
            'pageTitle' => 'Customers',
        ]);
    }

    public function show(int $id): void
    {
        $customer = \db()->fetch("SELECT * FROM users WHERE id = ? AND role_id = 3", [$id]);
        if (!$customer) {
            \showError(404, 'Customer not found');
            return;
        }
        $orders = \db()->fetchAll("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 20", [$id]);
        $this->renderAdmin('admin/customers/show', [
            'customer' => $customer,
            'orders' => $orders,
            'pageTitle' => $customer->firstname . ' ' . $customer->lastname,
        ]);
    }
}
