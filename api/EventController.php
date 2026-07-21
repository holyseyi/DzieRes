<?php
/**
 * API: Event Controller
 */

namespace Api;

use Controllers\BaseController;

class EventController extends BaseController
{
    public function upcoming(): void
    {
        $events = \db()->fetchAll(
            "SELECT * FROM events
             WHERE status = 'upcoming' AND event_date >= date('now')
             ORDER BY event_date ASC
             LIMIT 10"
        );
        $this->success($events);
    }
}
