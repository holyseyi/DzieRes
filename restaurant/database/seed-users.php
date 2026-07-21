<?php
/**
 * Ensure at least one admin/staff/customer account exists for local/dev.
 * Run: php database/seed-users.php
 */

$pdo = new PDO('sqlite:' . __DIR__ . '/restaurant.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$admin = [
    'firstname' => 'Admin',
    'lastname' => 'User',
    'email' => 'admin@dzieres.com',
    'phone' => '+233 50 000 0001',
    'password' => password_hash('admin123', PASSWORD_DEFAULT),
    'role_id' => 1,
    'status' => 'active',
];

$staff = [
    'firstname' => 'Staff',
    'lastname' => 'User',
    'email' => 'staff@dzieres.com',
    'phone' => '+233 50 000 0002',
    'password' => password_hash('staff123', PASSWORD_DEFAULT),
    'role_id' => 2,
    'status' => 'active',
];

$customer = [
    'firstname' => 'Test',
    'lastname' => 'Customer',
    'email' => 'customer@dzieres.com',
    'phone' => '+233 50 000 0003',
    'password' => password_hash('customer123', PASSWORD_DEFAULT),
    'role_id' => 3,
    'status' => 'active',
];

$stmt = $pdo->prepare('INSERT OR IGNORE INTO users (firstname, lastname, email, phone, password, role_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
$stmt->execute([$admin['firstname'], $admin['lastname'], $admin['email'], $admin['phone'], $admin['password'], $admin['role_id'], $admin['status']]);
$stmt->execute([$staff['firstname'], $staff['lastname'], $staff['email'], $staff['phone'], $staff['password'], $staff['role_id'], $staff['status']]);
$stmt->execute([$customer['firstname'], $customer['lastname'], $customer['email'], $customer['phone'], $customer['password'], $customer['role_id'], $customer['status']]);

echo "Seeded users:\n";
echo "Admin: admin@dzieres.com / admin123\n";
echo "Staff: staff@dzieres.com / staff123\n";
echo "Customer: customer@dzieres.com / customer123\n";
