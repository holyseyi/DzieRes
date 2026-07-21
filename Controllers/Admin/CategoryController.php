<?php
/**
 * Admin: Category Controller
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class CategoryController extends BaseController
{
    public function index(): void
    {
        $categories = \db()->fetchAll(
            "SELECT c.*, (SELECT COUNT(*) FROM foods WHERE category_id = c.id) as food_count
             FROM categories c ORDER BY c.sort_order ASC"
        );
        $this->renderAdmin('admin/categories/index', [
            'categories' => $categories,
            'pageTitle' => 'Categories',
        ]);
    }

    public function store(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $name = \sanitize($_POST['name'] ?? '');
        \db()->insert('categories', [
            'name' => $name,
            'slug' => \slugify($_POST['slug'] ?? $name),
            'description' => \sanitize($_POST['description'] ?? ''),
            'icon' => \sanitize($_POST['icon'] ?? 'utensils'),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'status' => \sanitize($_POST['status'] ?? 'active'),
        ]);
        $this->success([], 'Category created');
    }

    public function update(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $name = \sanitize($_POST['name'] ?? '');
        \db()->update('categories', [
            'name' => $name,
            'slug' => \slugify($_POST['slug'] ?? $name),
            'description' => \sanitize($_POST['description'] ?? ''),
            'icon' => \sanitize($_POST['icon'] ?? 'utensils'),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'status' => \sanitize($_POST['status'] ?? 'active'),
        ], 'id = :id', ['id' => $id]);
        $this->success([], 'Category updated');
    }

    public function delete(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->delete('categories', 'id = ?', [$id]);
        $this->success([], 'Category deleted');
    }
}
