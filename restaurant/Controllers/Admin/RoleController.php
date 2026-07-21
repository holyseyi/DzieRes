<?php
/**
 * Admin: Role Controller (RBAC)
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class RoleController extends BaseController
{
    public function index(): void
    {
        $roles = \db()->fetchAll(
            "SELECT r.*, (SELECT COUNT(*) FROM users WHERE role_id = r.id) as user_count
             FROM roles r ORDER BY r.id ASC"
        );
        $permissions = \db()->fetchAll("SELECT * FROM permissions ORDER BY module ASC, name ASC");
        $rolePermissions = [];
        $rows = \db()->fetchAll("SELECT role_id, permission_id FROM role_permissions");
        foreach ($rows as $row) {
            $rolePermissions[$row->role_id][] = $row->permission_id;
        }
        $this->renderAdmin('admin/roles/index', [
            'roles' => $roles,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
            'pageTitle' => 'Roles & Permissions',
        ]);
    }

    public function store(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->insert('roles', [
            'name' => \sanitize($_POST['name'] ?? ''),
            'slug' => \slugify($_POST['slug'] ?? $_POST['name'] ?? ''),
            'description' => \sanitize($_POST['description'] ?? ''),
        ]);
        $this->success([], 'Role created');
    }

    public function updatePermissions(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->delete('role_permissions', 'role_id = ?', [$id]);
        $permissions = $_POST['permissions'] ?? [];
        foreach ($permissions as $pid) {
            \db()->insert('role_permissions', [
                'role_id' => $id,
                'permission_id' => (int)$pid,
            ]);
        }
        $this->success([], 'Permissions updated');
    }
}
