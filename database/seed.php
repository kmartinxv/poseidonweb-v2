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
    ['Puppy Starter Kibble (3kg)', 'Food', 2400, null, 4.7, 121, null,
        'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?auto=format&fit=crop&w=400&h=400&q=80',
        'High-protein growth formula with DHA for brain and eye development. For puppies 2–12 months.', true],
    ['Kitten Wet Food Pouches (12-pack)', 'Food', 1850, 2100, 4.8, 96, 'Sale',
        'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=400&h=400&q=80',
        'Tender chicken and tuna chunks in gravy. Complete nutrition for kittens up to 12 months.', true],
    ['Senior Dog Formula (7+ Years)', 'Food', 3100, null, 4.6, 74, null,
        'https://images.unsplash.com/photo-1601758124510-52d02ddb7cbd?auto=format&fit=crop&w=400&h=400&q=80',
        'Lower-calorie recipe with glucosamine and antioxidants to support ageing joints and immunity.', true],
    ['Grain-Free Salmon Cat Food (2kg)', 'Food', 2650, null, 4.7, 63, 'New',
        'https://images.unsplash.com/photo-1574158622682-e40e69881006?auto=format&fit=crop&w=400&h=400&q=80',
        'Salmon-first dry food for cats with sensitive stomachs. Supports skin, coat, and urinary health.', true],
    ['Glucosamine Joint Support Chews', 'Supplements', 2200, 2500, 4.8, 132, 'Best Seller',
        'https://images.unsplash.com/photo-1568640347023-a616a30bc3bd?auto=format&fit=crop&w=400&h=400&q=80',
        'Glucosamine, chondroitin, and MSM chews for mobility in large breeds and senior dogs.', true],
    ['Multivitamin Tablets for Dogs', 'Supplements', 1300, null, 4.5, 47, null,
        'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=400&h=400&q=80',
        'Daily chewable multivitamin with vitamins A, D, E and B-complex for overall health.', true],
    ['Skin & Coat Biotin Supplement', 'Supplements', 1550, null, 4.6, 39, null,
        'https://images.unsplash.com/photo-1592194996308-7b43878e84a6?auto=format&fit=crop&w=400&h=400&q=80',
        'Biotin, zinc, and fatty acids to reduce shedding and restore a glossy coat.', true],
    ['Broad-Spectrum Dewormer Tablets', 'Medications', 650, null, 4.8, 288, 'Rx',
        'https://images.unsplash.com/photo-1530281700549-e82e7bf110d6?auto=format&fit=crop&w=400&h=400&q=80',
        'Treats roundworms, hookworms, whipworms, and tapeworms. Recommended every 3 months.', true],
    ['Frontline Plus Spot-On (Cats)', 'Medications', 1400, 1600, 4.7, 154, 'Sale',
        'https://images.unsplash.com/photo-1526336024174-e58f5cdd8e13?auto=format&fit=crop&w=400&h=400&q=80',
        'Monthly topical treatment that kills fleas, flea eggs, ticks, and chewing lice.', true],
    ['Ear Cleaning Solution', 'Medications', 950, null, 4.6, 71, null,
        'https://images.unsplash.com/photo-1517849845537-4d257902454a?auto=format&fit=crop&w=400&h=400&q=80',
        'Gentle drying ear cleanser that removes wax and debris and helps prevent ear infections.', true],
    ['Tick & Flea Collar (8 Months)', 'Medications', 2300, null, 4.5, 88, null,
        'https://images.unsplash.com/photo-1591946614720-90a587da4a36?auto=format&fit=crop&w=400&h=400&q=80',
        'Water-resistant collar giving up to 8 months of tick and flea protection. Adjustable fit.', false],
    ['Retractable Dog Leash (5m)', 'Accessories', 1700, null, 4.6, 112, null,
        'https://images.unsplash.com/photo-1544568100-847a948585b9?auto=format&fit=crop&w=400&h=400&q=80',
        'One-button brake and lock with ergonomic grip. For dogs up to 25 kg.', true],
    ['Airline-Approved Pet Carrier', 'Accessories', 5800, 6500, 4.7, 36, 'Sale',
        'https://images.unsplash.com/photo-1596854407944-bf87f6fdd49e?auto=format&fit=crop&w=400&h=400&q=80',
        'Ventilated soft-sided carrier with padded base and shoulder strap. For cats and small dogs.', true],
    ['Interactive Puzzle Feeder', 'Accessories', 1350, null, 4.5, 52, 'New',
        'https://images.unsplash.com/photo-1535294435445-d7249524ef2e?auto=format&fit=crop&w=400&h=400&q=80',
        'Slow-feed puzzle that turns mealtime into mental exercise and reduces gulping.', true],
    ['Self-Grooming Slicker Brush', 'Grooming', 900, null, 4.7, 103, null,
        'https://images.unsplash.com/photo-1587764379873-97837921fd44?auto=format&fit=crop&w=400&h=400&q=80',
        'Fine bent bristles remove loose undercoat and tangles. Button retracts bristles for easy cleaning.', true],
    ['Pet Nail Clippers with Guard', 'Grooming', 750, null, 4.6, 79, null,
        'https://images.unsplash.com/photo-1558929996-da64ba858215?auto=format&fit=crop&w=400&h=400&q=80',
        'Stainless steel clippers with safety guard to prevent over-cutting. Includes nail file.', true],
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
