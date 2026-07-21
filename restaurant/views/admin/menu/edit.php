<?php
/**
 * Admin: Menu Edit
 *
 * @var object $food
 * @var array $categories
 */
$tags = is_array($food->tags) ? implode(', ', $food->tags) : implode(', ', json_decode($food->tags ?? '[]', true));
$ingredients = is_array($food->ingredients ?? []) ? implode(', ', $food->ingredients) : $food->ingredients;
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><a href="<?= \baseUrl('admin/menu') ?>" class="text-muted small"><i class="fas fa-arrow-left me-1"></i>Back</a><h4 class="mb-0 mt-1">Edit: <?= \escape($food->name) ?></h4></div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="<?= \baseUrl('admin/menu/' . $food->id . '/update') ?>" enctype="multipart/form-data">
            <?= \csrfField() ?>
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Name</label><input type="text" name="name" class="form-control" value="<?= \escape($food->name) ?>" required></div>
                <div class="col-md-6"><label class="form-label">Slug</label><input type="text" name="slug" class="form-control" value="<?= \escape($food->slug) ?>"></div>
                <div class="col-md-6"><label class="form-label">Category</label><select name="category_id" class="form-select"><?php foreach ($categories as $c): ?><option value="<?= $c->id ?>" <?= $food->category_id==$c->id?'selected':'' ?>><?= \escape($c->name) ?></option><?php endforeach; ?></select></div>
                <div class="col-md-3"><label class="form-label">Price</label><input type="number" step="0.01" name="price" class="form-control" value="<?= $food->price ?>" required></div>
                <div class="col-md-3"><label class="form-label">Discount %</label><input type="number" step="0.01" name="discount_percent" class="form-control" value="<?= $food->discount_percent ?>"></div>
                <div class="col-md-3"><label class="form-label">Calories</label><input type="number" name="calories" class="form-control" value="<?= $food->calories ?>"></div>
                <div class="col-md-3"><label class="form-label">Prep Time</label><input type="number" name="preparation_time" class="form-control" value="<?= $food->preparation_time ?>"></div>
                <div class="col-md-3"><label class="form-label">Spice Level</label><select name="spice_level" class="form-select"><?php foreach (['mild','medium','hot','extra_hot'] as $s): ?><option value="<?= $s ?>" <?= $food->spice_level===$s?'selected':'' ?>><?= ucfirst(str_replace('_',' ',$s)) ?></option><?php endforeach; ?></select></div>
                <div class="col-md-3"><label class="form-label">Availability</label><select name="availability" class="form-select"><?php foreach (['available','unavailable','sold_out'] as $s): ?><option value="<?= $s ?>" <?= $food->availability===$s?'selected':'' ?>><?= ucfirst(str_replace('_',' ',$s)) ?></option><?php endforeach; ?></select></div>
                <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"><?= \escape($food->description ?? '') ?></textarea></div>
                <div class="col-12"><label class="form-label">Ingredients</label><input type="text" name="ingredients" class="form-control" value="<?= \escape($ingredients) ?>"></div>
                <div class="col-12"><label class="form-label">Tags</label><input type="text" name="tags" class="form-control" value="<?= \escape($tags) ?>"></div>
                <div class="col-md-6"><label class="form-label">Image</label><input type="file" name="image" class="form-control" accept="image/*"><small class="text-muted">Current: <?= \escape($food->image ?? 'none') ?></small></div>
                <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" <?= $food->status==='active'?'selected':'' ?>>Active</option><option value="inactive" <?= $food->status==='inactive'?'selected':'' ?>>Inactive</option></select></div>
                <div class="col-12">
                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="is_featured" id="f1" <?= $food->is_featured?'checked':'' ?>><label class="form-check-label" for="f1">Featured</label></div>
                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="is_todays_special" id="f2" <?= $food->is_todays_special?'checked':'' ?>><label class="form-check-label" for="f2">Today's Special</label></div>
                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="is_chef_recommendation" id="f3" <?= $food->is_chef_recommendation?'checked':'' ?>><label class="form-check-label" for="f3">Chef's Recommendation</label></div>
                </div>
            </div>
            <button class="btn btn-gold btn-lg mt-3">Update Item</button>
        </form>
    </div>
</div>
