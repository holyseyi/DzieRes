<?php
/**
 * Admin: Setting Controller
 * Restaurant name, logo, theme, hours, taxes, currency, payments.
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class SettingController extends BaseController
{
    public function index(): void
    {
        $settings = \db()->fetchAll("SELECT * FROM settings ORDER BY group_name ASC, sort_order ASC");
        $grouped = [];
        foreach ($settings as $s) {
            $grouped[$s->group_name][] = $s;
        }

        $this->renderAdmin('admin/settings/index', [
            'grouped' => $grouped,
            'pageTitle' => 'Settings',
        ]);
    }

    public function update(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        foreach ($_POST as $key => $value) {
            if (in_array($key, ['_csrf_token', 'submit'])) {
                continue;
            }
            if (is_array($value)) {
                $value = json_encode($value);
            }
            \setSetting(\sanitize($key), \sanitize($value));
        }
        \logActivity('settings_update', 'settings', 'Admin updated settings', \auth()->id);
        \sessionFlash('success', 'Settings saved');
        $this->redirect(\baseUrl('admin/settings'));
    }
}
