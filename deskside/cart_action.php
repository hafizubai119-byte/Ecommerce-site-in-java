<?php
// JSON endpoint used by assets/app.js
require_once 'config.php';
header('Content-Type: application/json');
$in = json_decode(file_get_contents('php://input'), true) ?: [];
$id = (int)($in['id'] ?? 0);
$action = $in['action'] ?? '';

$st = $pdo->prepare("SELECT stock FROM products WHERE id=?");
$st->execute([$id]);
$stock = $st->fetchColumn();
if ($stock === false) { http_response_code(404); echo json_encode(['error' => 'Product not found']); exit; }

$cart = &$_SESSION['cart'];
$cart = $cart ?? [];
$cur = $cart[$id] ?? 0;

if ($action === 'add') {
    if ($cur + 1 > $stock) { echo json_encode(['error' => 'No more stock available', 'count' => cart_count()]); exit; }
    $cart[$id] = $cur + 1;
} elseif ($action === 'set') {
    $qty = max(0, min((int)($in['qty'] ?? 0), (int)$stock));
    if ($qty === 0) unset($cart[$id]); else $cart[$id] = $qty;
} elseif ($action === 'remove') {
    unset($cart[$id]);
}
echo json_encode(['ok' => true, 'count' => cart_count()]);
