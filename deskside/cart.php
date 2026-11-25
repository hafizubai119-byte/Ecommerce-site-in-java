<?php
$title = 'Cart';
require 'includes/header.php';
$lines = cart_lines($pdo);
$total = array_sum(array_column($lines, 'line'));
?>
<section class="page">
  <h1>Your cart</h1>
  <?php if (!$lines): ?>
    <p class="empty">Your cart is empty. <a href="index.php">Find something for your desk</a>.</p>
  <?php else: ?>
  <div class="cart-wrap">
    <div class="cart-list">
      <?php foreach ($lines as $l): ?>
      <div class="cart-row">
        <div class="mini" style="background:<?= e($l['color']) ?>"><?= $l['emoji'] ?></div>
        <div class="grow">
          <a href="product.php?id=<?= (int)$l['id'] ?>"><strong><?= e($l['name']) ?></strong></a>
          <p class="muted"><?= money($l['price']) ?> each</p>
          <button class="link remove-btn" data-id="<?= (int)$l['id'] ?>">Remove</button>
        </div>
        <div class="qty">
          <button class="qty-btn" data-id="<?= (int)$l['id'] ?>" data-qty="<?= $l['qty']-1 ?>" aria-label="Decrease quantity">−</button>
          <span><?= (int)$l['qty'] ?></span>
          <button class="qty-btn" data-id="<?= (int)$l['id'] ?>" data-qty="<?= $l['qty']+1 ?>" aria-label="Increase quantity">+</button>
        </div>
        <strong class="line"><?= money($l['line']) ?></strong>
      </div>
      <?php endforeach; ?>
    </div>
    <aside class="summary">
      <h2>Order summary</h2>
      <div class="row"><span>Subtotal</span><span><?= money($total) ?></span></div>
      <div class="row"><span>Shipping</span><span>Free</span></div>
      <div class="row total"><span>Total</span><span><?= money($total) ?></span></div>
      <a class="btn btn-accent block" href="checkout.php">Go to checkout</a>
    </aside>
  </div>
  <?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
