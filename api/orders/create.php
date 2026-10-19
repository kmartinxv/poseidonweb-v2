<?php
/**
 * Public: shop order from the checkout page. Prices are re-read from the
 * database (never trusted from the browser), then the clinic gets the order
 * and the customer gets a confirmation.
 */
require_once __DIR__ . '/../lib/bootstrap.php';
require_once __DIR__ . '/../lib/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}

$body = json_body();
guard_public_form($body, 'order');
require_fields($body, ['firstName', 'email', 'phone', 'address', 'items']);
if (!valid_email($body['email'])) {
    json_error('Please enter a valid email address', 422);
}
if (!is_array($body['items']) || count($body['items']) === 0) {
    json_error('Your cart is empty', 422);
}

$field = fn(string $k, int $max = 200) => mb_substr(trim((string)($body[$k] ?? '')), 0, $max);

// Look up real names and prices for the requested product ids.
$qtyById = [];
foreach (array_slice($body['items'], 0, 50) as $item) {
    $id  = (int)($item['id'] ?? 0);
    $qty = max(1, min(99, (int)($item['qty'] ?? 1)));
    if ($id > 0) $qtyById[$id] = ($qtyById[$id] ?? 0) + $qty;
}
if (!$qtyById) {
    json_error('Your cart is empty', 422);
}
$placeholders = implode(',', array_fill(0, count($qtyById), '?'));
$stmt = db()->prepare("SELECT id, name, price FROM products WHERE id IN ({$placeholders})");
$stmt->execute(array_keys($qtyById));
$products = $stmt->fetchAll();
if (!$products) {
    json_error('Those products are no longer available', 422);
}

$fmt = fn(float $n) => 'KES ' . number_format($n);
$subtotal = 0;
$itemRows = '';
foreach ($products as $p) {
    $qty  = $qtyById[(int)$p['id']];
    $line = (float)$p['price'] * $qty;
    $subtotal += $line;
    $itemRows .= '<tr><td style="padding:6px 0">' . e($p['name']) . ' × ' . $qty
               . '</td><td style="padding:6px 0;text-align:right">' . $fmt($line) . '</td></tr>';
}
// Same rule as the cart (src/composables/useCart.js).
$shipping = $subtotal > 5000 ? 0 : 350;
$total    = $subtotal + $shipping;

$orderRef = 'PV-' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
$itemsTable = '<table style="width:100%;border-collapse:collapse;font-size:14px">' . $itemRows
    . '<tr><td style="padding:6px 0;border-top:1px solid #e2e8f0">Shipping</td><td style="padding:6px 0;border-top:1px solid #e2e8f0;text-align:right">' . ($shipping ? $fmt($shipping) : 'FREE') . '</td></tr>'
    . '<tr><td style="padding:6px 0"><strong>Total</strong></td><td style="padding:6px 0;text-align:right"><strong>' . $fmt($total) . '</strong></td></tr></table>';

$payments = ['mpesa' => 'M-Pesa', 'card' => 'Card', 'cash' => 'Cash on delivery'];
$name  = trim($field('firstName', 60) . ' ' . $field('lastName', 60));
$email = $field('email', 160);
$customer = [
    'Order ref' => $orderRef,
    'Name'      => $name,
    'Phone'     => $field('phone', 30),
    'Email'     => $email,
    'Address'   => $field('address'),
    'Area'      => $field('area', 100),
    'City'      => $field('city', 60),
    'Payment'   => $payments[$body['payment'] ?? ''] ?? $field('payment', 30),
];

$clinicTo = mail_config()['notify_to'] ?? 'info@poseidonvet.com';

send_mail(
    $clinicTo,
    "New shop order {$orderRef} — {$fmt($total)}",
    email_layout('New shop order', email_rows($customer) . '<hr style="border:0;border-top:1px solid #e2e8f0;margin:16px 0">' . $itemsTable),
    $email
);

try {
    send_mail(
        $email,
        "Your Poseidon Vet order {$orderRef}",
        email_layout('Thank you for your order, ' . $field('firstName', 60) . '!',
            '<p>We have received your order <strong>' . e($orderRef) . '</strong> and will process it within 24 hours. We will contact you to arrange payment and delivery.</p>'
            . $itemsTable
            . '<p>Questions? Reply to this email or call us on +254 20 2470436.</p>'),
        $clinicTo
    );
} catch (Throwable $e) {
    error_log('Order customer email failed: ' . $e->getMessage());
}

json_response(['ok' => true, 'ref' => $orderRef]);
