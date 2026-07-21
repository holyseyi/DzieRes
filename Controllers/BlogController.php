<?php
/**
 * Blog Controller (Frontend)
 */

namespace Controllers;

class BlogController extends BaseController
{
    public function index(): void
    {
        $query = "SELECT bp.*, bc.name as category_name,
                         CONCAT(u.firstname, ' ', u.lastname) as author_name
                  FROM blog_posts bp
                  LEFT JOIN blog_categories bc ON bp.category_id = bc.id
                  LEFT JOIN users u ON bp.user_id = u.id
                  WHERE bp.status = 'published'";
        $params = [];

        $category = \sanitize($_GET['category'] ?? '');
        if ($category) {
            $query .= " AND bc.slug = ?";
            $params[] = $category;
        }

        $query .= " ORDER BY bp.published_at DESC";
        $countQuery = str_replace("bp.*, bc.name as category_name, CONCAT(u.firstname, ' ', u.lastname) as author_name", "COUNT(*) as count", $query);

        $paginator = \paginate($query, $countQuery, $params, \config('pagination.per_page', 12));
        $categories = \db()->fetchAll("SELECT * FROM blog_categories WHERE status = 'active' ORDER BY name ASC");

        $this->renderWithLayout('blog/index', [
            'posts' => $paginator['items'],
            'paginator' => $paginator,
            'categories' => $categories,
            'metaTitle' => 'Blog - DzieRes Restaurant',
        ]);
    }

    public function show(string $slug): void
    {
        $post = \db()->fetch(
            "SELECT bp.*, bc.name as category_name, bc.slug as category_slug,
                    CONCAT(u.firstname, ' ', u.lastname) as author_name
             FROM blog_posts bp
             LEFT JOIN blog_categories bc ON bp.category_id = bc.id
             LEFT JOIN users u ON bp.user_id = u.id
             WHERE bp.slug = ? AND bp.status = 'published'",
            [$slug]
        );
        if (!$post) {
            \showError(404, 'Post not found');
            return;
        }

        $related = \db()->fetchAll(
            "SELECT id, title, slug, image, excerpt FROM blog_posts
             WHERE status = 'published' AND category_id = ? AND id != ?
             ORDER BY published_at DESC LIMIT 3",
            [$post->category_id, $post->id]
        );

        $this->renderWithLayout('blog/show', [
            'post' => $post,
            'related' => $related,
            'metaTitle' => $post->meta_title ?: ($post->title . ' - DzieRes'),
            'metaDescription' => $post->meta_description ?: \truncate($post->excerpt ?? '', 160),
            'metaKeywords' => $post->meta_keywords ?? '',
        ]);
    }

    public function category(string $slug): void
    {
        $_GET['category'] = $slug;
        $this->index();
    }
}
