<?php
/**
 * Admin: Blog Controller
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class BlogController extends BaseController
{
    public function index(): void
    {
        $posts = \db()->fetchAll(
            "SELECT bp.*, bc.name as category_name
             FROM blog_posts bp
             LEFT JOIN blog_categories bc ON bp.category_id = bc.id
             ORDER BY bp.created_at DESC"
        );
        $this->renderAdmin('admin/blog/index', [
            'posts' => $posts,
            'pageTitle' => 'Blog',
        ]);
    }

    public function create(): void
    {
        $categories = \db()->fetchAll("SELECT * FROM blog_categories WHERE status = 'active'");
        $this->renderAdmin('admin/blog/create', [
            'categories' => $categories,
            'pageTitle' => 'Add Post',
        ]);
    }

    public function store(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $status = \sanitize($_POST['status'] ?? 'draft');
        \db()->insert('blog_posts', [
            'category_id' => (int)($_POST['category_id'] ?? 0) ?: null,
            'user_id' => \auth()->id,
            'title' => \sanitize($_POST['title'] ?? ''),
            'slug' => \slugify($_POST['slug'] ?? $_POST['title'] ?? ''),
            'excerpt' => \sanitize($_POST['excerpt'] ?? ''),
            'content' => $_POST['content'] ?? '',
            'meta_title' => \sanitize($_POST['meta_title'] ?? ''),
            'meta_description' => \sanitize($_POST['meta_description'] ?? ''),
            'meta_keywords' => \sanitize($_POST['meta_keywords'] ?? ''),
            'status' => $status,
            'published_at' => $status === 'published' ? date('Y-m-d H:i:s') : null,
        ]);
        \sessionFlash('success', 'Post created');
        $this->redirect(\baseUrl('admin/blog'));
    }

    public function edit(int $id): void
    {
        $post = \db()->fetch("SELECT * FROM blog_posts WHERE id = ?", [$id]);
        if (!$post) {
            \showError(404, 'Post not found');
            return;
        }
        $categories = \db()->fetchAll("SELECT * FROM blog_categories WHERE status = 'active'");
        $this->renderAdmin('admin/blog/edit', [
            'post' => $post,
            'categories' => $categories,
            'pageTitle' => 'Edit Post',
        ]);
    }

    public function update(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $post = \db()->fetch("SELECT * FROM blog_posts WHERE id = ?", [$id]);
        if (!$post) {
            $this->error('Post not found');
            return;
        }
        $status = \sanitize($_POST['status'] ?? $post->status);
        $data = [
            'category_id' => (int)($_POST['category_id'] ?? 0) ?: null,
            'title' => \sanitize($_POST['title'] ?? ''),
            'slug' => \slugify($_POST['slug'] ?? $_POST['title'] ?? ''),
            'excerpt' => \sanitize($_POST['excerpt'] ?? ''),
            'content' => $_POST['content'] ?? '',
            'status' => $status,
            'published_at' => ($status === 'published' && !$post->published_at)
                ? date('Y-m-d H:i:s') : $post->published_at,
        ];
        \db()->update('blog_posts', $data, 'id = :id', ['id' => $id]);
        \sessionFlash('success', 'Post updated');
        $this->redirect(\baseUrl('admin/blog'));
    }

    public function delete(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $post = \db()->fetch("SELECT * FROM blog_posts WHERE id = ?", [$id]);
        if ($post) {
            \deleteFile($post->image);
            \db()->delete('blog_posts', 'id = ?', [$id]);
        }
        $this->success([], 'Post deleted');
    }
}
