<?php
/**
 * Our Story View
 */
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">The Journey</p>
            <h1 class="page-title">Our Story</h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="timeline">
            <?php
            $milestones = [
                ['year' => '2019', 'title' => 'The Beginning', 'text' => 'DzieRes opened its first location in Accra with just 12 tables and a dream.'],
                ['year' => '2020', 'title' => 'Going Digital', 'text' => 'We launched online ordering and delivery to serve our community better.'],
                ['year' => '2022', 'title' => 'Expansion', 'text' => 'Opened our second and third branches, bringing our cuisine to more neighborhoods.'],
                ['year' => '2024', 'title' => 'Recognition', 'text' => 'Awarded Best Fine Dining Experience and launched our loyalty rewards program.'],
                ['year' => 'Today', 'title' => 'Innovation', 'text' => 'A modern platform powering reservations, kitchen display, and analytics.'],
            ];
            foreach ($milestones as $m): ?>
                <div class="timeline-item" data-aos="fade-up">
                    <div class="timeline-year"><?= $m['year'] ?></div>
                    <div class="timeline-content glass-card">
                        <h5><?= $m['title'] ?></h5>
                        <p class="text-muted mb-0"><?= $m['text'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
