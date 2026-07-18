<?php
/**
 * API: Gallery Controller
 */

namespace Api;

use Controllers\BaseController;

class GalleryController extends BaseController
{
    public function index(): void
    {
        $category = \sanitize($_GET['category'] ?? '');
        $where = "WHERE status = 'active'";
        $params = [];
        if ($category) {
            $where .= " AND category = ?";
            $params[] = $category;
        }
        $images = \db()->fetchAll(
            "SELECT * FROM gallery {$where} ORDER BY sort_order ASC",
            $params
        );
        $this->success($images);
    }
}
