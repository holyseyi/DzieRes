<?php
/**
 * Admin: Gallery Controller
 */

namespace Controllers\Admin;

use Controllers\BaseController;

class GalleryController extends BaseController
{
    public function index(): void
    {
        $images = \db()->fetchAll("SELECT * FROM gallery ORDER BY sort_order ASC");
        $this->renderAdmin('admin/gallery/index', [
            'images' => $images,
            'pageTitle' => 'Gallery',
        ]);
    }

    public function upload(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            \sessionFlash('error', 'Please select an image');
            $this->redirect(\baseUrl('admin/gallery'));
        }
        $path = \uploadFile($_FILES['image'], 'gallery');
        if (!$path) {
            \sessionFlash('error', 'Upload failed. Check file type/size.');
            $this->redirect(\baseUrl('admin/gallery'));
        }
        \db()->insert('gallery', [
            'title' => \sanitize($_POST['title'] ?? ''),
            'description' => \sanitize($_POST['description'] ?? ''),
            'image' => $path,
            'category' => \sanitize($_POST['category'] ?? 'food'),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'status' => \sanitize($_POST['status'] ?? 'active'),
        ]);
        \sessionFlash('success', 'Image uploaded');
        $this->redirect(\baseUrl('admin/gallery'));
    }

    public function delete(int $id): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }
        $image = \db()->fetch("SELECT * FROM gallery WHERE id = ?", [$id]);
        if ($image) {
            \deleteFile($image->image);
            \db()->delete('gallery', 'id = ?', [$id]);
        }
        $this->success([], 'Image deleted');
    }
}
