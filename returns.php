<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$categories = getAllCategories();
$brands = getAllBrands();

$pageTitle = 'Returns & Refunds Policy';
$pageDescription = "Learn about our returns and refunds policy for outboard motors and marine equipment. Easy returns within 30 days.";
$pageKeywords = "returns policy, refunds, outboard motor returns, marine equipment returns";

includeHeader($pageTitle, $pageDescription, $pageKeywords);
?>
    <!-- Main Content -->
    <main>
        <?php displayMessage(); ?>
        
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <div class="header-content">
                    <h1>Returns & Refunds Policy</h1>
                    <p>We want you to be completely satisfied with your purchase. Learn about our easy return process and customer-friendly policies.</p>
                </div>
            </div>
        </section>
        
        <!-- Returns Content -->
        <section class="policy-section">
            <div class="container">
                <div class="policy-content">
                    
                    <!-- Overview -->
                    <div class="policy-card">
                        <div class="policy-icon">
                            <i class="fas fa-undo-alt"></i>
                        </div>
                        <div class="policy-text">
                            <h2>Easy Returns Within 30 Days</h2>
                            <p>We offer a hassle-free 30-day return policy on most items. If you're not completely satisfied with your purchase, we'll help make it right.</p>
                        </div>
                    </div>
                    
                    <!-- Return Conditions -->
                    <div class="content-section">
                        <h2>Return Conditions</h2>
                        <div class="grid grid-2">
                            <div class="condition-card">
                                <h3><i class="fas fa-check-circle"></i> Eligible for Return</h3>
                                <ul>
                                    <li>Item is in original condition</li>
                                    <li>Original packaging included</li>
                                    <li>All accessories and manuals included</li>
                                    <li>No signs of use or installation</li>
                                    <li>Returned within 30 days of purchase</li>
                                    <li>Original receipt or order number provided</li>
                                </ul>
                            </div>
                            <div class="condition-card">
                                <h3><i class="fas fa-times-circle"></i> Not Eligible for Return</h3>
                                <ul>
                                    <li>Items installed or mounted on boats</li>
                                    <li>Custom or special order items</li>
                                    <li>Items damaged by misuse</li>
                                    <li>Consumable items (oil, filters, etc.)</li>
                                    <li>Electrical items that have been connected</li>
                                    <li>Items returned after 30 days</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Return Process -->
                    <div class="content-section">
                        <h2>How to Return an Item</h2>
                        <div class="process-steps">
                            <div class="step">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <h3>Contact Us</h3>
                                    <p>Call us at (555) 123-4567 or email <?php echo SITE_EMAIL; ?> to initiate your return. We'll provide you with a Return Authorization Number (RAN).</p>
                                </div>
                            </div>
                            <div class="step">
                                <div class="step-number">2</div>
                                <div class="step-content">
                                    <h3>Package Your Item</h3>
                                    <p>Carefully package the item in its original packaging with all accessories, manuals, and documentation.</p>
                                </div>
                            </div>
                            <div class="step">
                                <div class="step-number">3</div>
                                <div class="step-content">
                                    <h3>Ship to Us</h3>
                                    <p>Use the prepaid return label we provide or ship to our return center. Include your RAN with the package.</p>
                                </div>
                            </div>
                            <div class="step">
                                <div class="step-number">4</div>
                                <div class="step-content">
                                    <h3>Receive Refund</h3>
                                    <p>Once we receive and inspect your return, we'll process your refund within 3-5 business days.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Refund Information -->
                    <div class="content-section">
                        <h2>Refund Information</h2>
                        <div class="info-grid">
                            <div class="info-card">
                                <h3><i class="fas fa-credit-card"></i> Refund Method</h3>
                                <p>Refunds will be issued to the original payment method used for the purchase.</p>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-clock"></i> Processing Time</h3>
                                <p>Refunds are processed within 3-5 business days after we receive your return.</p>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-shipping-fast"></i> Return Shipping</h3>
                                <p>We provide prepaid return labels for defective items. Customer pays return shipping for other returns.</p>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-percentage"></i> Restocking Fee</h3>
                                <p>No restocking fee for items returned in original condition within 30 days.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Exchange Policy -->
                    <div class="content-section">
                        <h2>Exchange Policy</h2>
                        <p>We're happy to exchange items for different models or sizes. Exchanges follow the same conditions as returns and must be initiated within 30 days of purchase.</p>
                        
                        <div class="highlight-box">
                            <h3><i class="fas fa-exchange-alt"></i> Exchange Process</h3>
                            <ol>
                                <li>Contact us to discuss your exchange needs</li>
                                <li>We'll check availability of the desired item</li>
                                <li>Return your original item following our return process</li>
                                <li>We'll ship your new item once the original is received</li>
                                <li>Pay any price difference or receive a refund for the difference</li>
                            </ol>
                        </div>
                    </div>
                    
                    <!-- Damaged Items -->
                    <div class="content-section">
                        <h2>Damaged or Defective Items</h2>
                        <p>If you receive a damaged or defective item, please contact us immediately. We'll arrange for a replacement or refund at no cost to you.</p>
                        
                        <div class="emergency-contact">
                            <h3><i class="fas fa-exclamation-triangle"></i> Report Damage Immediately</h3>
                            <p>For damaged shipments, please:</p>
                            <ul>
                                <li>Take photos of the damage and packaging</li>
                                <li>Keep all packaging materials</li>
                                <li>Contact us within 48 hours of delivery</li>
                                <li>Do not attempt to use or install damaged items</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Contact for Returns -->
                    <div class="contact-section">
                        <h2>Questions About Returns?</h2>
                        <p>Our customer service team is here to help with any questions about returns or exchanges.</p>
                        
                        <div class="contact-options">
                            <div class="contact-option">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <h4>Call Us</h4>
                                    <p>(555) 123-4567<br>Mon-Fri: 8AM-6PM EST</p>
                                </div>
                            </div>
                            <div class="contact-option">
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <h4>Email Support</h4>
                                    <p><?php echo SITE_EMAIL; ?><br>Response within 24 hours</p>
                                </div>
                            </div>
                            <div class="contact-option">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <h4>Visit Our Store</h4>
                                    <p>123 Marina Drive<br>Coastal City, CC 12345</p>
                                </div>
                            </div>
                        </div>
                        
                        <div style="text-align: center; margin-top: 32px;">
                            <a href="contact.php" class="btn btn-primary">Contact Customer Service</a>
                            <a href="products.php" class="btn btn-outline" style="margin-left: 8px;">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php includeFooter(); ?>
