<?php
/**
 * Menu Index View
 *
 * @var array $categories
 * @var array $foods
 * @var array $paginator
 */
$search = $search ?? '';
$sort = $sort ?? 'name_asc';
$spiceLevel = $spiceLevel ?? '';
$currentCategory = $currentCategory ?? '';
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">Culinary Excellence</p>
            <h1 class="page-title">Our Menu</h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <!-- Filters -->
        <div class="menu-filters mb-4" data-aos="fade-up">
            <form method="GET" action="<?= \baseUrl('menu') ?>" id="menuFilterForm" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search dishes..." value="<?= \escape($search) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= \escape($cat->slug) ?>" <?= $currentCategory === $cat->slug ? 'selected' : '' ?>>
                                <?= \escape($cat->name) ?> (<?= $cat->food_count ?? 0 ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Spice Level</label>
                    <select name="spice" class="form-select">
                        <option value="">Any</option>
                        <option value="mild" <?= $spiceLevel === 'mild' ? 'selected' : '' ?>>Mild</option>
                        <option value="medium" <?= $spiceLevel === 'medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="hot" <?= $spiceLevel === 'hot' ? 'selected' : '' ?>>Hot</option>
                        <option value="extra_hot" <?= $spiceLevel === 'extra_hot' ? 'selected' : '' ?>>Extra Hot</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sort By</label>
                    <select name="sort" class="form-select">
                        <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Name A-Z</option>
                        <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price Low</option>
                        <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price High</option>
                        <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-gold w-100"><i class="fas fa-filter"></i></button>
                </div>
            </form>
        </div>

        <!-- Category Pills -->
        <div class="category-pills mb-4 text-center">
            <a href="<?= \baseUrl('menu') ?>" class="category-pill <?= empty($currentCategory) ? 'active' : '' ?>">All</a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?= \baseUrl('menu/category/' . $cat->slug) ?>" class="category-pill <?= $currentCategory === $cat->slug ? 'active' : '' ?>">
                    <?= \escape($cat->name) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Food Grid -->
        <div class="row g-4" id="foodGrid">
            <?php if (!empty($foods)): ?>
                <?php foreach ($foods as $food): ?>
                    <div class="col-md-6 col-lg-4 col-xl-3" data-aos="fade-up">
                        <div class="food-card">
                            <div class="food-image">
                                <a href="<?= \baseUrl('menu/' . $food->slug) ?>">
                                    <img src="<?= \menuImageUrl($food) ?>" alt="<?= \escape($food->name) ?>" loading="lazy">
                                </a>
                                <?php if ($food->discount_percent > 0): ?>
                                    <span class="discount-badge">-<?= $food->discount_percent ?>%</span>
                                <?php endif; ?>
                                <div class="food-overlay">
                                    <button class="btn btn-gold btn-sm add-to-cart" data-food-id="<?= $food->id ?>">
                                        <i class="fas fa-shopping-bag me-1"></i>Add to Cart
                                    </button>
                                </div>
                            </div>
                            <div class="food-body">
                                <p class="food-category"><?= \escape($food->category_name ?? '') ?></p>
                                <h5 class="food-name">
                                    <a href="<?= \baseUrl('menu/' . $food->slug) ?>" class="text-decoration-none text-reset stretched-link-target">
                                        <?= \escape($food->name) ?>
                                    </a>
                                </h5>
                                <p class="food-description"><?= \truncate($food->description ?? '', 70) ?></p>
                                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                    <span class="badge bg-light text-dark"><i class="far fa-clock me-1"></i><?= $food->preparation_time ?? 15 ?> min</span>
                                    <span class="badge bg-light text-dark"><i class="fas fa-fire me-1"></i><?= $food->calories ?? 0 ?> cal</span>
                                    <?php if ($food->spice_level && $food->spice_level !== 'mild'): ?>
                                        <span class="badge bg-danger-subtle text-danger"><?= ucwords(str_replace('_', ' ', $food->spice_level)) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="food-footer">
                                <div class="food-price">
                                    <?= \formatPrice($food->final_price ?? $food->price) ?>
                                    <?php if ($food->discount_percent > 0): ?>
                                        <span class="original-price"><?= \formatPrice($food->price) ?></span>
                                    <?php endif; ?>
                                </div>
                                <button class="btn btn-light-gold btn-sm favorite-btn" data-food-id="<?= $food->id ?>">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No menu items found. Try adjusting your filters.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if (!empty($paginator) && $paginator['total_pages'] > 1): ?>
            <div class="mt-5">
                <?= \paginationLinks($paginator, \currentUrl()) ?>
            </div>
        <?php endif; ?>
    </div>
</section>
