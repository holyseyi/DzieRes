<?php
/**
 * Admin: Reservation Controller
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class ReservationController extends BaseController
{
    public function index(): void
    {
        $status = \sanitize($_GET['status'] ?? '');
        $where = '';
        $params = [];
        if ($status) {
            $where = "WHERE r.status = ?";
            $params[] = $status;
        }

        $reservations = \db()->fetchAll(
            "SELECT r.*, t.table_number, CONCAT(COALESCE(u.firstname, r.guest_name), ' ', COALESCE(u.lastname, '')) as customer_name
             FROM reservations r
             LEFT JOIN tables t ON r.table_id = t.id
             LEFT JOIN users u ON r.user_id = u.id
             {$where}
             ORDER BY r.reservation_date DESC, r.reservation_time DESC
             LIMIT 200",
            $params
        );

        $this->renderAdmin('admin/reservations/index', [
            'reservations' => $reservations,
            'statuses' => ['pending','confirmed','seated','completed','cancelled','no_show'],
            'currentStatus' => $status,
            'pageTitle' => 'Reservations',
        ]);
    }

    public function show(int $id): void
    {
        $reservation = \db()->fetch(
            "SELECT r.*, t.table_number, CONCAT(COALESCE(u.firstname, r.guest_name), ' ', COALESCE(u.lastname, '')) as customer_name
             FROM reservations r
             LEFT JOIN tables t ON r.table_id = t.id
             LEFT JOIN users u ON r.user_id = u.id
             WHERE r.id = ?",
            [$id]
        );
        if (!$reservation) {
            \showError(404, 'Reservation not found');
            return;
        }
        $tables = \db()->fetchAll("SELECT * FROM tables ORDER BY capacity ASC");
        $this->renderAdmin('admin/reservations/show', [
            'reservation' => $reservation,
            'tables' => $tables,
            'pageTitle' => 'Reservation #' . $reservation->reservation_number,
        ]);
    }

    public function updateStatus(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $status = \sanitize($_POST['status'] ?? '');
        \db()->update('reservations', ['status' => $status], 'id = :id', ['id' => $id]);

        $res = \db()->fetch("SELECT * FROM reservations WHERE id = ?", [$id]);
        if ($res && $res->user_id) {
            \createNotification($res->user_id, 'reservation',
                'Reservation Update',
                "Your reservation #{$res->reservation_number} is now: " . ucfirst($status),
                \baseUrl());
        }

        \logActivity('reservation_status', 'reservations', "Reservation #{$res->reservation_number} -> {$status}", \auth()->id);
        $this->success([], 'Reservation updated');
    }

    public function assignTable(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $tableId = (int)($_POST['table_id'] ?? 0);
        \db()->update('reservations', ['table_id' => $tableId ?: null], 'id = :id', ['id' => $id]);
        if ($tableId) {
            \db()->update('tables', ['status' => 'reserved'], 'id = :id', ['id' => $tableId]);
        }
        $this->success([], 'Table assigned');
    }
}
