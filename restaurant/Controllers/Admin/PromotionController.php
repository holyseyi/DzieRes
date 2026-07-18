<?php
/**
 * Admin: Promotion Controller
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class PromotionController extends BaseController
{
    public function index(): void
    {
        $promotions = \db()->fetchAll("SELECT * FROM promotions ORDER BY created_at DESC");
        $this->renderAdmin('admin/promotions/index', [
            'promotions' => $promotions,
            'pageTitle' => 'Promotions',
        ]);
    }

    public function store(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->insert('promotions', [
            'title' => \sanitize($_POST['title'] ?? ''),
            'slug' => \slugify($_POST['title'] ?? ''),
            'description' => \sanitize($_POST['description'] ?? ''),
            'type' => \sanitize($_POST['type'] ?? 'discount'),
            'discount_percent' => (float)($_POST['discount_percent'] ?? 0),
            'start_date' => \sanitize($_POST['start_date'] ?? null),
            'end_date' => \sanitize($_POST['end_date'] ?? null),
            'status' => \sanitize($_POST['status'] ?? 'active'),
        ]);
        \sessionFlash('success', 'Promotion created');
        $this->redirect(\baseUrl('admin/promotions'));
    }

    public function update(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->update('promotions', [
            'title' => \sanitize($_POST['title'] ?? ''),
            'type' => \sanitize($_POST['type'] ?? 'discount'),
            'discount_percent' => (float)($_POST['discount_percent'] ?? 0),
            'status' => \sanitize($_POST['status'] ?? 'active'),
        ], 'id = :id', ['id' => $id]);
        $this->success([], 'Promotion updated');
    }

    public function delete(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->delete('promotions', 'id = ?', [$id]);
        $this->success([], 'Promotion deleted');
    }
}
