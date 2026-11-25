<?php
$title = 'Shop';
require 'includes/header.php';

$cat    = $_GET['cat'] ?? '';
$q      = trim($_GET['q'] ?? '');
$sort   = $_GET['sort'] ?? 'new';
$orders = ['new' => 'p.created_at DESC, p.id DESC', 'low' => 'p.price ASC', 'high' => 'p.price DESC'];
$orderBy = $orders[$sort] ?? $orders['new'];

$sql = "SELECT p.*, c.name AS cat FROM products p JOIN categories c ON c.id = p.category_id WHERE 1=1";
$params = [];
if ($cat !== '') { $sql .= " AND c.slug = ?"; $params[] = $cat; }
if ($q !== '')   { $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)"; $params[] = "%$q%"; $params[] = "%$q%"; }
$sql .= " ORDER BY $orderBy";
$st = $pdo->prepare($sql);
$st->execute($params);
$products = $st->fetchAll();
$cats = $pdo->query("SELECT * FROM categories ORDER BY id")->fetchAll();
?>
<section class="hero">
  <h1>Gear for long study sessions.</h1>
  <p>Pens, lamps, stands and keyboards picked for people who spend all day at a desk.</p>
  <a class="btn btn-accent" href="#products">Browse the shelf</a>
</section>

<section id="products" class="shop">
  <form class="toolbar" method="get" action="index.php#products">
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Search products" aria-label="Search products">
    <select name="sort" aria-label="Sort products" onchange="this.form.submit()">
      <option value="new"  <?= $sort==='new'?'selected':'' ?>>Newest</option>
      <option value="low"  <?= $sort==='low'?'selected':'' ?>>Price: low to high</option>
      <option value="high" <?= $sort==='high'?'selected':'' ?>>Price: high to low</option>
    </select>
    <?php if ($cat): ?><input type="hidden" name="cat" value="<?= e($cat) ?>"><?php endif; ?>
    <button class="btn" type="submit">Search</button>
  </form>

  <nav class="chips" aria-label="Categories">
    <a class="chip <?= $cat===''?'on':'' ?>" href="index.php#products">All</a>
    <?php foreach ($cats as $c): ?>
      <a class="chip <?= $cat===$c['slug']?'on':'' ?>" href="index.php?cat=<?= e($c['slug']) ?>#products"><?= e($c['name']) ?></a>
    <?php endforeach; ?>
  </nav>

  <?php if (!$products): ?>
    <p class="empty">Nothing matches that search. Clear the filters to see every product.</p>
  <?php else: ?>
  <div class="grid">
    <?php foreach ($products as $p): ?>
    <article class="card">
      <a class="thumb" href="product.php?id=<?= (int)$p['id'] ?>" style="background:<?= e($p['color']) ?>">
        <span><?= $p['emoji'] ?></span>
      </a>
      <div class="card-body">
        <p class="muted"><?= e($p['cat']) ?></p>
        <h2><a href="product.php?id=<?= (int)$p['id'] ?>"><?= e($p['name']) ?></a></h2>
        <div class="row">
          <strong class="price"><?= money($p['price']) ?></strong>
          <?php if ($p['stock'] > 0): ?>
            <button class="btn btn-sm add-btn" data-id="<?= (int)$p['id'] ?>">Add to cart</button>
          <?php else: ?>
            <span class="sold">Sold out</span>
          <?php endif; ?>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
