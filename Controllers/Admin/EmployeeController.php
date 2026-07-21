<?php
/**
 * Admin: Employee Controller
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class EmployeeController extends BaseController
{
    public function index(): void
    {
        $employees = \db()->fetchAll(
            "SELECT e.*, u.firstname, u.lastname, u.email
             FROM employees e
             LEFT JOIN users u ON e.user_id = u.id
             ORDER BY e.hire_date DESC"
        );
        $this->renderAdmin('admin/employees/index', [
            'employees' => $employees,
            'pageTitle' => 'Employees',
        ]);
    }

    public function create(): void
    {
        $this->renderAdmin('admin/employees/create', ['pageTitle' => 'Add Employee']);
    }

    public function store(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->insert('employees', [
            'employee_code' => \sanitize($_POST['employee_code'] ?? ('EMP' . time())),
            'position' => \sanitize($_POST['position'] ?? 'waiter'),
            'department' => \sanitize($_POST['department'] ?? ''),
            'hire_date' => \sanitize($_POST['hire_date'] ?? date('Y-m-d')),
            'salary' => (float)($_POST['salary'] ?? 0),
            'pay_frequency' => \sanitize($_POST['pay_frequency'] ?? 'monthly'),
            'employment_type' => \sanitize($_POST['employment_type'] ?? 'full_time'),
            'emergency_contact' => \sanitize($_POST['emergency_contact'] ?? ''),
            'emergency_phone' => \sanitize($_POST['emergency_phone'] ?? ''),
            'status' => \sanitize($_POST['status'] ?? 'active'),
        ]);
        \sessionFlash('success', 'Employee added');
        $this->redirect(\baseUrl('admin/employees'));
    }

    public function edit(int $id): void
    {
        $employee = \db()->fetch("SELECT * FROM employees WHERE id = ?", [$id]);
        if (!$employee) {
            \showError(404, 'Employee not found');
            return;
        }
        $this->renderAdmin('admin/employees/edit', [
            'employee' => $employee,
            'pageTitle' => 'Edit Employee',
        ]);
    }

    public function update(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->update('employees', [
            'position' => \sanitize($_POST['position'] ?? 'waiter'),
            'department' => \sanitize($_POST['department'] ?? ''),
            'salary' => (float)($_POST['salary'] ?? 0),
            'status' => \sanitize($_POST['status'] ?? 'active'),
        ], 'id = :id', ['id' => $id]);
        \sessionFlash('success', 'Employee updated');
        $this->redirect(\baseUrl('admin/employees'));
    }

    public function delete(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->delete('employees', 'id = ?', [$id]);
        $this->success([], 'Employee deleted');
    }

    public function attendance(): void
    {
        $today = date('Y-m-d');
        $attendance = \db()->fetchAll(
            "SELECT a.*, e.employee_code, e.position
             FROM attendance a
             JOIN employees e ON a.employee_id = e.id
             WHERE a.date = ? ORDER BY e.employee_code ASC",
            [$today]
        );
        $this->success($attendance);
    }
}
