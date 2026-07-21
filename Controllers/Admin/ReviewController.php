<?php
/**
 * Admin: Review Controller (Customer testimonials / reviews)
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class ReviewController extends BaseController
{
    public function index(): void
    {
        $status = \sanitize($_GET['status'] ?? '');
        $where = '';
        $params = [];
        if ($status) {
            $where = "WHERE status = ?";
            $params[] = $status;
        }
        $reviews = \db()->fetchAll(
            "SELECT r.*, CONCAT(COALESCE(u.firstname, r.guest_name), ' ', COALESCE(u.lastname, '')) as customer_name
             FROM reviews r
             LEFT JOIN users u ON r.user_id = u.id
             {$where}
             ORDER BY r.created_at DESC
             LIMIT 200",
            $params
        );
        $this->renderAdmin('admin/reviews/index', [
            'reviews' => $reviews,
            'currentStatus' => $status,
            'pageTitle' => 'Reviews',
        ]);
    }

    public function updateStatus(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $status = \sanitize($_POST['status'] ?? 'pending');
        \db()->update('reviews', ['status' => $status], 'id = :id', ['id' => $id]);
        $this->success([], 'Review status updated');
    }

    public function reply(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        \db()->update('reviews', [
            'staff_reply' => \sanitize($_POST['reply'] ?? ''),
            'replied_by' => \auth()->id,
            'replied_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $id]);
        $this->success([], 'Reply saved');
    }
}
