<?php
/**
 * Admin: Blog Edit
 *
 * @var object $post
 * @var array $categories
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><a href="<?= \baseUrl('admin/blog') ?>" class="text-muted small"><?= \icon('arrow-left', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i>Back</a><h4 class="mb-0 mt-1">Edit Post</h4></div>
</div>
<div class="card border-0 shadow-sm"><div class="card-body">
<form method="POST" action="<?= \baseUrl('admin/blog/' . $post->id . '/update') ?>">
    <?= \csrfField() ?>
    <div class="row g-3">
        <div class="col-md-8"><label class="form-label">Title</label><input type="text" name="title" class="form-control" value="<?= \escape($post->title) ?>" required></div>
        <div class="col-md-4"><label class="form-label">Category</label><select name="category_id" class="form-select"><option value="">None</option><?php foreach ($categories as $c): ?><option value="<?= $c->id ?>" <?= ($post->category_id ?? 0)==$c->id?'selected':'' ?>><?= \escape($c->name) ?></option><?php endforeach; ?></select></div>
        <div class="col-12"><label class="form-label">Excerpt</label><input type="text" name="excerpt" class="form-control" value="<?= \escape($post->excerpt ?? '') ?>"></div>
        <div class="col-12"><label class="form-label">Content</label><textarea name="content" class="form-control" rows="10"><?= \escape($post->content) ?></textarea></div>
        <div class="col-md-4"><label class="form-label">Meta Title</label><input type="text" name="meta_title" class="form-control" value="<?= \escape($post->meta_title ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">Meta Keywords</label><input type="text" name="meta_keywords" class="form-control" value="<?= \escape($post->meta_keywords ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option value="draft" <?= $post->status==='draft'?'selected':'' ?>>Draft</option><option value="published" <?= $post->status==='published'?'selected':'' ?>>Published</option></select></div>
        <div class="col-12"><label class="form-label">Meta Description</label><textarea name="meta_description" class="form-control" rows="2"><?= \escape($post->meta_description ?? '') ?></textarea></div>
    </div>
    <button class="btn btn-gold btn-lg mt-3">Update</button>
</form>
</div></div>
