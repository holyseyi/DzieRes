<?php
/**
 * Admin: Notification Controller
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class NotificationController extends BaseController
{
    public function index(): void
    {
        $notifications = \db()->fetchAll(
            "SELECT n.*, CONCAT(u.firstname, ' ', u.lastname) as user_name
             FROM notifications n
             LEFT JOIN users u ON n.user_id = u.id
             WHERE n.user_id IS NULL OR n.user_id = ?
             ORDER BY n.created_at DESC
             LIMIT 20",
            [\auth()->id]
        );
        $this->success($notifications);
    }

    public function markRead(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            \db()->update('notifications',
                ['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')],
                'id = :id AND user_id = :uid',
                ['id' => $id, 'uid' => \auth()->id]);
        } else {
            \db()->query("UPDATE notifications SET is_read = 1, read_at = ? WHERE user_id = ?",
                [date('Y-m-d H:i:s'), \auth()->id]);
        }
        $this->success([], 'Marked read');
    }
}
