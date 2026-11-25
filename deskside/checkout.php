<?php
require_once 'config.php';
$lines = cart_lines($pdo);
$errors = []; $done = null;
$v = ['name' => '', 'email' => '', 'address' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $lines) {
    foreach ($v as $k => $_) $v[$k] = trim($_POST[$k] ?? '');
    if (strlen($v['name']) < 2) $errors['name'] = 'Enter your full name.';
    if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Enter a valid email address.';
    if (strlen($v['address']) < 8) $errors['address'] = 'Enter your full delivery address.';

    if (!$errors) {
        $total = array_sum(array_column($lines, 'line'));
        try {
            $pdo->beginTransaction();
            $pdo->prepare("INSERT INTO orders (customer_name,email,address,total) VALUES (?,?,?,?)")
                ->execute([$v['name'], $v['email'], $v['address'], $total]);
            $oid = $pdo->lastInsertId();
            $ins = $pdo->prepare("INSERT INTO order_items (order_id,product_id,quantity,unit_price) VALUES (?,?,?,?)");
            $dec = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");
            foreach ($lines as $l) {
                $dec->execute([$l['qty'], $l['id'], $l['qty']]);
                if ($dec->rowCount() === 0) throw new Exception($l['name'] . ' just sold out.');
                $ins->execute([$oid, $l['id'], $l['qty'], $l['price']]);
            }
            $pdo->commit();
            unset($_SESSION['cart']);
            $done = $oid;
        } catch (Exception $ex) {
            $pdo->rollBack();
            $errors['form'] = $ex->getMessage() . ' Update your cart and try again.';
        }
    }
}
$title = 'Checkout';
require 'includes/header.php';
$total = array_sum(array_column($lines, 'line'));
?>
<section class="page">
<?php if ($done): ?>
  <div class="success">
    <h1>Order #<?= (int)$done ?> is placed</h1>
    <p>Thanks, <?= e($v['name']) ?>. A confirmation will go to <?= e($v['email']) ?>.</p>
    <a class="btn btn-accent" href="index.php">Keep shopping</a>
  </div>
<?php elseif (!$lines): ?>
  <h1>Checkout</h1><p class="empty">Your cart is empty. <a href="index.php">Back to the shop</a></p>
<?php else: ?>
  <h1>Checkout</h1>
  <?php if (isset($errors['form'])): ?><p class="alert"><?= e($errors['form']) ?></p><?php endif; ?>
  <div class="cart-wrap">
    <form method="post" class="form" novalidate>
      <?php foreach (['name' => 'Full name', 'email' => 'Email'] as $k => $label): ?>
        <label>
          <?= $label ?>
          <input name="<?= $k ?>" type="<?= $k==='email'?'email':'text' ?>" value="<?= e($v[$k]) ?>" required>
          <?php if (isset($errors[$k])): ?><span class="err"><?= e($errors[$k]) ?></span><?php endif; ?>
        </label>
      <?php endforeach; ?>
      <label>
        Delivery address
        <textarea name="address" rows="3" required><?= e($v['address']) ?></textarea>
        <?php if (isset($errors['address'])): ?><span class="err"><?= e($errors['address']) ?></span><?php endif; ?>
      </label>
      <button class="btn btn-accent" type="submit">Place order</button>
    </form>
    <aside class="summary">
      <h2>Your items</h2>
      <?php foreach ($lines as $l): ?>
        <div class="row"><span><?= e($l['name']) ?> × <?= (int)$l['qty'] ?></span><span><?= money($l['line']) ?></span></div>
      <?php endforeach; ?>
      <div class="row total"><span>Total</span><span><?= money($total) ?></span></div>
    </aside>
  </div>
<?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
