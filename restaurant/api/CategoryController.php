<?php
/**
 * API: Category Controller
 */

namespace Api;

use Controllers\BaseController;

class CategoryController extends BaseController
{
    public function index(): void
    {
        $categories = \db()->fetchAll(
            "SELECT c.*, (SELECT COUNT(*) FROM foods WHERE category_id = c.id AND status = 'active') as food_count
             FROM categories c
             WHERE c.status = 'active'
             ORDER BY c.sort_order ASC"
        );
        $this->success($categories);
    }
}
