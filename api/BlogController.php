<?php
/**
 * API: Blog Controller
 */

namespace Api;

use Controllers\BaseController;

class BlogController extends BaseController
{
    public function latest(): void
    {
        $posts = \db()->fetchAll(
            "SELECT bp.id, bp.title, bp.slug, bp.excerpt, bp.image, bp.published_at,
                    bc.name as category_name
             FROM blog_posts bp
             LEFT JOIN blog_categories bc ON bp.category_id = bc.id
             WHERE bp.status = 'published'
             ORDER BY bp.published_at DESC
             LIMIT 6"
        );
        $this->success($posts);
    }
}
