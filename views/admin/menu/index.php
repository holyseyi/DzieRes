<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Menu Items</h4>
    <a href="<?= \baseUrl('admin/menu/create') ?>" class="btn btn-gold"><?= \icon('plus', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>Add Item</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Status</th><th>Flags</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($foods as $f): ?>
                        <tr>
                            <td><img src="<?= \menuImageUrl($f) ?>" alt="<?= \escape($f->name) ?>" style="width:50px;height:50px;object-fit:cover;border-radius:8px;"></td>
                            <td><strong><?= \escape($f->name) ?></strong><div class="small text-muted"><?= \escape($f->slug) ?></div></td>
                            <td><?= \escape($f->category_name ?? '') ?></td>
                            <td><?= \formatPrice($f->final_price ?? $f->price) ?><?php if ($f->discount_percent>0): ?><span class="badge bg-danger ms-1">-<?= $f->discount_percent ?>%</span><?php endif; ?></td>
                            <td><span class="badge bg-<?= $f->status==='active'?'success':'secondary' ?>"><?= ucfirst($f->status) ?></span></td>
                            <td>
                                <?php if ($f->is_featured): ?><span class="badge bg-info" title="Featured">★</span><?php endif; ?>
                                <?php if ($f->is_todays_special): ?><span class="badge bg-warning text-dark" title="Today's Special">TS</span><?php endif; ?>
                                <?php if ($f->is_chef_recommendation): ?><span class="badge bg-primary" title="Chef's Pick">CP</span><?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= \baseUrl('admin/menu/' . $f->id . '/edit') ?>" class="btn btn-sm btn-outline-secondary"><?= \icon('edit', []) ?></a>
                                <button class="btn btn-sm btn-outline-danger menu-delete" data-id="<?= $f->id ?>"><?= \icon('trash', []) ?></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($foods)): ?><tr><td colspan="7" class="text-center text-muted py-4">No menu items. <a href="<?= \baseUrl('admin/menu/create') ?>">Add one</a>.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
