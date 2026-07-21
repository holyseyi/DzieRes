<?php
/**
 * Gallery Controller (Frontend)
 */

namespace Controllers;

class GalleryController extends BaseController
{
    public function index(): void
    {
        $images = \db()->fetchAll(
            "SELECT * FROM gallery WHERE status = 'active' ORDER BY sort_order ASC"
        );
        $this->renderWithLayout('gallery/index', [
            'images' => $images,
            'metaTitle' => 'Gallery - DzieRes Restaurant',
            'metaDescription' => 'Browse photos of our restaurant, dishes, events and ambiance.',
        ]);
    }
}
