<?php
/**
 * Admin: Activity Log Controller
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class ActivityLogController extends BaseController
{
    public function index(): void
    {
        $logs = \db()->fetchAll(
            "SELECT a.*, CONCAT(u.firstname, ' ', u.lastname) as user_name
             FROM activity_logs a
             LEFT JOIN users u ON a.user_id = u.id
             ORDER BY a.created_at DESC
             LIMIT 300"
        );
        $this->renderAdmin('admin/activity-logs/index', [
            'logs' => $logs,
            'pageTitle' => 'Activity Logs',
        ]);
    }
}
