<?php
/**
 * Admin: Inventory Controller
 * Ingredients, stock, suppliers, purchases.
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class InventoryController extends BaseController
{
    public function index(): void
    {
        $ingredients = \db()->fetchAll(
            "SELECT * FROM ingredients ORDER BY name ASC"
        );
        $this->renderAdmin('admin/inventory/index', [
            'ingredients' => $ingredients,
            'pageTitle' => 'Inventory',
        ]);
    }

    public function ingredients(): void
    {
        $ingredients = \db()->fetchAll(
            "SELECT i.*, (SELECT COUNT(*) FROM food_ingredients WHERE ingredient_id = i.id) as usage_count
             FROM ingredients i ORDER BY i.name ASC"
        );
        $suppliers = \db()->fetchAll("SELECT * FROM suppliers WHERE status = 'active' ORDER BY name ASC");
        $this->renderAdmin('admin/inventory/ingredients', [
            'ingredients' => $ingredients,
            'suppliers' => $suppliers,
            'pageTitle' => 'Ingredients',
        ]);
    }

    public function storeIngredient(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->insert('ingredients', [
            'name' => \sanitize($_POST['name'] ?? ''),
            'slug' => \slugify($_POST['name'] ?? ''),
            'category' => \sanitize($_POST['category'] ?? ''),
            'unit' => \sanitize($_POST['unit'] ?? 'kg'),
            'unit_price' => (float)($_POST['unit_price'] ?? 0),
            'stock_quantity' => (float)($_POST['stock_quantity'] ?? 0),
            'minimum_stock' => (float)($_POST['minimum_stock'] ?? 10),
            'expiry_date' => \sanitize($_POST['expiry_date'] ?? null),
            'status' => \sanitize($_POST['status'] ?? 'active'),
        ]);
        \sessionFlash('success', 'Ingredient added');
        $this->redirect(\baseUrl('admin/inventory/ingredients'));
    }

    public function addStock(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $ingredientId = (int)($_POST['ingredient_id'] ?? 0);
        $qty = (float)($_POST['quantity'] ?? 0);
        $unitPrice = (float)($_POST['unit_price'] ?? 0);
        $supplierId = (int)($_POST['supplier_id'] ?? 0);
        $date = \sanitize($_POST['purchase_date'] ?? date('Y-m-d'));

        $ingredient = \db()->fetch("SELECT * FROM ingredients WHERE id = ?", [$ingredientId]);
        if (!$ingredient) {
            $this->error('Ingredient not found');
            return;
        }

        $total = $qty * $unitPrice;

        \db()->beginTransaction();
        try {
            \db()->insert('inventory', [
                'ingredient_id' => $ingredientId,
                'quantity' => $qty,
                'unit' => $ingredient->unit,
                'unit_price' => $unitPrice,
                'total_cost' => $total,
                'supplier_id' => $supplierId ?: null,
                'purchase_date' => $date,
                'expiry_date' => \sanitize($_POST['expiry_date'] ?? null),
                'status' => 'in_stock',
            ]);
            \db()->insert('purchase_records', [
                'supplier_id' => $supplierId ?: 1,
                'ingredient_id' => $ingredientId,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'total_cost' => $total,
                'purchase_date' => $date,
                'invoice_number' => \sanitize($_POST['invoice_number'] ?? ('INV-' . date('Ymd') . '-' . time())),
            ]);
            \db()->update('ingredients',
                ['stock_quantity' => $ingredient->stock_quantity + $qty],
                'id = :id', ['id' => $ingredientId]);
            \db()->commit();
        } catch (\Exception $e) {
            \db()->rollback();
            $this->error('Failed: ' . $e->getMessage());
            return;
        }

        \sessionFlash('success', 'Stock added');
        $this->redirect(\baseUrl('admin/inventory/ingredients'));
    }

    public function suppliers(): void
    {
        $suppliers = \db()->fetchAll("SELECT * FROM suppliers ORDER BY name ASC");
        $this->renderAdmin('admin/inventory/suppliers', [
            'suppliers' => $suppliers,
            'pageTitle' => 'Suppliers',
        ]);
    }

    public function storeSupplier(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->insert('suppliers', [
            'name' => \sanitize($_POST['name'] ?? ''),
            'contact_person' => \sanitize($_POST['contact_person'] ?? ''),
            'email' => \sanitize($_POST['email'] ?? ''),
            'phone' => \sanitize($_POST['phone'] ?? ''),
            'address' => \sanitize($_POST['address'] ?? ''),
            'city' => \sanitize($_POST['city'] ?? ''),
            'payment_terms' => \sanitize($_POST['payment_terms'] ?? ''),
            'status' => \sanitize($_POST['status'] ?? 'active'),
        ]);
        \sessionFlash('success', 'Supplier added');
        $this->redirect(\baseUrl('admin/inventory/suppliers'));
    }

    public function lowStock(): void
    {
        $items = \db()->fetchAll(
            "SELECT * FROM ingredients WHERE stock_quantity <= minimum_stock ORDER BY name ASC"
        );
        $this->success($items);
    }
}
