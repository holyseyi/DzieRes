<?php
/**
 * Blog Show View
 *
 * @var object $post
 * @var array $related
 */
?>
<section class="page-hero" style="min-height:auto;padding:80px 0;">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <span class="badge bg-gold mb-2"><?= \escape($post->category_name ?? 'General') ?></span>
            <h1 class="page-title"><?= \escape($post->title) ?></h1>
            <p class="text-white-50">By <?= \escape($post->author_name ?? 'DzieRes Team') ?> · <?= \formatDate($post->published_at ?? $post->created_at) ?></p>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <?php if ($post->image): ?>
                    <img src="<?= \uploadUrl($post->image) ?>" alt="<?= \escape($post->title) ?>" class="img-fluid rounded-4 mb-4 shadow">
                <?php endif; ?>
                <div class="blog-content">
                    <?= $post->content ?>
                </div>
                <div class="mt-4">
                    <?php if ($post->meta_keywords): ?>
                        <?php foreach (explode(',', $post->meta_keywords) as $kw): ?><span class="tag-pill">#<?= \escape(trim($kw)) ?></span><?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="glass-card p-4">
                    <h5 class="mb-3">Related Posts</h5>
                    <?php if (!empty($related)): ?>
                        <?php foreach ($related as $r): ?>
                            <div class="d-flex gap-2 mb-3">
                                <img src="<?= \uploadUrl($r->image) ?>" alt="" style="width:70px;height:70px;object-fit:cover;border-radius:10px;">
                                <div>
                                    <a href="<?= \baseUrl('blog/' . $r->slug) ?>" class="text-decoration-none fw-semibold small"><?= \escape($r->title) ?></a>
                                    <div class="small text-muted"><?= \truncate($r->excerpt ?? '', 60) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted small">No related posts.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
