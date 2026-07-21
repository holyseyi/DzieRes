<?php
/**
 * Authentication Controller
 * Restaurant Management System
 */

namespace Controllers;

class AuthController extends BaseController
{
    public function login(): void
    {
        if (\auth()) {
            $this->redirect(\baseUrl());
        }

        $this->renderWithLayout('auth/login', [
            'metaTitle' => 'Login - DzieRes Restaurant',
        ]);
    }

    public function authenticate(): void
    {
        if (!\verifyCsrf()) {
            \sessionFlash('error', 'Invalid security token');
            $this->back();
        }

        if (!\checkRateLimit('login', 5, 900)) {
            \sessionFlash('error', 'Too many login attempts. Please try again in 15 minutes.');
            $this->back();
        }

        $email = \sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = !empty($_POST['remember']);

        if (empty($email) || empty($password)) {
            \sessionFlash('error', 'Please enter email and password');
            $this->back();
        }

        $user = \db()->fetch(
            "SELECT u.*, r.slug as role_slug, r.name as role_name 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.email = ? AND u.status = 'active'",
            [$email]
        );

        if (!$user || !password_verify($password, $user->password)) {
            \sessionFlash('error', 'Invalid email or password');
            $this->back();
        }

        // Update last login
        \db()->update('users', [
            'last_login' => date('Y-m-d H:i:s'),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
        ], 'id = :id', ['id' => $user->id]);

        // Set session
        \sessionSet('user_id', $user->id);

        // Remember me
        if ($remember) {
            $token = \bin2hex(\random_bytes(32));
            \db()->update('users', ['remember_token' => $token], 'id = :id', ['id' => $user->id]);
            \setcookie('remember_token', $token, time() + 86400 * 30, '/', '', false, true);
        }

        \logActivity('login', 'auth', "User {$user->email} logged in", $user->id);

        // Redirect based on role
        if ($user->role_slug === 'admin' || $user->role_slug === 'staff') {
            $this->redirect(\baseUrl('admin'));
        }

        $redirect = \sessionGet('redirect_after_login', \baseUrl());
        \sessionRemove('redirect_after_login');
        $this->redirect($redirect);
    }

    public function register(): void
    {
        if (\auth()) {
            $this->redirect(\baseUrl());
        }
        
        $this->renderWithLayout('auth/register', [
            'metaTitle' => 'Register - DzieRes Restaurant',
        ]);
    }

    public function store(): void
    {
        if (!\verifyCsrf()) {
            \sessionFlash('error', 'Invalid security token');
            $this->back();
        }

        $data = [
            'firstname' => \sanitize($_POST['firstname'] ?? ''),
            'lastname' => \sanitize($_POST['lastname'] ?? ''),
            'email' => \sanitize($_POST['email'] ?? ''),
            'phone' => \sanitize($_POST['phone'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
        ];

        // Validation
        $errors = $this->validate($data, [
            'firstname' => 'required|min:2|max:50',
            'lastname' => 'required|min:2|max:50',
            'email' => 'required|email',
            'phone' => 'phone',
            'password' => 'required|min:8',
        ]);

        if ($data['password'] !== $data['password_confirm']) {
            $errors['password_confirm'][] = 'Passwords do not match';
        }

        // Check if email exists
        $existing = \db()->fetch("SELECT id FROM users WHERE email = ?", [$data['email']]);
        if ($existing) {
            $errors['email'][] = 'This email is already registered';
        }

        if (!empty($errors)) {
            \sessionFlash('errors', $errors);
            \sessionFlash('old', $data);
            $this->back();
        }

        $hashedPassword = \password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        $userId = \db()->insert('users', [
            'role_id' => 3, // Customer
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $hashedPassword,
            'email_verified_at' => date('Y-m-d H:i:s'), // Auto-verified for demo
        ]);

        // Add welcome loyalty points
        \addLoyaltyPoints($userId, \config('loyalty.welcome_points', 100), 'bonus', 'Welcome bonus');

        \logActivity('register', 'auth', "New user registered: {$data['email']}", $userId);

        \sessionSet('user_id', $userId);
        \sessionFlash('success', 'Welcome to DzieRes! Your account has been created.');
        $this->redirect(\baseUrl());
    }

    public function logout(): void
    {
        \logActivity('logout', 'auth', 'User logged out');
        \sessionDestroy();
        \setcookie('remember_token', '', time() - 3600, '/');
        $this->redirect(\baseUrl());
    }

    public function forgotPassword(): void
    {
        $this->renderWithLayout('auth/forgot-password', [
            'metaTitle' => 'Forgot Password - DzieRes Restaurant',
        ]);
    }

    public function sendResetLink(): void
    {
        if (!\verifyCsrf()) {
            \sessionFlash('error', 'Invalid security token');
            $this->back();
        }

        $email = \sanitize($_POST['email'] ?? '');

        if (!\validateEmail($email)) {
            \sessionFlash('error', 'Please enter a valid email address');
            $this->back();
        }

        $user = \db()->fetch("SELECT id FROM users WHERE email = ?", [$email]);

        if ($user) {
            $token = \bin2hex(\random_bytes(32));
            \db()->update('users', [
                'reset_token' => $token,
                'reset_token_expires' => date('Y-m-d H:i:s', strtotime('+1 hour'))
            ], 'id = :id', ['id' => $user->id]);
        }

        // Always show success to prevent email enumeration
        \sessionFlash('success', 'If that email is registered, we have sent a password reset link.');
        $this->back();
    }

    public function resetPassword(string $token): void
    {
        $user = \db()->fetch(
            "SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > datetime('now')",
            [$token]
        );

        if (!$user) {
            \sessionFlash('error', 'Invalid or expired reset token');
            $this->redirect(\baseUrl('login'));
        }

        $this->renderWithLayout('auth/reset-password', [
            'token' => $token,
            'metaTitle' => 'Reset Password - DzieRes Restaurant',
        ]);
    }

    public function updatePassword(): void
    {
        if (!\verifyCsrf()) {
            \sessionFlash('error', 'Invalid security token');
            $this->back();
        }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($password) || strlen($password) < 8) {
            \sessionFlash('error', 'Password must be at least 8 characters');
            $this->back();
        }

        if ($password !== $passwordConfirm) {
            \sessionFlash('error', 'Passwords do not match');
            $this->back();
        }

        $user = \db()->fetch(
            "SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > datetime('now')",
            [$token]
        );

        if (!$user) {
            \sessionFlash('error', 'Invalid or expired reset token');
            $this->redirect(\baseUrl('login'));
        }

        \db()->update('users', [
            'password' => \password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
            'reset_token' => null,
            'reset_token_expires' => null
        ], 'id = :id', ['id' => $user->id]);

        \sessionFlash('success', 'Password has been reset successfully. Please login.');
        $this->redirect(\baseUrl('login'));
    }
}