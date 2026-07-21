<?php
/**
 * API: Testimonial Controller
 */

namespace Api;

use Controllers\BaseController;

class TestimonialController extends BaseController
{
    public function index(): void
    {
        $testimonials = \db()->fetchAll(
            "SELECT * FROM testimonials WHERE status = 'active' ORDER BY sort_order ASC LIMIT 20"
        );
        $this->success($testimonials);
    }
}
