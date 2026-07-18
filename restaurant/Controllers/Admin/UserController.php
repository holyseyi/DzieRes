<?php
/**
 * Admin: User Controller
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class UserController extends BaseController
{
    public function index(): void
    {
        $users = \db()->fetchAll(
            "SELECT u.*, r.name as role_name
             FROM users u JOIN roles r ON u.role_id = r.id
             ORDER BY u.created_at DESC LIMIT 200"
        );
        $roles = \db()->fetchAll("SELECT * FROM roles ORDER BY id ASC");
        $this->renderAdmin('admin/users/index', [
            'users' => $users,
            'roles' => $roles,
            'pageTitle' => 'Users',
        ]);
    }

    public function create(): void
    {
        $roles = \db()->fetchAll("SELECT * FROM roles ORDER BY id ASC");
        $this->renderAdmin('admin/users/create', [
            'roles' => $roles,
            'pageTitle' => 'Add User',
        ]);
    }

    public function store(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $email = \sanitize($_POST['email'] ?? '');
        if (\db()->fetch("SELECT id FROM users WHERE email = ?", [$email])) {
            \sessionFlash('error', 'Email already exists');
            $this->redirect(\baseUrl('admin/users/create'));
        }
        $password = $_POST['password'] ?? 'password123';
        \db()->insert('users', [
            'role_id' => (int)($_POST['role_id'] ?? 3),
            'firstname' => \sanitize($_POST['firstname'] ?? ''),
            'lastname' => \sanitize($_POST['lastname'] ?? ''),
            'email' => $email,
            'phone' => \sanitize($_POST['phone'] ?? ''),
            'password' => \password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
            'status' => \sanitize($_POST['status'] ?? 'active'),
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);
        \sessionFlash('success', 'User created');
        $this->redirect(\baseUrl('admin/users'));
    }

    public function edit(int $id): void
    {
        $user = \db()->fetch("SELECT * FROM users WHERE id = ?", [$id]);
        if (!$user) {
            \showError(404, 'User not found');
            return;
        }
        $roles = \db()->fetchAll("SELECT * FROM roles ORDER BY id ASC");
        $this->renderAdmin('admin/users/edit', [
            'user' => $user,
            'roles' => $roles,
            'pageTitle' => 'Edit User',
        ]);
    }

    public function update(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $data = [
            'role_id' => (int)($_POST['role_id'] ?? 3),
            'firstname' => \sanitize($_POST['firstname'] ?? ''),
            'lastname' => \sanitize($_POST['lastname'] ?? ''),
            'phone' => \sanitize($_POST['phone'] ?? ''),
            'status' => \sanitize($_POST['status'] ?? 'active'),
        ];
        if (!empty($_POST['password'])) {
            $data['password'] = \password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        }
        \db()->update('users', $data, 'id = :id', ['id' => $id]);
        \sessionFlash('success', 'User updated');
        $this->redirect(\baseUrl('admin/users'));
    }

    public function delete(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        if ($id == \auth()->id) {
            $this->error('You cannot delete your own account');
            return;
        }
        \db()->delete('users', 'id = ?', [$id]);
        $this->success([], 'User deleted');
    }
}
