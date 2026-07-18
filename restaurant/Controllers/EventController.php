<?php
/**
 * Event Controller (Frontend)
 */

namespace Controllers;

class EventController extends BaseController
{
    public function index(): void
    {
        $events = \db()->fetchAll(
            "SELECT * FROM events
             WHERE status = 'upcoming' AND event_date >= date('now')
             ORDER BY event_date ASC"
        );
        $this->renderWithLayout('events/index', [
            'events' => $events,
            'metaTitle' => 'Events - DzieRes Restaurant',
        ]);
    }

    public function show(string $slug): void
    {
        $event = \db()->fetch("SELECT * FROM events WHERE slug = ?", [$slug]);
        if (!$event) {
            \showError(404, 'Event not found');
            return;
        }
        $this->renderWithLayout('events/show', [
            'event' => $event,
            'metaTitle' => $event->title . ' - DzieRes',
        ]);
    }

    public function book(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }

        $eventId = (int)($_POST['event_id'] ?? 0);
        $name = \sanitize($_POST['name'] ?? '');
        $email = \sanitize($_POST['email'] ?? '');
        $phone = \sanitize($_POST['phone'] ?? '');
        $tickets = max(1, (int)($_POST['tickets'] ?? 1));

        $errors = $this->validate([
            'name' => $name,
            'email' => $email,
        ], [
            'name' => 'required|max:100',
            'email' => 'required|email',
        ]);

        if (!empty($errors)) {
            \sessionFlash('errors', $errors);
            $this->back();
        }

        $event = \db()->fetch("SELECT * FROM events WHERE id = ?", [$eventId]);
        if (!$event) {
            \sessionFlash('error', 'Event not found');
            $this->back();
        }

        $total = ($event->price ?? 0) * $tickets;

        \db()->insert('event_bookings', [
            'event_id' => $eventId,
            'user_id' => \auth() ? \auth()->id : null,
            'guest_name' => $name,
            'guest_email' => $email,
            'guest_phone' => $phone,
            'number_of_tickets' => $tickets,
            'total_amount' => $total,
            'payment_status' => $total > 0 ? 'pending' : 'paid',
            'status' => 'confirmed',
        ]);

        \sessionFlash('success', 'Event booking confirmed! We will contact you shortly.');
        $this->redirect(\baseUrl('events'));
    }
}
