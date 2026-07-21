<?php
/**
 * Admin: Menu (Food) Controller
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class MenuController extends BaseController
{
    public function index(): void
    {
        $foods = \db()->fetchAll(
            "SELECT f.*, c.name as category_name FROM foods f
             JOIN categories c ON f.category_id = c.id
             ORDER BY c.sort_order ASC, f.sort_order ASC"
        );
        $categories = \db()->fetchAll("SELECT * FROM categories WHERE status = 'active' ORDER BY name ASC");

        $this->renderAdmin('admin/menu/index', [
            'foods' => $foods,
            'categories' => $categories,
            'pageTitle' => 'Menu Management',
        ]);
    }

    public function create(): void
    {
        $categories = \db()->fetchAll("SELECT * FROM categories WHERE status = 'active' ORDER BY name ASC");
        $this->renderAdmin('admin/menu/create', [
            'categories' => $categories,
            'pageTitle' => 'Add Menu Item',
        ]);
    }

    public function store(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }

        $data = [
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'name' => \sanitize($_POST['name'] ?? ''),
            'slug' => \slugify($_POST['slug'] ?? $_POST['name'] ?? ''),
            'description' => \sanitize($_POST['description'] ?? ''),
            'ingredients' => \sanitize($_POST['ingredients'] ?? ''),
            'price' => (float)($_POST['price'] ?? 0),
            'discount_percent' => (float)($_POST['discount_percent'] ?? 0),
            'calories' => (int)($_POST['calories'] ?? 0),
            'preparation_time' => (int)($_POST['preparation_time'] ?? 15),
            'spice_level' => \sanitize($_POST['spice_level'] ?? 'mild'),
            'availability' => \sanitize($_POST['availability'] ?? 'available'),
            'status' => \sanitize($_POST['status'] ?? 'active'),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_todays_special' => isset($_POST['is_todays_special']) ? 1 : 0,
            'is_chef_recommendation' => isset($_POST['is_chef_recommendation']) ? 1 : 0,
            'tags' => json_encode(array_filter(array_map('trim', explode(',', $_POST['tags'] ?? '')))),
        ];
        $data['final_price'] = round($data['price'] * (1 - $data['discount_percent'] / 100), 2);

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $path = \uploadFile($_FILES['image'], 'foods');
            if ($path) {
                $data['image'] = $path;
            }
        }

        \db()->insert('foods', $data);
        \logActivity('menu_create', 'menu', "Created food: {$data['name']}", \auth()->id);
        \sessionFlash('success', 'Menu item created');
        $this->redirect(\baseUrl('admin/menu'));
    }

    public function edit(int $id): void
    {
        $food = \db()->fetch("SELECT * FROM foods WHERE id = ?", [$id]);
        if (!$food) {
            \showError(404, 'Food not found');
            return;
        }
        $categories = \db()->fetchAll("SELECT * FROM categories WHERE status = 'active' ORDER BY name ASC");
        $this->renderAdmin('admin/menu/edit', [
            'food' => $food,
            'categories' => $categories,
            'pageTitle' => 'Edit Menu Item',
        ]);
    }

    public function update(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }

        $food = \db()->fetch("SELECT * FROM foods WHERE id = ?", [$id]);
        if (!$food) {
            $this->error('Food not found');
            return;
        }

        $data = [
            'category_id' => (int)($_POST['category_id'] ?? $food->category_id),
            'name' => \sanitize($_POST['name'] ?? $food->name),
            'slug' => \slugify($_POST['slug'] ?? $_POST['name'] ?? $food->name),
            'description' => \sanitize($_POST['description'] ?? ''),
            'ingredients' => \sanitize($_POST['ingredients'] ?? ''),
            'price' => (float)($_POST['price'] ?? $food->price),
            'discount_percent' => (float)($_POST['discount_percent'] ?? 0),
            'calories' => (int)($_POST['calories'] ?? 0),
            'preparation_time' => (int)($_POST['preparation_time'] ?? 15),
            'spice_level' => \sanitize($_POST['spice_level'] ?? 'mild'),
            'availability' => \sanitize($_POST['availability'] ?? 'available'),
            'status' => \sanitize($_POST['status'] ?? 'active'),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_todays_special' => isset($_POST['is_todays_special']) ? 1 : 0,
            'is_chef_recommendation' => isset($_POST['is_chef_recommendation']) ? 1 : 0,
            'tags' => json_encode(array_filter(array_map('trim', explode(',', $_POST['tags'] ?? '')))),
        ];
        $data['final_price'] = round($data['price'] * (1 - $data['discount_percent'] / 100), 2);

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $path = \uploadFile($_FILES['image'], 'foods');
            if ($path) {
                $data['image'] = $path;
                \deleteFile($food->image);
            }
        }

        \db()->update('foods', $data, 'id = :id', ['id' => $id]);
        \logActivity('menu_update', 'menu', "Updated food: {$data['name']}", \auth()->id);
        \sessionFlash('success', 'Menu item updated');
        $this->redirect(\baseUrl('admin/menu'));
    }

    public function delete(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $food = \db()->fetch("SELECT * FROM foods WHERE id = ?", [$id]);
        if ($food) {
            \deleteFile($food->image);
            \db()->delete('foods', 'id = ?', [$id]);
            \logActivity('menu_delete', 'menu', "Deleted food: {$food->name}", \auth()->id);
        }
        $this->success([], 'Menu item deleted');
    }
}
