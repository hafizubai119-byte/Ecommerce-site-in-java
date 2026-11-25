<?php
require_once 'config.php';
$st = $pdo->prepare("SELECT p.*, c.name AS cat FROM products p JOIN categories c ON c.id=p.category_id WHERE p.id=?");
$st->execute([(int)($_GET['id'] ?? 0)]);
$p = $st->fetch();
if (!$p) { http_response_code(404); }
$title = $p['name'] ?? 'Not found';
require 'includes/header.php';
if (!$p): ?>
  <section class="page"><h1>Product not found</h1><p>It may have been removed. <a href="index.php">Back to the shop</a></p></section>
<?php else: ?>
<section class="page detail">
  <div class="detail-art" style="background:<?= e($p['color']) ?>"><span><?= $p['emoji'] ?></span></div>
  <div>
    <p class="muted"><a href="index.php?cat=<?= e(strtolower(str_replace(' ','-',$p['cat']))) ?>"><?= e($p['cat']) ?></a></p>
    <h1><?= e($p['name']) ?></h1>
    <p class="price big"><?= money($p['price']) ?></p>
    <p class="lead"><?= e($p['description']) ?></p>
    <p class="muted"><?= $p['stock'] > 0 ? ($p['stock'] <= 15 ? "Only {$p['stock']} left" : 'In stock') : 'Sold out' ?></p>
    <?php if ($p['stock'] > 0): ?>
      <button class="btn btn-accent add-btn" data-id="<?= (int)$p['id'] ?>">Add to cart</button>
    <?php endif; ?>
  </div>
</section>
<?php endif; require 'includes/footer.php'; ?>
