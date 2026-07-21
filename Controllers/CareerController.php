<?php
/**
 * Career Controller (Frontend)
 */

namespace Controllers;

class CareerController extends BaseController
{
    public function index(): void
    {
        $jobs = \db()->fetchAll(
            "SELECT * FROM job_listings WHERE status = 'open' ORDER BY created_at DESC"
        );
        $this->renderWithLayout('careers/index', [
            'jobs' => $jobs,
            'metaTitle' => 'Careers - DzieRes Restaurant',
        ]);
    }

    public function show(string $slug): void
    {
        $job = \db()->fetch("SELECT * FROM job_listings WHERE slug = ?", [$slug]);
        if (!$job) {
            \showError(404, 'Job not found');
            return;
        }
        $this->renderWithLayout('careers/show', [
            'job' => $job,
            'metaTitle' => $job->title . ' - Careers - DzieRes',
        ]);
    }

    public function apply(): void
    {
        if (!\verifyCsrf()) {
            $this->error('Invalid security token');
            return;
        }

        $jobId = (int)($_POST['job_id'] ?? 0);
        $firstname = \sanitize($_POST['firstname'] ?? '');
        $lastname = \sanitize($_POST['lastname'] ?? '');
        $email = \sanitize($_POST['email'] ?? '');
        $phone = \sanitize($_POST['phone'] ?? '');
        $cover = \sanitize($_POST['cover_letter'] ?? '');

        $errors = $this->validate([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
        ], [
            'firstname' => 'required|max:50',
            'lastname' => 'required|max:50',
            'email' => 'required|email',
        ]);

        if (!empty($errors)) {
            \sessionFlash('errors', $errors);
            $this->back();
        }

        \db()->insert('job_applications', [
            'job_id' => $jobId,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'phone' => $phone,
            'cover_letter' => $cover,
            'status' => 'pending',
        ]);

        \sessionFlash('success', 'Application submitted successfully! Our HR team will review it.');
        $this->redirect(\baseUrl('careers'));
    }
}
