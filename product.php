<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = getProductById($id);
if (!$product) { showMessage('Product not found', 'error'); redirect('products.php'); }
$images = getProductImages($id);

$pageTitle = sanitizeInput($product['name']);

includeHeader($pageTitle);
?>
  <main class="container">
    <div class="product-page">
      <div class="product-gallery">
        <img src="<?php echo getProductImageUrl($product['main_image']); ?>" alt="<?php echo sanitizeInput($product['name']); ?>">
        <?php if ($images): ?>
          <div class="grid grid-4" style="margin-top:12px;">
            <?php foreach ($images as $img): ?>
              <img style="border:1px solid #e2e8f0; border-radius:8px;" src="<?php echo getProductImageUrl($img['image_path']); ?>" alt="<?php echo sanitizeInput($img['alt_text'] ?? $product['name']); ?>">
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="product-summary">
        <h2 class="title"><?php echo sanitizeInput($product['name']); ?></h2>
        <div class="meta">Brand: <?php echo sanitizeInput($product['brand_name']); ?> • Model: <?php echo sanitizeInput($product['model']); ?></div>
        <div class="price">
          <?php if ($product['sale_price']): ?>
            <span class="original-price" style="text-decoration:line-through; color:#64748b; font-size:16px; margin-right:8px;">
              <?php echo formatPrice($product['price']); ?>
            </span>
            <span><?php echo formatPrice($product['sale_price']); ?></span>
          <?php else: ?>
            <span><?php echo formatPrice($product['price']); ?></span>
          <?php endif; ?>
        </div>
        <div>
          <strong>Specifications</strong>
          <ul>
            <li>Horsepower: <?php echo (int)$product['horsepower']; ?> HP</li>
            <li>Stroke: <?php echo ucfirst($product['stroke']); ?></li>
            <li>Fuel: <?php echo ucfirst($product['fuel_type']); ?></li>
            <li>Shaft Length: <?php echo ucwords(str_replace('-', ' ', $product['shaft_length'])); ?></li>
            <?php if ($product['weight']): ?><li>Weight: <?php echo formatWeight($product['weight']); ?></li><?php endif; ?>
            <?php if ($product['displacement']): ?><li>Displacement: <?php echo $product['displacement']; ?> cc</li><?php endif; ?>
            <?php if ($product['cylinders']): ?><li>Cylinders: <?php echo (int)$product['cylinders']; ?></li><?php endif; ?>
          </ul>
        </div>
        <?php if (!empty($product['description'])): ?>
          <div>
            <strong>Description</strong>
            <p style="color:#475569;"><?php echo nl2br(sanitizeInput($product['description'])); ?></p>
          </div>
        <?php endif; ?>
        <form method="POST" action="add_to_cart.php" style="display:flex; gap:10px; align-items:center;">
          <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <label>
                <input class="input qty" type="number" name="quantity" value="1" min="1">
            </label>
            <button class="btn btn-primary" type="submit"><i class="fas fa-cart-plus"></i> Add to Cart</button>
        </form>
      </div>
    </div>
  </main>
<?php includeFooter(); ?>
