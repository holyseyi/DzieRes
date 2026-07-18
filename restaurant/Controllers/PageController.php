<?php
/**
 * Static Pages Controller
 * Restaurant Management System
 */

namespace Controllers;

class PageController extends BaseController
{
    public function about(): void
    {
        $this->renderWithLayout('about', [
            'metaTitle' => 'About Us - DzieRes Restaurant',
            'metaDescription' => 'Learn about DzieRes Restaurant, our story, and our passion for exceptional dining experiences.',
        ]);
    }

    public function ourStory(): void
    {
        $this->renderWithLayout('our-story', [
            'metaTitle' => 'Our Story - DzieRes Restaurant',
        ]);
    }

    public function ourChef(): void
    {
        $this->renderWithLayout('our-chef', [
            'metaTitle' => 'Our Chef - DzieRes Restaurant',
        ]);
    }

    public function faqs(): void
    {
        $this->renderWithLayout('faqs', [
            'metaTitle' => 'FAQs - DzieRes Restaurant',
        ]);
    }

    public function privacy(): void
    {
        $this->renderWithLayout('privacy-policy', [
            'metaTitle' => 'Privacy Policy - DzieRes Restaurant',
        ]);
    }

    public function terms(): void
    {
        $this->renderWithLayout('terms', [
            'metaTitle' => 'Terms & Conditions - DzieRes Restaurant',
        ]);
    }

    public function notFound(): void
    {
        http_response_code(404);
        $this->renderWithLayout('errors/404', [
            'metaTitle' => '404 - Page Not Found',
        ]);
    }
}