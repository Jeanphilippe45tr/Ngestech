<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$categories = getAllCategories();
$brands = getAllBrands();

$pageTitle = "Marine Accessories - PowerWave outboards";

// Sample accessories data (in a real application, this would come from the database)
$accessories = [
    [
        'id' => 1,
        'name' => 'PowerWave Premium Propeller Set',
        'description' => 'High-performance stainless steel propellers designed for maximum efficiency and durability.',
        'price' => 299.99,
        'sale_price' => null,
        'image' => 'images/accessories/propeller-set.jpg',
        'category' => 'Propellers',
        'rating' => 4.8
    ],
    [
        'id' => 2,
        'name' => 'Marine Engine Oil - Synthetic Blend',
        'description' => 'Premium synthetic blend oil specifically formulated for PowerWave outboard motors.',
        'price' => 89.99,
        'sale_price' => 69.99,
        'image' => 'images/accessories/engine-oil.jpg',
        'category' => 'Maintenance',
        'rating' => 4.9
    ],
    [
        'id' => 3,
        'name' => 'Outboard Motor Cover - Waterproof',
        'description' => 'Heavy-duty waterproof cover to protect your outboard motor from the elements.',
        'price' => 149.99,
        'sale_price' => null,
        'image' => 'images/accessories/motor-cover.jpg',
        'category' => 'Protection',
        'rating' => 4.7
    ],
    [
        'id' => 4,
        'name' => 'PowerWave Digital Gauge Kit',
        'description' => 'Advanced digital gauge system with RPM, fuel flow, and engine diagnostics.',
        'price' => 899.99,
        'sale_price' => null,
        'image' => 'images/accessories/gauge-kit.jpg',
        'category' => 'Electronics',
        'rating' => 4.6
    ],
    [
        'id' => 5,
        'name' => 'Fuel Water Separator Filter',
        'description' => 'Essential fuel system component that removes water and contaminants from fuel.',
        'price' => 45.99,
        'sale_price' => 34.99,
        'image' => 'images/accessories/fuel-filter.jpg',
        'category' => 'Maintenance',
        'rating' => 4.8
    ],
    [
        'id' => 6,
        'name' => 'Outboard Motor Stand - Heavy Duty',
        'description' => 'Adjustable heavy-duty stand for secure storage and maintenance of outboard motors.',
        'price' => 199.99,
        'sale_price' => null,
        'image' => 'images/accessories/motor-stand.jpg',
        'category' => 'Storage',
        'rating' => 4.5
    ],
    [
        'id' => 7,
        'name' => 'PowerWave Remote Control Kit',
        'description' => 'Complete remote control system for convenient motor operation from anywhere on your boat.',
        'price' => 549.99,
        'sale_price' => 499.99,
        'image' => 'images/accessories/remote-control.jpg',
        'category' => 'Controls',
        'rating' => 4.7
    ],
    [
        'id' => 8,
        'name' => 'Stainless Steel Trim Tabs',
        'description' => 'Premium stainless steel trim tabs for improved boat performance and fuel efficiency.',
        'price' => 379.99,
        'sale_price' => null,
        'image' => 'images/accessories/trim-tabs.jpg',
        'category' => 'Performance',
        'rating' => 4.6
    ]
];

$accessoryCategories = [
    'All' => 'All Accessories',
    'Propellers' => 'Propellers',
    'Maintenance' => 'Maintenance',
    'Protection' => 'Protection',
    'Electronics' => 'Electronics',
    'Storage' => 'Storage',
    'Controls' => 'Controls',
    'Performance' => 'Performance'
];

$selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'All';

includeHeader($pageTitle);
?>
    <!-- Main Content -->
    <main>
        <?php displayMessage(); ?>
        
        <!-- Accessories Hero Section -->
        <section class="accessories-hero">
            <div class="container">
                <div class="hero-content">
                    <h1>Marine Accessories</h1>
                    <p>Premium accessories and parts to enhance your PowerWave outboard motor experience. From performance upgrades to essential maintenance items.</p>
                </div>
            </div>
        </section>

        <!-- Category Filter -->
        <section class="accessories-filter">
            <div class="container">
                <div class="filter-tabs">
                    <?php foreach ($accessoryCategories as $catKey => $catName): ?>
                        <a href="accessories.php?category=<?php echo $catKey; ?>" 
                           class="filter-tab <?php echo $selectedCategory === $catKey ? 'active' : ''; ?>">
                            <?php echo $catName; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Accessories Grid -->
        <section class="accessories-products">
            <div class="container">
                <div class="products-grid">
                    <?php foreach ($accessories as $accessory): ?>
                        <?php if ($selectedCategory === 'All' || $selectedCategory === $accessory['category']): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="<?php echo $accessory['image']; ?>" alt="<?php echo $accessory['name']; ?>" loading="lazy">
                                    <?php if ($accessory['sale_price']): ?>
                                        <span class="sale-badge">Sale</span>
                                    <?php endif; ?>
                                    <div class="product-actions">
                                        <button class="btn-add-to-cart" data-product-id="acc_<?php echo $accessory['id']; ?>">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                        <button class="btn-wishlist" data-product-id="acc_<?php echo $accessory['id']; ?>">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                        <button class="btn-quick-view" data-product-id="acc_<?php echo $accessory['id']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <h3><?php echo $accessory['name']; ?></h3>
                                    <p class="product-category"><?php echo $accessory['category']; ?></p>
                                    <p class="product-description"><?php echo $accessory['description']; ?></p>
                                    <div class="product-price">
                                        <?php if ($accessory['sale_price']): ?>
                                            <span class="original-price"><?php echo formatPrice($accessory['price']); ?></span>
                                            <span class="sale-price"><?php echo formatPrice($accessory['sale_price']); ?></span>
                                        <?php else: ?>
                                            <span class="price"><?php echo formatPrice($accessory['price']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-rating">
                                        <div class="stars">
                                            <?php 
                                            $rating = $accessory['rating'];
                                            $fullStars = floor($rating);
                                            $hasHalfStar = $rating - $fullStars >= 0.5;
                                            
                                            for ($i = 0; $i < $fullStars; $i++): ?>
                                                <i class="fas fa-star"></i>
                                            <?php endfor; 
                                            
                                            if ($hasHalfStar): ?>
                                                <i class="fas fa-star-half-alt"></i>
                                            <?php endif;
                                            
                                            for ($i = $fullStars + ($hasHalfStar ? 1 : 0); $i < 5; $i++): ?>
                                                <i class="far fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="rating-count">(<?php echo rand(15, 50); ?> reviews)</span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Accessories Info Section -->
        <section class="accessories-info">
            <div class="container">
                <div class="info-grid">
                    <div class="info-card">
                        <i class="fas fa-medal"></i>
                        <h3>Genuine Parts</h3>
                        <p>All accessories are genuine PowerWave parts designed specifically for optimal performance and compatibility.</p>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-shipping-fast"></i>
                        <h3>Fast Shipping</h3>
                        <p>Quick delivery on all accessories with free shipping on orders over $75. Most items ship within 24 hours.</p>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-tools"></i>
                        <h3>Expert Support</h3>
                        <p>Need installation help? Our certified technicians provide expert guidance and support for all accessories.</p>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Quality Guarantee</h3>
                        <p>Every accessory comes with our quality guarantee and manufacturer warranty for your peace of mind.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php includeFooter(); ?>
