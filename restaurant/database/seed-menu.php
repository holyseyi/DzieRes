<?php
/**
 * Quick seeder for local development / menu image verification.
 * Run: php database/seed-menu.php
 */

$pdo = new PDO('sqlite:' . __DIR__ . '/restaurant.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$categories = [
    ['name' => 'Breakfast', 'slug' => 'breakfast'],
    ['name' => 'Lunch', 'slug' => 'lunch'],
    ['name' => 'Dinner', 'slug' => 'dinner'],
    ['name' => 'Sides', 'slug' => 'sides'],
    ['name' => 'Desserts', 'slug' => 'desserts'],
    ['name' => 'Drinks', 'slug' => 'drinks'],
];

foreach ($categories as $cat) {
    $stmt = $pdo->prepare('INSERT OR IGNORE INTO categories (name, slug, sort_order) VALUES (?, ?, ?)');
    $stmt->execute([$cat['name'], $cat['slug'], 0]);
}

$catId = (int)$pdo->query("SELECT id FROM categories WHERE slug='breakfast'")->fetchColumn();

$foods = [
    ['name' => 'Koko With Koose', 'slug' => 'koko-with-koose', 'price' => 25, 'calories' => 320, 'prep' => 10, 'spice' => 'mild'],
    ['name' => 'Waakye', 'slug' => 'waakye', 'price' => 35, 'calories' => 450, 'prep' => 20, 'spice' => 'mild'],
    ['name' => 'Jollof Rice', 'slug' => 'jollof-rice', 'price' => 40, 'calories' => 520, 'prep' => 25, 'spice' => 'medium'],
    ['name' => 'Fufu', 'slug' => 'fufu', 'price' => 30, 'calories' => 380, 'prep' => 15, 'spice' => 'mild'],
    ['name' => 'Banku And Tilapia', 'slug' => 'banku-and-tilapia', 'price' => 60, 'calories' => 650, 'prep' => 30, 'spice' => 'hot'],
    ['name' => 'Red Red', 'slug' => 'red-red', 'price' => 28, 'calories' => 400, 'prep' => 15, 'spice' => 'mild'],
    ['name' => 'Kelewele', 'slug' => 'kelewele', 'price' => 15, 'calories' => 250, 'prep' => 10, 'spice' => 'hot'],
    ['name' => 'Peanut Soup', 'slug' => 'peanut-soup', 'price' => 35, 'calories' => 420, 'prep' => 20, 'spice' => 'medium'],
    ['name' => 'Kenkey And Fried Fish', 'slug' => 'kenkey-and-fried-fish', 'price' => 50, 'calories' => 580, 'prep' => 25, 'spice' => 'medium'],
    ['name' => 'Yam Pottage', 'slug' => 'yam-pottage', 'price' => 32, 'calories' => 440, 'prep' => 20, 'spice' => 'mild'],
    ['name' => 'Tuo Zaafi', 'slug' => 'tuo-zaafi', 'price' => 30, 'calories' => 410, 'prep' => 20, 'spice' => 'mild'],
    ['name' => 'Bofrot', 'slug' => 'bofrot', 'price' => 12, 'calories' => 280, 'prep' => 8, 'spice' => 'mild'],
    ['name' => 'Garden Egg Stew', 'slug' => 'garden-egg-stew', 'price' => 25, 'calories' => 300, 'prep' => 15, 'spice' => 'mild'],
    ['name' => 'Light Soup', 'slug' => 'light-soup', 'price' => 30, 'calories' => 260, 'prep' => 15, 'spice' => 'hot'],
    ['name' => 'Okra Soup', 'slug' => 'okra-soup', 'price' => 32, 'calories' => 350, 'prep' => 20, 'spice' => 'medium'],
    ['name' => 'Nkatie Peanut Candy', 'slug' => 'nkatie-peanut-candy', 'price' => 10, 'calories' => 180, 'prep' => 0, 'spice' => 'mild'],
    ['name' => 'Kube Cake', 'slug' => 'kube-cake', 'price' => 18, 'calories' => 320, 'prep' => 5, 'spice' => 'mild'],
    ['name' => 'Wasawasa', 'slug' => 'wasawasa', 'price' => 22, 'calories' => 340, 'prep' => 15, 'spice' => 'medium'],
];

$insert = $pdo->prepare('
    INSERT INTO foods (category_id, name, slug, description, price, final_price, calories, preparation_time, spice_level, availability, status, image, is_featured, sort_order)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
');

foreach ($foods as $i => $f) {
    $insert->execute([
        $catId,
        $f['name'],
        $f['slug'],
        "Delicious {$f['name']} prepared with fresh ingredients.",
        $f['price'],
        $f['price'],
        $f['calories'],
        $f['prep'],
        $f['spice'],
        'available',
        'active',
        '',
        $i < 5 ? 1 : 0,
        $i,
    ]);
}

echo "Seeded " . count($foods) . " foods.\n";
