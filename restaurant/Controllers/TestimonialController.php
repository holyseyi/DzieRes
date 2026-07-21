<?php
/**
 * Testimonial Controller (Frontend)
 */

namespace Controllers;

class TestimonialController extends BaseController
{
    public function index(): void
    {
        $testimonials = \db()->fetchAll(
            "SELECT * FROM testimonials WHERE status = 'active' ORDER BY sort_order ASC"
        );
        $this->renderWithLayout('testimonials/index', [
            'testimonials' => $testimonials,
            'metaTitle' => 'Testimonials - DzieRes Restaurant',
        ]);
    }

    public function submit(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }

        $name = \sanitize($_POST['name'] ?? '');
        $title = \sanitize($_POST['title'] ?? '');
        $rating = (int)($_POST['rating'] ?? 0);
        $comment = \sanitize($_POST['comment'] ?? '');

        if (empty($name) || $rating < 1 || $rating > 5 || empty($comment)) {
            \sessionFlash('error', 'Please complete all required fields with a valid rating');
            $this->back();
        }

        \db()->insert('testimonials', [
            'guest_name' => $name,
            'guest_title' => $title,
            'rating' => $rating,
            'content' => $comment,
            'status' => 'pending',
        ]);

        \sessionFlash('success', 'Thank you! Your testimonial has been submitted for review.');
        $this->back();
    }
}
