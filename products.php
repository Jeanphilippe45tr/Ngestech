<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Sanitize and get all filter parameters from GET request
$q = isset($_GET['q']) ? sanitizeInput($_GET['q']) : '';
$category = isset($_GET['category']) ? (int)$_GET['category'] : null;
$brand = isset($_GET['brand']) ? (int)$_GET['brand'] : null;
$minPrice = isset($_GET['min']) && $_GET['min'] !== '' ? (float)$_GET['min'] : null;
$maxPrice = isset($_GET['max']) && $_GET['max'] !== '' ? (float)$_GET['max'] : null;
$page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
$limit = PRODUCTS_PER_PAGE;
$offset = ($page - 1) * $limit;

// Check if any filters are active
$isFiltered = !empty($q) || !empty($category) || !empty($brand) || $minPrice !== null || $maxPrice !== null;

$products = [];
$total = 0;

try {
    // Get all categories and brands for the filter dropdowns
    $categories = getAllCategories();
    $brands = getAllBrands();

    // Call the refactored search function
    $searchResult = searchProducts($q, $category, $brand, $minPrice, $maxPrice, $limit, $offset);
    $products = $searchResult['products'];
    $total = $searchResult['total'];
} catch (Exception $e) {
    // Log the error and display a friendly message
    error_log('Products page error: ' . $e->getMessage());
    showMessage('An error occurred while trying to load products. Please try again later.', 'error');
}

// Calculate total pages for pagination
$totalPages = max(1, (int)ceil($total / $limit));

$pageTitle = 'Products';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo generateCSRFToken(); ?>">
  <title><?php echo $pageTitle; ?> - <?php echo SITE_NAME; ?></title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/responsive.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <header class="header">
    <div class="container">
      <div class="main-header">
        <div class="logo">
          <a href="index.php">
            <img src="logo1.png" alt="<?php echo SITE_NAME; ?>" style="height: 50px; width: auto;">
            <h1><?php echo SITE_NAME; ?></h1>
          </a>
        </div>
        <div class="search-bar">
          <form action="products.php" method="GET" class="search-form">
            <input type="text" name="q" placeholder="Search..." value="<?php echo $q; ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
          </form>
        </div>
        <div class="cart-info">
          <a href="cart.php" class="cart-link">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count"><?php echo getCartItemCount(isLoggedIn() ? $_SESSION['user_id'] : null); ?></span>
            <span class="cart-total"><?php echo formatPrice(getCartTotal(isLoggedIn() ? $_SESSION['user_id'] : null)); ?></span>
          </a>
        </div>
      </div>
      <nav class="navigation">
        <ul class="nav-menu">
          <li><a href="index.php">Home</a></li>
          <li><a class="active" href="products.php">Products</a></li>
          <li><a href="accessories.php">Accessories</a></li>
          <li><a href="brand.php">Our Brand</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
        <div class="mobile-menu-toggle"><i class="fas fa-bars"></i></div>
      </nav>
    </div>
  </header>

  <main class="container">
    <h2 class="section-title">Browse Outboard Motors</h2>
    <div class="filters">
      <form method="GET" action="products.php">
        <input type="hidden" name="q" value="<?php echo $q; ?>" />
        <select name="category">
          <option value="">All Categories</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?php echo $c['id']; ?>" <?php echo $category===$c['id']?'selected':''; ?>><?php echo sanitizeInput($c['name']); ?></option>
          <?php endforeach; ?>
        </select>
        <select name="brand">
          <option value="">All Brands</option>
          <?php foreach ($brands as $b): ?>
            <option value="<?php echo $b['id']; ?>" <?php echo $brand===$b['id']?'selected':''; ?>><?php echo sanitizeInput($b['name']); ?></option>
          <?php endforeach; ?>
        </select>
        <input type="number" name="min" step="0.01" placeholder="Min Price" value="<?php echo $minPrice!==null?$minPrice:''; ?>">
        <input type="number" name="max" step="0.01" placeholder="Max Price" value="<?php echo $maxPrice!==null?$maxPrice:''; ?>">
        <button class="btn btn-outline" type="submit">Filter</button>
        <a class="btn" href="products.php">Reset</a>
      </form>
    </div>

    <div id="add-to-cart-message" style="margin-bottom: 15px;"></div>

    <div class="products-grid">
      <?php if (empty($products)): ?>
        <div style="text-align: center; padding: 40px; background: white; border-radius: 12px; border: 1px solid #e2e8f0; grid-column: 1 / -1;">
            <i class="fas fa-search" style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px;"></i>
            <h3><?php echo $isFiltered ? 'No Products Match Your Filters' : 'No Products Found'; ?></h3>
            <p><?php echo $isFiltered ? 'Try adjusting or clearing your filters to see more results.' : 'Check back later for more products.'; ?></p>
            <?php if ($isFiltered): ?>
                <a href="products.php" class="btn btn-primary">Clear Filters</a>
            <?php endif; ?>
        </div>
      <?php else: ?>
        <?php foreach ($products as $p): ?>
          <div class="product-card">
            <div class="product-image">
              <a href="product.php?id=<?php echo $p['id']; ?>">
                <img src="<?php echo getProductImageUrl($p['main_image']); ?>" alt="<?php echo sanitizeInput($p['name']); ?>">
              </a>
              <?php if ($p['sale_price']): ?><span class="sale-badge">Sale</span><?php endif; ?>
              <div class="product-actions">
                <form class="add-to-cart-form">
                    <input type="hidden" name="item_id" value="<?php echo $p['id']; ?>">
                    <input type="hidden" name="item_type" value="product">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" title="Add to Cart"><i class="fas fa-cart-plus"></i></button>
                </form>
                <a class="btn-quick-view" href="product.php?id=<?php echo $p['id']; ?>" title="Quick View"><i class="fas fa-eye"></i></a>
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
      <?php 
        // Prepare base URL for pagination links, preserving existing filters
        $queryParams = $_GET;
      ?>
      <?php for ($i=1; $i<=$totalPages; $i++): ?>
        <?php $queryParams['page'] = $i; ?>
        <a class="btn <?php echo $i===$page?'btn-primary':'btn-outline'; ?>" href="?<?php echo http_build_query($queryParams); ?>"><?php echo $i; ?></a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>
  </main>

  <footer class="footer">
    <div class="container">
      <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
        <div class="footer-links">
          <a href="privacy.php">Privacy Policy</a>
          <a href="terms.php">Terms of Service</a>
        </div>
      </div>
    </div>
  </footer>

  <script src="js/main.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.add-to-cart-form');
        const messageDiv = document.getElementById('add-to-cart-message');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                formData.append('csrf_token', csrfToken);

                const button = form.querySelector('button[type="submit"]');
                const originalButtonIcon = button.innerHTML;
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                fetch('add_to_cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const cartCountEl = document.querySelector('.cart-count');
                        const cartTotalEl = document.querySelector('.cart-total');
                        if (cartCountEl) cartCountEl.textContent = data.cart_count;
                        if (cartTotalEl) cartTotalEl.textContent = data.cart_total_formatted;

                        messageDiv.innerHTML = '<div class="alert alert-success">Product added to cart!</div>';
                        setTimeout(() => messageDiv.innerHTML = '', 3000);
                    } else {
                        messageDiv.innerHTML = `<div class="alert alert-error">Error: ${data.message}</div>`;
                        setTimeout(() => messageDiv.innerHTML = '', 5000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    messageDiv.innerHTML = '<div class="alert alert-error">An unexpected error occurred.</div>';
                    setTimeout(() => messageDiv.innerHTML = '', 5000);
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = originalButtonIcon;
                });
            });
        });
    });
  </script>
</body>
</html>
