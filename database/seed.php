<?php
/**
 * One-time seed: populates categories, products, blog_posts, banners, and
 * a first admin staff account from the site's original static data.
 *
 * Run locally with: php database/seed.php
 * Safe to re-run — it skips anything that already exists.
 */

require_once __DIR__ . '/../api/lib/db.php';
require_once __DIR__ . '/../api/lib/slug.php';

$pdo = db();

// ── Staff ────────────────────────────────────────────────────────
$adminEmail = 'admin@poseidonvet.com';
$stmt = $pdo->prepare('SELECT id FROM staff WHERE email = ?');
$stmt->execute([$adminEmail]);
if (!$stmt->fetch()) {
    $pdo->prepare('INSERT INTO staff (name, email, password_hash, role) VALUES (?, ?, ?, ?)')
        ->execute(['Dr. Chesang', $adminEmail, password_hash('ChangeMe123!', PASSWORD_DEFAULT), 'admin']);
    echo "Created admin staff account: {$adminEmail} / ChangeMe123!  (change this password after first login)\n";
} else {
    echo "Admin staff account already exists, skipping.\n";
}

// ── Categories ───────────────────────────────────────────────────
$categoryNames = ['Food', 'Supplements', 'Accessories', 'Medications', 'Grooming'];
$categoryIds   = [];
foreach ($categoryNames as $i => $name) {
    $slug = make_slug($name);
    $stmt = $pdo->prepare('SELECT id FROM categories WHERE slug = ?');
    $stmt->execute([$slug]);
    $existing = $stmt->fetch();
    if ($existing) {
        $categoryIds[$name] = (int)$existing['id'];
        continue;
    }
    $pdo->prepare('INSERT INTO categories (name, slug, sort_order) VALUES (?, ?, ?)')
        ->execute([$name, $slug, $i]);
    $categoryIds[$name] = (int)$pdo->lastInsertId();
}
echo 'Categories ready: ' . implode(', ', $categoryNames) . "\n";

// ── Products ─────────────────────────────────────────────────────
$products = [
    ['Royal Canin Veterinary Diet', 'Food', 3200, 3800, 4.9, 214, 'Best Seller',
        'https://images.unsplash.com/photo-1601758003122-53c40e686a19?auto=format&fit=crop&w=400&h=400&q=80',
        'Prescription diet formulated for urinary tract health in cats. Vet-recommended for long-term management.', true],
    ['Omega-3 Fish Oil Capsules', 'Supplements', 1450, null, 4.7, 98, null,
        'https://images.unsplash.com/photo-1585435557343-3b092031a831?auto=format&fit=crop&w=400&h=400&q=80',
        'Pure pharmaceutical-grade Omega-3 for coat health, joint support, and cardiovascular function.', true],
    ['Adjustable Comfort Harness', 'Accessories', 2100, 2500, 4.8, 143, 'Sale',
        'https://images.unsplash.com/photo-1587300003388-59208cc962cb?auto=format&fit=crop&w=400&h=400&q=80',
        'Padded no-pull harness with reflective stitching. Suitable for dogs 5–40 kg.', true],
    ['Nexgard Spectra Flea & Tick', 'Medications', 1800, null, 4.9, 317, 'Rx',
        'https://images.unsplash.com/photo-1573883429746-084be9b5cfca?auto=format&fit=crop&w=400&h=400&q=80',
        'Monthly oral chew that protects against fleas, ticks, mites, heartworm, and intestinal worms.', true],
    ['Waterless Dry Shampoo', 'Grooming', 850, null, 4.5, 67, null,
        'https://images.unsplash.com/photo-1600369672770-985fd30004eb?auto=format&fit=crop&w=400&h=400&q=80',
        'Quick-dry foam shampoo for freshening up between baths. Aloe vera and oat extract formula.', true],
    ['Probiotic Gut Health Powder', 'Supplements', 1650, 1900, 4.6, 51, 'New',
        'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?auto=format&fit=crop&w=400&h=400&q=80',
        'Multi-strain probiotic to support digestive health, immunity, and stool quality in dogs and cats.', true],
    ['Premium Orthopedic Bed', 'Accessories', 4500, 5200, 4.8, 89, 'Sale',
        'https://images.unsplash.com/photo-1601758228041-f3b2795255f1?auto=format&fit=crop&w=400&h=400&q=80',
        'Memory foam base with washable cover. Designed for senior pets and post-surgery recovery.', true],
    ["Hill's Science Diet Adult", 'Food', 2800, null, 4.7, 176, null,
        'https://images.unsplash.com/photo-1589924691995-400dc9ecc119?auto=format&fit=crop&w=400&h=400&q=80',
        'Balanced adult formula with chicken and barley. Supports lean muscle and healthy digestion.', false],
    ['Stainless Steel Travel Bowl', 'Accessories', 650, null, 4.4, 33, null,
        'https://images.unsplash.com/photo-1695023264743-7f1448deb7f2?auto=format&fit=crop&w=400&h=400&q=80',
        'Collapsible 700ml bowl for walks and travel. BPA-free, dishwasher safe.', true],
    ['Dental Chew Sticks (30-pack)', 'Food', 980, 1100, 4.6, 202, 'Sale',
        'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=400&h=400&q=80',
        'Enzymatic dental chews that reduce plaque by up to 80%. Chicken flavor, grain-free.', true],
    ['Pet First Aid Kit', 'Accessories', 1950, null, 4.9, 44, 'New',
        'https://images.unsplash.com/photo-1585435557343-3b092031a831?auto=format&fit=crop&w=400&h=400&q=80',
        '42-piece kit including bandages, antiseptic wipes, thermometer, and first aid guide.', true],
    ['Calming Lavender Shampoo', 'Grooming', 720, null, 4.5, 58, null,
        'https://images.unsplash.com/photo-1600369672770-985fd30004eb?auto=format&fit=crop&w=400&h=400&q=80',
        'Tear-free, sulphate-free shampoo with lavender and chamomile to soothe sensitive skin.', true],
];

$productCount = 0;
foreach ($products as [$name, $cat, $price, $oldPrice, $rating, $reviews, $badge, $image, $desc, $inStock]) {
    $slug = make_slug($name);
    $stmt = $pdo->prepare('SELECT id FROM products WHERE slug = ?');
    $stmt->execute([$slug]);
    if ($stmt->fetch()) {
        continue;
    }
    $pdo->prepare('INSERT INTO products
        (name, slug, category_id, price, old_price, description, image, rating, reviews, badge, in_stock)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
        ->execute([$name, $slug, $categoryIds[$cat], $price, $oldPrice, $desc, $image, $rating, $reviews, $badge, (int)$inStock]);
    $productCount++;
}
echo "Inserted {$productCount} new products (" . (count($products) - $productCount) . " already existed).\n";

// ── Blog posts ───────────────────────────────────────────────────
$posts = require __DIR__ . '/seed_blog_data.php';

$postCount = 0;
foreach ($posts as $p) {
    $stmt = $pdo->prepare('SELECT id FROM blog_posts WHERE slug = ?');
    $stmt->execute([$p['slug']]);
    if ($stmt->fetch()) {
        continue;
    }
    $pdo->prepare('INSERT INTO blog_posts
        (slug, title, excerpt, category, author, author_avatar, image, body, tags, read_time, published)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)')
        ->execute([
            $p['slug'], $p['title'], $p['excerpt'], $p['category'], $p['author'],
            $p['authorAvatar'], $p['image'], $p['body'], $p['tags'], $p['readTime'],
        ]);
    $postCount++;
}
echo "Inserted {$postCount} new blog posts (" . (count($posts) - $postCount) . " already existed).\n";

// ── Banners ──────────────────────────────────────────────────────
$banners = [
    ['20% Off All Dental Care', 'Chews, brushes & cleanings this month only', null, '/shop?category=food', 'shop', 1],
    ['Free Delivery Over KES 5,000', 'Nairobi CBD & suburbs', null, '/shop', 'shop', 2],
    ['Rabies & Core Vaccines', 'Book your pet\'s annual shots — walk-ins welcome', null, '/appointments', 'services', 1],
    ['New Patient? First Wellness Exam Discounted', 'Meet our vets and get your pet\'s baseline health check', null, '/appointments', 'home', 1],
];
$bannerCount = 0;
foreach ($banners as [$title, $subtitle, $image, $link, $placement, $sort]) {
    $stmt = $pdo->prepare('SELECT id FROM banners WHERE title = ?');
    $stmt->execute([$title]);
    if ($stmt->fetch()) {
        continue;
    }
    $pdo->prepare('INSERT INTO banners (title, subtitle, image, link, placement, active, sort_order)
        VALUES (?, ?, ?, ?, ?, 1, ?)')
        ->execute([$title, $subtitle, $image, $link, $placement, $sort]);
    $bannerCount++;
}
echo "Inserted {$bannerCount} new banners (" . (count($banners) - $bannerCount) . " already existed).\n";

echo "Seed complete.\n";
