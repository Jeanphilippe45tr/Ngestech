<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$q = isset($_GET['q']) ? sanitizeInput($_GET['q']) : '';
$category = isset($_GET['category']) ? (int)$_GET['category'] : null;
$brand = isset($_GET['brand']) ? (int)$_GET['brand'] : null;
$minPrice = isset($_GET['min']) ? (float)$_GET['min'] : null;
$maxPrice = isset($_GET['max']) ? (float)$_GET['max'] : null;
$page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
$limit = PRODUCTS_PER_PAGE;
$offset = ($page - 1) * $limit;

$categories = getAllCategories();
$brands = getAllBrands();
$products = searchProducts($q, $category, $brand, $minPrice, $maxPrice, $limit, $offset);

// For simplicity, approximate total for pagination
$db = Database::getInstance();
$params = [];
$where = ["status = 'active'"];
if ($q) { $where[] = "(name LIKE ? OR description LIKE ? OR model LIKE ?)"; $params[] = "%$q%"; $params[] = "%$q%"; $params[] = "%$q%"; }
if ($category) { $where[] = "category_id = ?"; $params[] = $category; }
if ($brand) { $where[] = "brand_id = ?"; $params[] = $brand; }
if ($minPrice !== null) { $where[] = "price >= ?"; $params[] = $minPrice; }
if ($maxPrice !== null) { $where[] = "price <= ?"; $params[] = $maxPrice; }
$whereSql = implode(' AND ', $where);
$total = (int)$db->fetchColumn("SELECT COUNT(*) FROM products WHERE $whereSql", $params);
$totalPages = max(1, (int)ceil($total / $limit));

$pageTitle = 'Products';

includeHeader($pageTitle);
?>
  <main class="container">
    <h2 class="section-title">Browse Outboard Motors</h2>
    <div class="filters">
      <form method="GET" action="products.php">
        <input type="hidden" name="q" value="<?php echo $q; ?>" />
          <label>
              <select name="category">
                <option value="">All Categories</option>
                <?php foreach ($categories as $c): ?>
                  <option value="<?php echo $c['id']; ?>" <?php echo $category===$c['id']?'selected':''; ?>><?php echo sanitizeInput($c['name']); ?></option>
                <?php endforeach; ?>
              </select>
          </label>
          <label>
              <select name="brand">
              <option value="">All Brands</option>
              <?php foreach ($brands as $b): ?>
                <option value="<?php echo $b['id']; ?>" <?php echo $brand===$b['id']?'selected':''; ?>><?php echo sanitizeInput($b['name']); ?></option>
              <?php endforeach; ?>
            </select>
          </label>
          <label>
              <input type="number" name="min" step="0.01" placeholder="Min Price" value="<?php echo $minPrice!==null?$minPrice:''; ?>">
          </label>
          <label>
              <input type="number" name="max" step="0.01" placeholder="Max Price" value="<?php echo $maxPrice!==null?$maxPrice:''; ?>">
          </label>
          <button class="btn btn-outline" type="submit">Filter</button>
        <a class="btn" href="products.php">Reset</a>
      </form>
    </div>

    <div class="products-grid">
      <?php if (!$products): ?>
        <p>No products found.</p>
      <?php else: ?>
        <?php foreach ($products as $p): ?>
          <div class="product-card">
            <div class="product-image">
              <a href="product.php?id=<?php echo $p['id']; ?>">
                <img src="<?php echo getProductImageUrl($p['main_image']); ?>" alt="<?php echo sanitizeInput($p['name']); ?>">
              </a>
              <?php if ($p['sale_price']): ?><span class="sale-badge">Sale</span><?php endif; ?>
              <div class="product-actions">
                <button class="btn-add-to-cart" data-product-id="<?php echo $p['id']; ?>"><i class="fas fa-cart-plus"></i></button>
                <a class="btn-quick-view" href="product.php?id=<?php echo $p['id']; ?>"><i class="fas fa-eye"></i></a>
              </div>
            </div>
            <div class="product-info">
              <h3><a href="product.php?id=<?php echo $p['id']; ?>"><?php echo sanitizeInput($p['name']); ?></a></h3>
              <p class="product-brand"><?php echo sanitizeInput($p['brand_name']); ?></p>
              <div class="product-specs"><span><?php echo (int)$p['horsepower']; ?>HP</span> <span><?php echo ucfirst($p['stroke']); ?></span></div>
              <div class="product-price">
                <?php if ($p['sale_price']): ?>
                  <span class="original-price"><?php echo formatPrice($p['price']); ?></span>
                  <span class="sale-price"><?php echo formatPrice($p['sale_price']); ?></span>
                <?php else: ?>
                  <span class="price"><?php echo formatPrice($p['price']); ?></span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="pagination" style="display:flex; gap:8px; justify-content:center; margin:16px 0;">
      <?php for ($i=1; $i<=$totalPages; $i++): ?>
        <?php $params = $_GET; $params['page']=$i; $url = 'products.php?'.http_build_query($params); ?>
        <a class="btn <?php echo $i===$page?'btn-primary':'btn-outline'; ?>" href="<?php echo $url; ?>"><?php echo $i; ?></a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>
  </main>
<?php includeFooter(); ?>