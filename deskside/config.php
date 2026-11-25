<?php
// Edit these four values for your local MySQL (XAMPP default shown)
const DB_HOST = 'localhost';
const DB_NAME = 'deskside';
const DB_USER = 'root';
const DB_PASS = '';

session_start();
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed. Check config.php and import schema.sql.');
}

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money($n) { return '$' . number_format((float)$n, 2); }
function cart_count() { return array_sum($_SESSION['cart'] ?? []); }

// Cart lines are always priced from the database, never from the browser.
function cart_lines(PDO $pdo) {
    $cart = $_SESSION['cart'] ?? [];
    if (!$cart) return [];
    $ids = array_keys($cart);
    $in = implode(',', array_fill(0, count($ids), '?'));
    $st = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
    $st->execute($ids);
    $lines = [];
    foreach ($st as $p) {
        $p['qty'] = min($cart[$p['id']], $p['stock']);
        $p['line'] = $p['qty'] * $p['price'];
        $lines[] = $p;
    }
    return $lines;
}
