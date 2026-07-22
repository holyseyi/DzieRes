<?php
/**
 * Gallery View
 *
 * @var array $images
 */
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">Visual Journey</p>
            <h1 class="page-title">Our Gallery</h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <?php if (!empty($images)): ?>
            <div class="gallery-grid">
                <?php foreach ($images as $image): ?>
                    <div class="gallery-item" onclick="openLightbox('<?= \uploadUrl($image->image) ?>', '<?= \escape($image->title ?? '') ?>')">
                        <img src="<?= \uploadUrl($image->image) ?>" alt="<?= \escape($image->title ?? 'Gallery') ?>" loading="lazy">
                        <div class="gallery-overlay"><?= \icon('search-plus', ['style' => 'width:2em;height:2em;color:#fff;']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5 text-muted">No images yet.</div>
        <?php endif; ?>
    </div>
</section>

<!-- Lightbox -->
<div class="lightbox" id="lightbox" onclick="closeLightbox()">
    <span class="lightbox-close">&times;</span>
    <img id="lightboxImg" src="" alt="">
    <div class="lightbox-caption" id="lightboxCaption"></div>
</div>
