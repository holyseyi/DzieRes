<?php
/**
 * Our Chef View
 */
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">Culinary Mastermind</p>
            <h1 class="page-title">Our Chef</h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5" data-aos="fade-right">
                <img src="<?= \asset('images/placeholders/chef.jpg') ?>" alt="Head Chef" class="img-fluid rounded-4 shadow chef-portrait">
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <p class="section-subtitle">Executive Chef</p>
                <h2 class="section-title">Kwame Mensah</h2>
                <p class="text-muted">With over 20 years of culinary experience across Europe, Asia, and Africa, Chef Kwame brings a unique fusion perspective to DzieRes. His philosophy is simple: respect the ingredient, honor the tradition, and surprise the palate.</p>
                <p class="text-muted">Under his leadership, our kitchen has earned multiple accolades and continues to push the boundaries of modern Ghanaian fine dining.</p>
                <div class="d-flex gap-3 mt-3">
                    <div class="glass-card p-3 text-center flex-fill"><div class="stat-number" data-count="20">0</div><small class="text-muted">Years Exp</small></div>
                    <div class="glass-card p-3 text-center flex-fill"><div class="stat-number" data-count="15">0</div><small class="text-muted">Awards</small></div>
                    <div class="glass-card p-3 text-center flex-fill"><div class="stat-number" data-count="3">0</div><small class="text-muted">Branches</small></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding bg-light-section">
    <div class="container">
        <div class="text-center mb-5"><h2 class="section-title">The Kitchen Team</h2></div>
        <div class="row g-4">
            <?php foreach ([
                ['name' => 'Ama Owusu', 'role' => 'Sous Chef', 'initial' => 'AO'],
                ['name' => 'Kofi Asare', 'role' => 'Pastry Chef', 'initial' => 'KA'],
                ['name' => 'Esi Boateng', 'role' => 'Grill Master', 'initial' => 'EB'],
                ['name' => 'Yaw Frimpong', 'role' => 'Line Cook', 'initial' => 'YF'],
            ] as $member): ?>
                <div class="col-md-3" data-aos="fade-up">
                    <div class="glass-card text-center p-4">
                        <div class="team-avatar mx-auto mb-3"><?= $member['initial'] ?></div>
                        <h6 class="mb-0"><?= $member['name'] ?></h6>
                        <small class="text-muted"><?= $member['role'] ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
