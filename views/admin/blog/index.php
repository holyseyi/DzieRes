<?php
/**
 * Admin: Blog Index
 *
 * @var array $posts
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Blog Posts</h4>
    <a href="<?= \baseUrl('admin/blog/create') ?>" class="btn btn-gold"><?= \icon('plus', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i>New Post</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Title</th><th>Category</th><th>Status</th><th>Published</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($posts as $p): ?>
                        <tr>
                            <td><strong><?= \escape($p->title) ?></strong></td>
                            <td><?= \escape($p->category_name ?? '—') ?></td>
                            <td><span class="badge bg-<?= $p->status==='published'?'success':($p->status==='draft'?'secondary':'warning') ?>"><?= ucfirst($p->status) ?></span></td>
                            <td><small class="text-muted"><?= $p->published_at ? \formatDate($p->published_at) : '—' ?></small></td>
                            <td>
                                <a href="<?= \baseUrl('admin/blog/' . $p->id . '/edit') ?>" class="btn btn-sm btn-outline-secondary"><?= \icon('edit', []) ?>></i></a>
                                <button class="btn btn-sm btn-outline-danger blog-del" data-id="<?= $p->id ?>"><?= \icon('trash', []) ?>></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($posts)): ?><tr><td colspan="5" class="text-center text-muted py-4">No posts.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
