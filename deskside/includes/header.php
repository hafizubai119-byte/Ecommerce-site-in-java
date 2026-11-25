<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'Deskside') ?> | Deskside</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header">
  <a class="logo" href="index.php">Deskside</a>
  <a class="cart-link" href="cart.php" aria-label="Cart">Cart <span id="cart-count" class="badge"><?= cart_count() ?></span></a>
</header>
<main>
