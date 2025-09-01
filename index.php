<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get featured products
$featuredProducts = getFeaturedProducts(6);
$categories = getAllCategories();
$brands = getAllBrands();

$pageTitle = "Home - Premium Outboard Motors";

includeHeader($pageTitle);
?>
    <!-- Main Content -->
    <main>
        <?php displayMessage(); ?>
        
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h2>Premium Outboard Motors</h2>
                <p>Discover the perfect outboard motor for your boat. Quality engines from trusted brands.</p>
                <a href="products.php" class="btn btn-primary">Shop Now</a>
            </div>
            <div class="hero-image">
                <img src="images/hero-outboard.jpg" alt="Premium Outboard Motors" loading="lazy">
            </div>
        </section>
        
        <!-- Featured Categories -->
        <section class="featured-categories">
            <div class="container">
                <h2 class="section-title">Shop by Category</h2>
                <div class="categories-grid">
                    <?php foreach (array_slice($categories, 0, 4) as $category): ?>
                        <div class="category-card">
                            <a href="products.php?category=<?php echo $category['id']; ?>">
                                <div class="category-image">
                                    <img src="<?php echo $category['image'] ? 'uploads/categories/' . $category['image'] : 'images/category-placeholder.jpg'; ?>" 
                                         alt="<?php echo sanitizeInput($category['name']); ?>" loading="lazy">
                                </div>
                                <h3><?php echo sanitizeInput($category['name']); ?></h3>
                                <p><?php echo sanitizeInput($category['description']); ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        
        <!-- Featured Products -->
        <section class="featured-products">
            <div class="container">
                <h2 class="section-title">Featured Products</h2>
                <div class="products-grid">
                    <?php foreach ($featuredProducts as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <a href="product.php?id=<?php echo $product['id']; ?>">
                                    <img src="<?php echo getProductImageUrl($product['main_image']); ?>" 
                                         alt="<?php echo sanitizeInput($product['name']); ?>" loading="lazy">
                                </a>
                                <?php if ($product['sale_price']): ?>
                                    <span class="sale-badge">Sale</span>
                                <?php endif; ?>
                                <div class="product-actions">
                                    <button class="btn-add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                    <button class="btn-wishlist" data-product-id="<?php echo $product['id']; ?>">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                    <a href="product.php?id=<?php echo $product['id']; ?>" class="btn-quick-view">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3><a href="product.php?id=<?php echo $product['id']; ?>"><?php echo sanitizeInput($product['name']); ?></a></h3>
                                <p class="product-brand"><?php echo sanitizeInput($product['brand_name']); ?></p>
                                <div class="product-specs">
                                    <span class="hp"><?php echo $product['horsepower']; ?>HP</span>
                                    <span class="stroke"><?php echo ucfirst($product['stroke']); ?></span>
                                </div>
                                <div class="product-price">
                                    <?php if ($product['sale_price']): ?>
                                        <span class="original-price"><?php echo formatPrice($product['price']); ?></span>
                                        <span class="sale-price"><?php echo formatPrice($product['sale_price']); ?></span>
                                    <?php else: ?>
                                        <span class="price"><?php echo formatPrice($product['price']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="product-rating">
                                    <div class="stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                    </div>
                                    <span class="rating-count">(24 reviews)</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center">
                    <a href="products.php" class="btn btn-outline">View All Products</a>
                </div>
            </div>
        </section>
        
        <!-- Features Section -->
        <section class="features">
            <div class="container">
                <div class="features-grid">
                    <div class="feature">
                        <i class="fas fa-shipping-fast"></i>
                        <h3>Free Shipping</h3>
                        <p>Free shipping on orders over $1,000</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-tools"></i>
                        <h3>Expert Service</h3>
                        <p>Professional installation and maintenance</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Warranty</h3>
                        <p>Comprehensive warranty on all products</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-headset"></i>
                        <h3>Support</h3>
                        <p>24/7 customer support and assistance</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Newsletter -->
        <section class="newsletter">
            <div class="container">
                <div class="newsletter-content">
                    <h2>Stay Updated</h2>
                    <p>Subscribe to our newsletter for the latest products and special offers</p>
                    <form class="newsletter-form" id="newsletter-form">
                        <label>
                            <input type="email" name="email" placeholder="Enter your email address" required>
                        </label>
                        <button type="submit" class="btn btn-primary">Subscribe</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

<?php includeFooter(); ?>
