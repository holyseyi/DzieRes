<?php
/**
 * Admin: Backup Controller
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class BackupController extends BaseController
{
    public function index(): void
    {
        $backups = \db()->fetchAll("SELECT * FROM backups ORDER BY created_at DESC");
        $this->renderAdmin('admin/backups/index', [
            'backups' => $backups,
            'pageTitle' => 'Backups',
        ]);
    }

    public function create(): void
    {
        $dbPath = \config('database.database') ?? __DIR__ . '/../../database/restaurant.db';
        $backupDir = __DIR__ . '/../../database/backups';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        $filename = 'restaurant_' . date('Ymd_His') . '.db';
        $dest = $backupDir . '/' . $filename;

        if (copy($dbPath, $dest)) {
            \db()->insert('backups', [
                'filename' => $filename,
                'filepath' => 'database/backups/' . $filename,
                'filesize' => filesize($dest),
                'type' => 'manual',
                'status' => 'completed',
            ]);
            \sessionFlash('success', 'Backup created');
        } else {
            \sessionFlash('error', 'Backup failed');
        }
        $this->redirect(\baseUrl('admin/backups'));
    }

    public function restore(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $backup = \db()->fetch("SELECT * FROM backups WHERE id = ?", [$id]);
        if (!$backup) {
            $this->error('Backup not found');
            return;
        }
        $src = __DIR__ . '/../../' . $backup->filepath;
        $dbPath = \config('database.database') ?? __DIR__ . '/../../database/restaurant.db';
        if (file_exists($src) && copy($src, $dbPath)) {
            $this->success([], 'Backup restored');
        } else {
            $this->error('Restore failed');
        }
    }

    public function delete(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $backup = \db()->fetch("SELECT * FROM backups WHERE id = ?", [$id]);
        if ($backup) {
            $path = __DIR__ . '/../../' . $backup->filepath;
            if (file_exists($path)) {
                unlink($path);
            }
            \db()->delete('backups', 'id = ?', [$id]);
        }
        $this->success([], 'Backup deleted');
    }
}
