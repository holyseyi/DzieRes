<?php
/**
 * Reservation Controller (Frontend)
 * Restaurant Management System
 */

namespace Controllers;

class ReservationController extends BaseController
{
    public function index(): void
    {
        $tables = \db()->fetchAll(
            "SELECT * FROM tables WHERE status = 'available' ORDER BY capacity ASC"
        );

        $today = date('Y-m-d');

        $existing = null;
        if (\auth()) {
            $existing = \db()->fetch(
                "SELECT * FROM reservations WHERE user_id = ? ORDER BY created_at DESC LIMIT 1",
                [\auth()->id]
            );
        }

        $this->renderWithLayout('reservations/index', [
            'tables' => $tables,
            'today' => $today,
            'existing' => $existing,
            'metaTitle' => 'Reserve a Table - DzieRes',
            'metaDescription' => 'Book your table at DzieRes Restaurant for an unforgettable dining experience.',
        ]);
    }

    public function book(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }

        $name = \sanitize($_POST['name'] ?? '');
        $email = \sanitize($_POST['email'] ?? '');
        $phone = \sanitize($_POST['phone'] ?? '');
        $date = \sanitize($_POST['date'] ?? '');
        $time = \sanitize($_POST['time'] ?? '');
        $guests = (int)($_POST['guests'] ?? 1);
        $tableId = (int)($_POST['table_id'] ?? 0);
        $occasion = \sanitize($_POST['occasion'] ?? '');
        $requests = \sanitize($_POST['requests'] ?? '');

        $errors = $this->validate([
            'name' => $name,
            'email' => $email,
            'date' => $date,
            'time' => $time,
        ], [
            'name' => 'required|max:100',
            'email' => 'required|email',
            'date' => 'required',
            'time' => 'required',
        ]);

        if ($guests < 1) {
            $errors['guests'][] = 'Number of guests must be at least 1';
        }

        if (!empty($errors)) {
            \sessionFlash('errors', $errors);
            \sessionFlash('old', $_POST);
            $this->back();
        }

        // Validate the table capacity if selected
        if ($tableId) {
            $table = \db()->fetch("SELECT * FROM tables WHERE id = ?", [$tableId]);
            if ($table && $guests > $table->capacity) {
                \sessionFlash('error', 'Selected table does not accommodate ' . $guests . ' guests');
                $this->back();
            }
        }

        $number = \generateReservationNumber();
        $userId = \auth() ? \auth()->id : null;

        $reservationId = \db()->insert('reservations', [
            'reservation_number' => $number,
            'user_id' => $userId,
            'guest_name' => $name,
            'guest_email' => $email,
            'guest_phone' => $phone,
            'table_id' => $tableId ?: null,
            'reservation_date' => $date,
            'reservation_time' => $time,
            'number_of_guests' => $guests,
            'occasion' => $occasion,
            'special_requests' => $requests,
            'status' => 'pending',
        ]);

        // Notify admins
        $admins = \db()->fetchAll("SELECT id FROM users WHERE role_id IN (1,2)");
        foreach ($admins as $admin) {
            \createNotification(
                $admin->id,
                'reservation',
                'New Reservation',
                "{$name} reserved a table for {$guests} guests on {$date} at {$time}.",
                \baseUrl('admin/reservations/' . $reservationId)
            );
        }

        \logActivity('reservation_create', 'reservations', "Reservation {$number} created", $userId);

        if ($this->isAjax()) {
            $this->success([
                'number' => $number,
                'redirect' => \baseUrl('reservations/confirm/' . $number),
            ], 'Reservation request submitted');
            return;
        }

        \sessionFlash('success', "Reservation request submitted! Reference: {$number}");
        $this->redirect(\baseUrl('reservations/confirm/' . $number));
    }

    public function confirm(string $number): void
    {
        $reservation = \db()->fetch(
            "SELECT r.*, t.table_number FROM reservations r
             LEFT JOIN tables t ON r.table_id = t.id
             WHERE r.reservation_number = ?",
            [$number]
        );
        if (!$reservation) {
            \showError(404, 'Reservation not found');
            return;
        }
        $this->renderWithLayout('reservations/confirmation', [
            'reservation' => $reservation,
            'metaTitle' => 'Reservation Confirmation - DzieRes',
        ]);
    }

    public function availableTables(): void
    {
        $date = \sanitize($_GET['date'] ?? date('Y-m-d'));
        $guests = (int)($_GET['guests'] ?? 1);

        $tables = \db()->fetchAll(
            "SELECT * FROM tables
             WHERE capacity >= ? AND status = 'available'
             AND id NOT IN (
                 SELECT table_id FROM reservations
                 WHERE reservation_date = ? AND status IN ('confirmed','pending')
             )
             ORDER BY capacity ASC",
            [$guests, $date]
        );

        $this->success($tables);
    }

    public function availableForDineIn(): void
    {
        $tables = \db()->fetchAll(
            "SELECT id, table_number, capacity, location, status FROM tables WHERE status = 'available' ORDER BY CAST(table_number AS INTEGER) ASC"
        );
        $this->success($tables);
    }

    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
