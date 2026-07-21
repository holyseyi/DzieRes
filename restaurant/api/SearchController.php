<?php
/**
 * API: Global Search Controller
 * Instant search across foods, blog and events.
 */

namespace Api;

use Controllers\BaseController;

class SearchController extends BaseController
{
    public function index(): void
    {
        $query = \sanitize($_GET['q'] ?? '');
        if (empty($query)) {
            $this->success([]);
            return;
        }

        $term = "%{$query}%";

        $foods = \db()->fetchAll(
            "SELECT 'food' as type, f.id, f.name, f.slug, f.final_price as price, f.image,
                    c.name as subtitle
             FROM foods f
             JOIN categories c ON f.category_id = c.id
             WHERE f.status = 'active' AND (f.name LIKE ? OR f.description LIKE ? OR f.tags LIKE ?)
             ORDER BY f.name ASC LIMIT 8",
            [$term, $term, $term]
        );

        $blog = \db()->fetchAll(
            "SELECT 'blog' as type, id, title as name, slug, image, excerpt as subtitle
             FROM blog_posts
             WHERE status = 'published' AND (title LIKE ? OR content LIKE ?)
             ORDER BY published_at DESC LIMIT 4",
            [$term, $term]
        );

        $events = \db()->fetchAll(
            "SELECT 'event' as type, id, title as name, slug, image, location as subtitle
             FROM events
             WHERE status = 'upcoming' AND title LIKE ?
             ORDER BY event_date ASC LIMIT 4",
            [$term]
        );

        $this->success(array_merge($foods, $blog, $events));
    }
}
