<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$categories = getAllCategories();
$brands = getAllBrands();

$pageTitle = 'Shipping Information';
$pageDescription = "Learn about shipping options for outboard motors and marine equipment. Fast delivery, free shipping on qualifying orders, and specialized handling.";
$pageKeywords = "outboard motor shipping, marine equipment delivery, free shipping, freight delivery, shipping rates";

includeHeader($pageTitle, $pageDescription, $pageKeywords);
?>
    <!-- Main Content -->
    <main>
        <?php displayMessage(); ?>
        
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <div class="header-content">
                    <h1>Shipping Information</h1>
                    <p>Fast, secure delivery for all outboard motors and marine equipment. We offer multiple shipping options to get your order delivered safely and on time.</p>
                </div>
            </div>
        </section>
        
        <!-- Shipping Content -->
        <section class="policy-section">
            <div class="container">
                <div class="policy-content">
                    
                    <!-- Free Shipping Overview -->
                    <div class="policy-card">
                        <div class="policy-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="policy-text">
                            <h2>Free Shipping on Orders Over $1,000</h2>
                            <p>Enjoy complimentary shipping on all orders over $1,000. We use specialized freight carriers experienced in handling marine equipment to ensure your order arrives safely.</p>
                        </div>
                    </div>
                    
                    <!-- Shipping Options -->
                    <div class="content-section">
                        <h2>Shipping Options</h2>
                        <div class="info-grid">
                            <div class="info-card">
                                <h3><i class="fas fa-truck"></i> Standard Freight</h3>
                                <p><strong>5-10 Business Days</strong><br>
                                Best for outboard motors and large accessories. Professional freight delivery with liftgate service available.</p>
                                <ul style="margin-top: 12px;">
                                    <li>Free on orders over $1,000</li>
                                    <li>Curbside delivery included</li>
                                    <li>Liftgate service available</li>
                                    <li>Appointment scheduling</li>
                                </ul>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-plane"></i> Express Shipping</h3>
                                <p><strong>2-3 Business Days</strong><br>
                                Expedited delivery for smaller accessories and parts when you need them fast.</p>
                                <ul style="margin-top: 12px;">
                                    <li>Available for items under 150 lbs</li>
                                    <li>Signature required delivery</li>
                                    <li>Tracking information provided</li>
                                    <li>Insurance included</li>
                                </ul>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-star"></i> White Glove Delivery</h3>
                                <p><strong>Scheduled Appointment</strong><br>
                                Premium delivery service with inside delivery and unpacking for outboard motors.</p>
                                <ul style="margin-top: 12px;">
                                    <li>Inside delivery to your location</li>
                                    <li>Professional unpacking</li>
                                    <li>Debris removal</li>
                                    <li>Inspection upon delivery</li>
                                </ul>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-store"></i> Local Pickup</h3>
                                <p><strong>Same Day Availability</strong><br>
                                Save on shipping and pick up your order at our facility. Perfect for local customers.</p>
                                <ul style="margin-top: 12px;">
                                    <li>No shipping charges</li>
                                    <li>Same-day pickup available</li>
                                    <li>Expert loading assistance</li>
                                    <li>Pre-delivery inspection</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping Rates -->
                    <div class="content-section">
                        <h2>Shipping Rates</h2>
                        <p>Shipping costs are calculated based on item weight, dimensions, and destination. Here are our standard rates:</p>
                        
                        <div class="shipping-table">
                            <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                                <thead>
                                    <tr style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                        <th style="padding: 12px; text-align: left; border: 1px solid #e2e8f0;">Item Type</th>
                                        <th style="padding: 12px; text-align: left; border: 1px solid #e2e8f0;">Weight Range</th>
                                        <th style="padding: 12px; text-align: left; border: 1px solid #e2e8f0;">Shipping Cost</th>
                                        <th style="padding: 12px; text-align: left; border: 1px solid #e2e8f0;">Delivery Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="border: 1px solid #e2e8f0;">
                                        <td style="padding: 12px; border: 1px solid #e2e8f0;">Small Accessories</td>
                                        <td style="padding: 12px; border: 1px solid #e2e8f0;">Under 10 lbs</td>
                                        <td style="padding: 12px; border: 1px solid #e2e8f0;">$15.99</td>
                                        <td style="padding: 12px; border: 1px solid #e2e8f0;">3-5 Business Days</td>
                                    </tr>
                                    <tr style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                        <td style="padding: 12px; border: 1px solid #e2e8f0;">Medium Parts</td>
                                        <td style="padding: 12px; border: 1px solid #e2e8f0;">10-50 lbs</td>
                                        <td style="padding: 12px; border: 1px solid #e2e8f0;">$49.99</td>
                                        <td style="padding: 12px; border: 1px solid #e2e8f0;">5-7 Business Days</td>
                                    </tr>
                                    <tr style="border: 1px solid #e2e8f0;">
                                        <td style="padding: 12px; border: 1px solid #e2e8f0;">Large Accessories</td>
                                        <td style="padding: 12px; border: 1px solid #e2e8f0;">50-150 lbs</td>
                                        <td style="padding: 12px; border: 1px solid #e2e8f0;">$99.99</td>
                                        <td style="padding: 12px; border: 1px solid #e2e8f0;">5-10 Business Days</td>
                                    </tr>
                                    <tr style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                        <td style="padding: 12px; border: 1px solid #e2e8f0;">Outboard Motors</td>
                                        <td style="padding: 12px; border: 1px solid #e2e8f0;">150+ lbs</td>
                                        <td style="padding: 12px; border: 1px solid #e2e8f0;">$199.99 - $499.99</td>
                                        <td style="padding: 12px; border: 1px solid #e2e8f0;">7-14 Business Days</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="highlight-box">
                            <h3><i class="fas fa-gift"></i> Free Shipping Qualifications</h3>
                            <ul>
                                <li>Orders over $1,000 qualify for free standard freight shipping</li>
                                <li>Free shipping applies to the contiguous United States</li>
                                <li>Alaska, Hawaii, and international destinations have additional charges</li>
                                <li>Some oversized items may have additional handling fees</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Processing & Handling -->
                    <div class="content-section">
                        <h2>Order Processing & Handling</h2>
                        <p>We take great care in preparing your order for shipment to ensure it arrives in perfect condition.</p>
                        
                        <div class="process-steps">
                            <div class="step">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <h3>Order Verification</h3>
                                    <p>We verify all order details, payment information, and shipping addresses within 24 hours of receiving your order.</p>
                                </div>
                            </div>
                            <div class="step">
                                <div class="step-number">2</div>
                                <div class="step-content">
                                    <h3>Professional Packaging</h3>
                                    <p>Items are carefully packaged using marine-grade protective materials and custom crating for outboard motors.</p>
                                </div>
                            </div>
                            <div class="step">
                                <div class="step-number">3</div>
                                <div class="step-content">
                                    <h3>Quality Inspection</h3>
                                    <p>Every item receives a final inspection before shipping to ensure it meets our quality standards.</p>
                                </div>
                            </div>
                            <div class="step">
                                <div class="step-number">4</div>
                                <div class="step-content">
                                    <h3>Shipment & Tracking</h3>
                                    <p>Your order ships with full tracking information and delivery confirmation.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Delivery Information -->
                    <div class="content-section">
                        <h2>Delivery Information</h2>
                        <div class="grid grid-2">
                            <div class="condition-card">
                                <h3><i class="fas fa-calendar-alt"></i> Delivery Scheduling</h3>
                                <ul>
                                    <li>Standard freight requires appointment scheduling</li>
                                    <li>2-hour delivery windows available</li>
                                    <li>Saturday delivery available for additional fee</li>
                                    <li>We'll call 24 hours before delivery</li>
                                    <li>Reschedule deliveries up to 3 times at no charge</li>
                                </ul>
                            </div>
                            <div class="condition-card">
                                <h3><i class="fas fa-home"></i> Delivery Requirements</h3>
                                <ul>
                                    <li>Someone 18+ must be present to sign for delivery</li>
                                    <li>Clear access path to delivery location required</li>
                                    <li>Liftgate service available for ground-level delivery</li>
                                    <li>Indoor delivery available with white glove service</li>
                                    <li>Inspect items before signing delivery receipt</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Special Handling -->
                    <div class="content-section">
                        <h2>Special Handling for Outboard Motors</h2>
                        <p>Outboard motors require specialized handling and shipping due to their size, weight, and sensitivity.</p>
                        
                        <div class="info-grid">
                            <div class="info-card">
                                <h3><i class="fas fa-box"></i> Custom Crating</h3>
                                <p>All outboard motors are professionally crated using marine-grade materials to prevent damage during transit.</p>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-shield-alt"></i> Insurance Coverage</h3>
                                <p>Full insurance coverage included for the complete value of your outboard motor during shipping.</p>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-tools"></i> Drain & Prep</h3>
                                <p>Motors are properly drained of fluids and prepared for shipping according to DOT regulations.</p>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-truck-loading"></i> Specialized Carriers</h3>
                                <p>We use freight carriers experienced in handling marine equipment with proper lifting equipment.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping Zones -->
                    <div class="content-section">
                        <h2>Shipping Zones & Timeframes</h2>
                        <div class="zone-info">
                            <div class="zone-card">
                                <h3><i class="fas fa-map-marked-alt"></i> Zone 1 - Local (Within 200 miles)</h3>
                                <div class="zone-details">
                                    <div class="zone-time">1-3 Business Days</div>
                                    <div class="zone-features">
                                        <p>Local delivery available • Same-day pickup • Installation scheduling</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="zone-card">
                                <h3><i class="fas fa-map-marker-alt"></i> Zone 2 - Regional (200-1000 miles)</h3>
                                <div class="zone-details">
                                    <div class="zone-time">3-7 Business Days</div>
                                    <div class="zone-features">
                                        <p>Standard freight delivery • Liftgate service available • Tracking provided</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="zone-card">
                                <h3><i class="fas fa-globe-americas"></i> Zone 3 - National (1000+ miles)</h3>
                                <div class="zone-details">
                                    <div class="zone-time">7-14 Business Days</div>
                                    <div class="zone-features">
                                        <p>Cross-country freight • White glove delivery available • Full insurance</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- International Shipping -->
                    <div class="content-section">
                        <h2>International Shipping</h2>
                        <p>We ship to select international destinations. International orders require special handling due to customs and documentation requirements.</p>
                        
                        <div class="grid grid-2">
                            <div class="condition-card">
                                <h3><i class="fas fa-globe"></i> Available Countries</h3>
                                <ul>
                                    <li>Canada</li>
                                    <li>Mexico</li>
                                    <li>United Kingdom</li>
                                    <li>European Union</li>
                                    <li>Australia</li>
                                    <li>Caribbean Islands</li>
                                </ul>
                                <p><small>Contact us for shipping to other destinations</small></p>
                            </div>
                            <div class="condition-card">
                                <h3><i class="fas fa-file-alt"></i> International Requirements</h3>
                                <ul>
                                    <li>Additional customs documentation required</li>
                                    <li>Customer responsible for import duties and taxes</li>
                                    <li>Extended delivery times (14-30 business days)</li>
                                    <li>Restricted items may not be available</li>
                                    <li>Special packaging for international transport</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Tracking -->
                    <div class="content-section">
                        <h2>Order Tracking & Updates</h2>
                        <p>Stay informed about your order status with our comprehensive tracking system.</p>
                        
                        <div class="info-grid">
                            <div class="info-card">
                                <h3><i class="fas fa-envelope"></i> Email Notifications</h3>
                                <p>Receive automatic email updates when your order is processed, shipped, and delivered.</p>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-mobile-alt"></i> SMS Updates</h3>
                                <p>Optional text message notifications for delivery appointments and status updates.</p>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-search"></i> Online Tracking</h3>
                                <p>Track your order 24/7 through our website or the carrier's tracking portal.</p>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-phone"></i> Personal Updates</h3>
                                <p>Call our customer service team for personal order updates and delivery scheduling.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Delivery Issues -->
                    <div class="content-section">
                        <h2>Delivery Issues & Damage</h2>
                        <p>While rare, delivery issues can occur. Here's how we handle them:</p>
                        
                        <div class="emergency-contact">
                            <h3><i class="fas fa-exclamation-triangle"></i> If Your Order is Damaged</h3>
                            <ul>
                                <li>Do not sign for damaged packages</li>
                                <li>Note any visible damage on the delivery receipt</li>
                                <li>Take photos of damaged packaging</li>
                                <li>Contact us immediately at (555) 123-4567</li>
                                <li>Keep all packaging materials for inspection</li>
                            </ul>
                        </div>
                        
                        <div class="highlight-box">
                            <h3><i class="fas fa-redo"></i> Missed Delivery Policy</h3>
                            <p>If you miss a scheduled delivery:</p>
                            <ol>
                                <li>Contact the carrier within 24 hours to reschedule</li>
                                <li>Additional delivery attempts may incur fees after the third attempt</li>
                                <li>Items will be returned to us after one week</li>
                                <li>Reshipment fees may apply for returned items</li>
                            </ol>
                        </div>
                    </div>
                    
                    <!-- Shipping Support -->
                    <div class="contact-section">
                        <h2>Shipping Support</h2>
                        <p>Our shipping department is ready to help with delivery scheduling, tracking, and any shipping-related questions.</p>
                        
                        <div class="contact-options">
                            <div class="contact-option">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <h4>Shipping Hotline</h4>
                                    <p>(555) 123-4567 ext. 3<br>Mon-Fri: 8AM-6PM EST</p>
                                </div>
                            </div>
                            <div class="contact-option">
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <h4>Email Support</h4>
                                    <p>shipping@outboardmotorspro.com<br>Response within 4 hours</p>
                                </div>
                            </div>
                            <div class="contact-option">
                                <i class="fas fa-truck"></i>
                                <div>
                                    <h4>Track Your Order</h4>
                                    <p>Login to your account<br>Real-time tracking available</p>
                                </div>
                            </div>
                        </div>
                        
                        <div style="text-align: center; margin-top: 32px;">
                            <a href="contact.php" class="btn btn-primary">Contact Shipping Department</a>
                            <a href="products.php" class="btn btn-outline" style="margin-left: 8px;">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php includeFooter(); ?>