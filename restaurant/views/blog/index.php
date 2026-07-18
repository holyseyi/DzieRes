<?php
/**
 * Blog Index View
 *
 * @var array $posts
 * @var array $categories
 * @var array $paginator
 */
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">Stories & Recipes</p>
            <h1 class="page-title">Our Blog</h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="row g-4">
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                            <div class="col-md-6" data-aos="fade-up">
                                <div class="food-card h-100">
                                    <div class="food-image" style="height:200px;">
                                        <a href="<?= \baseUrl('blog/' . $post->slug) ?>">
                                            <img src="<?= \uploadUrl($post->image) ?>" alt="<?= \escape($post->title) ?>" loading="lazy">
                                        </a>
                                    </div>
                                    <div class="food-body">
                                        <span class="badge bg-gold mb-1"><?= \escape($post->category_name ?? 'General') ?></span>
                                        <h5 class="food-name"><a href="<?= \baseUrl('blog/' . $post->slug) ?>" class="text-decoration-none text-reset"><?= \escape($post->title) ?></a></h5>
                                        <p class="food-description"><?= \truncate($post->excerpt ?? '', 100) ?></p>
                                        <small class="text-muted"><?= \formatDate($post->published_at ?? $post->created_at) ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center text-muted py-5">No posts published yet.</div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($paginator) && $paginator['total_pages'] > 1): ?>
                    <div class="mt-5"><?= \paginationLinks($paginator, \currentUrl()) ?></div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <div class="glass-card p-4 mb-4">
                    <h5 class="mb-3">Categories</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="<?= \baseUrl('blog') ?>" class="text-decoration-none">All Posts</a></li>
                        <?php foreach ($categories as $cat): ?>
                            <li class="mb-2"><a href="<?= \baseUrl('blog/category/' . $cat->slug) ?>" class="text-decoration-none"><?= \escape($cat->name) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="glass-card p-4">
                    <h5 class="mb-3">Stay Updated</h5>
                    <p class="text-muted small">Subscribe for the latest news and recipes.</p>
                    <form method="POST" action="<?= \baseUrl('newsletter/subscribe') ?>" class="sidebar-subscribe">
                        <?= \csrfField() ?>
                        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                        <button class="btn btn-gold w-100">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
