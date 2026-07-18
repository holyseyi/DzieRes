<?php
/**
 * Contact Controller (Frontend)
 */

namespace Controllers;

class ContactController extends BaseController
{
    public function index(): void
    {
        $this->renderWithLayout('contact/index', [
            'metaTitle' => 'Contact Us - DzieRes Restaurant',
            'metaDescription' => 'Get in touch with DzieRes Restaurant. We would love to hear from you.',
        ]);
    }

    public function send(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }

        if (!\checkRateLimit('contact', 10, 3600)) {
            \sessionFlash('error', 'Too many messages. Please try again later.');
            $this->back();
        }

        $name = \sanitize($_POST['name'] ?? '');
        $email = \sanitize($_POST['email'] ?? '');
        $phone = \sanitize($_POST['phone'] ?? '');
        $subject = \sanitize($_POST['subject'] ?? '');
        $message = \sanitize($_POST['message'] ?? '');

        $errors = $this->validate([
            'name' => $name,
            'email' => $email,
            'message' => $message,
        ], [
            'name' => 'required|max:100',
            'email' => 'required|email',
            'message' => 'required|min:10|max:2000',
        ]);

        if (!empty($errors)) {
            \sessionFlash('errors', $errors);
            \sessionFlash('old', $_POST);
            $this->back();
        }

        \db()->insert('contact_messages', [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
        ]);

        \logActivity('contact_message', 'contact', "Contact message from {$name}");

        \sessionFlash('success', 'Thank you! Your message has been sent. We will respond shortly.');
        $this->redirect(\baseUrl('contact'));
    }

    public function subscribe(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }

        $email = \sanitize($_POST['email'] ?? '');
        if (!\validateEmail($email)) {
            if ($this->isAjax()) {
                $this->error('Please enter a valid email');
                return;
            }
            \sessionFlash('error', 'Please enter a valid email');
            $this->back();
        }

        $existing = \db()->fetch("SELECT id FROM newsletter_subscribers WHERE email = ?", [$email]);
        $message = 'You are already subscribed!';
        if (!$existing) {
            \db()->insert('newsletter_subscribers', ['email' => $email]);
            $message = 'Subscribed successfully!';
        }

        if ($this->isAjax()) {
            $this->success([], $message);
            return;
        }
        \sessionFlash('success', $message);
        $this->back();
    }

    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
