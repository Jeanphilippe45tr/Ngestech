<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$categories = getAllCategories();
$brands = getAllBrands();

$pageTitle = "Brands - All Outboard Motor Brands";

includeHeader($pageTitle);
?>
    <!-- Main Content -->
    <main>
        <?php displayMessage(); ?>
        
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <div class="header-content">
                    <h1>Shop by Brand</h1>
                    <p>Explore our complete selection of outboard motors from the world's leading manufacturers. Each brand offers unique features and technologies designed for different boating needs.</p>
                </div>
            </div>
        </section>
        
        <!-- Brands Grid -->
        <section class="brands-section">
            <div class="container">
                <div class="brands-grid">
                    <?php foreach ($brands as $brand): ?>
                        <?php
                        // Get product count for this brand
                        $db = Database::getInstance();
                        $productCount = $db->fetchColumn(
                            "SELECT COUNT(*) FROM products WHERE brand_id = ? AND status = 'active'",
                            [$brand['id']]
                        );
                        ?>
                        <div class="brand-card">
                            <a href="products.php?brand=<?php echo $brand['id']; ?>">
                                <div class="brand-logo">
                                    <?php if ($brand['logo'] && file_exists('uploads/brands/' . $brand['logo'])): ?>
                                        <img src="uploads/brands/<?php echo $brand['logo']; ?>" 
                                             alt="<?php echo sanitizeInput($brand['name']); ?> Logo" loading="lazy">
                                    <?php else: ?>
                                        <div class="brand-placeholder">
                                            <i class="fas fa-anchor"></i>
                                            <span><?php echo strtoupper(substr(sanitizeInput($brand['name']), 0, 3)); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="brand-info">
                                    <h3><?php echo sanitizeInput($brand['name']); ?></h3>
                                    <p class="brand-description"><?php echo sanitizeInput($brand['description'] ?? 'Premium outboard motors'); ?></p>
                                    <div class="brand-stats">
                                        <span class="product-count">
                                            <i class="fas fa-cog"></i>
                                            <?php echo $productCount; ?> <?php echo $productCount === 1 ? 'Model' : 'Models'; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="brand-arrow">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        
        <!-- Brand Features -->
        <section class="brand-features">
            <div class="container">
                <h2 class="section-title">Why Choose Authorized Dealers?</h2>
                <div class="features-grid">
                    <div class="feature">
                        <i class="fas fa-certificate"></i>
                        <h3>Genuine Products</h3>
                        <p>All products are genuine and come with full manufacturer warranties</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-tools"></i>
                        <h3>Expert Service</h3>
                        <p>Factory-trained technicians for professional installation and service</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-shipping-fast"></i>
                        <h3>Fast Delivery</h3>
                        <p>Quick shipping and delivery options for all brand products</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-headset"></i>
                        <h3>Brand Support</h3>
                        <p>Direct access to manufacturer support and technical assistance</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

<?php includeFooter(); ?>