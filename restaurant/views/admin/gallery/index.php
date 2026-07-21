<?php
/**
 * Admin: Gallery
 *
 * @var array $images
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Gallery</h4>
    <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#galleryModal"><?= \icon('upload', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i>Upload Image</button>
</div>

<div class="row g-3">
    <?php foreach ($images as $img): ?>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <img src="<?= \uploadUrl($img->image) ?>" class="card-img-top" style="height:160px;object-fit:cover;" alt="<?= \escape($img->title ?? '') ?>">
                <div class="card-body p-2">
                    <div class="small text-truncate"><?= \escape($img->title ?? 'Untitled') ?></div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="badge bg-light text-dark"><?= ucfirst($img->category ?? 'food') ?></span>
                        <button class="btn btn-sm btn-outline-danger gallery-del" data-id="<?= $img->id ?>"><?= \icon('trash', []) ?>></i></button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($images)): ?><div class="col-12 text-center text-muted py-4">No images uploaded.</div><?php endif; ?>
</div>

<div class="modal fade" id="galleryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= \baseUrl('admin/gallery/upload') ?>" enctype="multipart/form-data">
                <?= \csrfField() ?>
                <div class="modal-header"><h5 class="modal-title">Upload Image</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Image</label><input type="file" name="image" class="form-control" accept="image/*" required></div>
                    <div class="mb-3"><label class="form-label">Title</label><input type="text" name="title" class="form-control"></div>
                    <div class="row g-2">
                        <div class="col"><label class="form-label">Category</label><select name="category" class="form-select"><option value="food">Food</option><option value="interior">Interior</option><option value="events">Events</option><option value="kitchen">Kitchen</option><option value="staff">Staff</option><option value="other">Other</option></select></div>
                        <div class="col"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-gold">Upload</button></div>
            </form>
        </div>
    </div>
</div>
