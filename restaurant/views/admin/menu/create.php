<?php
/**
 * Admin: Menu Create
 *
 * @var array $categories
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><a href="<?= \baseUrl('admin/menu') ?>" class="text-muted small"><i class="fas fa-arrow-left me-1"></i>Back</a><h4 class="mb-0 mt-1">Add Menu Item</h4></div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="<?= \baseUrl('admin/menu/store') ?>" enctype="multipart/form-data">
            <?= \csrfField() ?>
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Name</label><input type="text" name="name" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Slug (auto)</label><input type="text" name="slug" class="form-control" placeholder="leave blank to auto-generate"></div>
                <div class="col-md-6"><label class="form-label">Category</label><select name="category_id" class="form-select" required><?php foreach ($categories as $c): ?><option value="<?= $c->id ?>"><?= \escape($c->name) ?></option><?php endforeach; ?></select></div>
                <div class="col-md-3"><label class="form-label">Price (<?= \config('currency.code') ?>)</label><input type="number" step="0.01" name="price" class="form-control" required></div>
                <div class="col-md-3"><label class="form-label">Discount %</label><input type="number" step="0.01" name="discount_percent" class="form-control" value="0"></div>
                <div class="col-md-3"><label class="form-label">Calories</label><input type="number" name="calories" class="form-control" value="0"></div>
                <div class="col-md-3"><label class="form-label">Prep Time (min)</label><input type="number" name="preparation_time" class="form-control" value="15"></div>
                <div class="col-md-3"><label class="form-label">Spice Level</label><select name="spice_level" class="form-select"><option value="mild">Mild</option><option value="medium">Medium</option><option value="hot">Hot</option><option value="extra_hot">Extra Hot</option></select></div>
                <div class="col-md-3"><label class="form-label">Availability</label><select name="availability" class="form-select"><option value="available">Available</option><option value="unavailable">Unavailable</option><option value="sold_out">Sold Out</option></select></div>
                <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
                <div class="col-12"><label class="form-label">Ingredients (comma separated)</label><input type="text" name="ingredients" class="form-control" placeholder="Rice, Chicken, Spices"></div>
                <div class="col-12"><label class="form-label">Tags (comma separated)</label><input type="text" name="tags" class="form-control" placeholder="spicy, vegan, popular"></div>
                <div class="col-md-6"><label class="form-label">Image</label><input type="file" name="image" class="form-control" accept="image/*"></div>
                <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                <div class="col-12">
                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="is_featured" id="f1"><label class="form-check-label" for="f1">Featured</label></div>
                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="is_todays_special" id="f2"><label class="form-check-label" for="f2">Today's Special</label></div>
                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="is_chef_recommendation" id="f3"><label class="form-check-label" for="f3">Chef's Recommendation</label></div>
                </div>
            </div>
            <button class="btn btn-gold btn-lg mt-3">Save Item</button>
        </form>
    </div>
</div>
