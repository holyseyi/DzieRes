<?php
/**
 * Admin: Coupon Controller
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class CouponController extends BaseController
{
    public function index(): void
    {
        $coupons = \db()->fetchAll("SELECT * FROM coupons ORDER BY created_at DESC");
        $this->renderAdmin('admin/coupons/index', [
            'coupons' => $coupons,
            'pageTitle' => 'Coupons',
        ]);
    }

    public function store(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->insert('coupons', [
            'code' => strtoupper(\sanitize($_POST['code'] ?? '')),
            'type' => \sanitize($_POST['type'] ?? 'percentage'),
            'value' => (float)($_POST['value'] ?? 0),
            'min_order_amount' => (float)($_POST['min_order_amount'] ?? 0),
            'max_discount' => !empty($_POST['max_discount']) ? (float)$_POST['max_discount'] : null,
            'usage_limit' => (int)($_POST['usage_limit'] ?? 100),
            'start_date' => \sanitize($_POST['start_date'] ?? null),
            'end_date' => \sanitize($_POST['end_date'] ?? null),
            'status' => \sanitize($_POST['status'] ?? 'active'),
            'description' => \sanitize($_POST['description'] ?? ''),
        ]);
        \sessionFlash('success', 'Coupon created');
        $this->redirect(\baseUrl('admin/coupons'));
    }

    public function update(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->update('coupons', [
            'code' => strtoupper(\sanitize($_POST['code'] ?? '')),
            'type' => \sanitize($_POST['type'] ?? 'percentage'),
            'value' => (float)($_POST['value'] ?? 0),
            'min_order_amount' => (float)($_POST['min_order_amount'] ?? 0),
            'status' => \sanitize($_POST['status'] ?? 'active'),
        ], 'id = :id', ['id' => $id]);
        $this->success([], 'Coupon updated');
    }

    public function delete(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->delete('coupons', 'id = ?', [$id]);
        $this->success([], 'Coupon deleted');
    }
}
