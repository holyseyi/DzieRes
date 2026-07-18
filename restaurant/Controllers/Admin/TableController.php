<?php
/**
 * Admin: Table Controller (Floor management)
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class TableController extends BaseController
{
    public function index(): void
    {
        $tables = \db()->fetchAll("SELECT * FROM tables ORDER BY CAST(table_number AS INTEGER) ASC");
        $this->renderAdmin('admin/tables/index', [
            'tables' => $tables,
            'pageTitle' => 'Tables',
        ]);
    }

    public function store(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->insert('tables', [
            'table_number' => \sanitize($_POST['table_number'] ?? ''),
            'capacity' => (int)($_POST['capacity'] ?? 2),
            'min_capacity' => (int)($_POST['min_capacity'] ?? 1),
            'location' => \sanitize($_POST['location'] ?? 'indoor'),
            'status' => \sanitize($_POST['status'] ?? 'available'),
            'description' => \sanitize($_POST['description'] ?? ''),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ]);
        $this->success([], 'Table added');
    }

    public function update(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->update('tables', [
            'table_number' => \sanitize($_POST['table_number'] ?? ''),
            'capacity' => (int)($_POST['capacity'] ?? 2),
            'location' => \sanitize($_POST['location'] ?? 'indoor'),
            'status' => \sanitize($_POST['status'] ?? 'available'),
            'description' => \sanitize($_POST['description'] ?? ''),
        ], 'id = :id', ['id' => $id]);
        $this->success([], 'Table updated');
    }

    public function delete(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->delete('tables', 'id = ?', [$id]);
        $this->success([], 'Table deleted');
    }

    public function status(): void
    {
        $tables = \db()->fetchAll("SELECT * FROM tables ORDER BY CAST(table_number AS INTEGER) ASC");
        $this->success($tables);
    }
}
