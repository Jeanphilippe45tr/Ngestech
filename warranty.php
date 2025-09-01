<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$categories = getAllCategories();
$brands = getAllBrands();

$pageTitle = 'Warranty Information';
$pageDescription = "Learn about warranty coverage for outboard motors and marine equipment. Comprehensive warranty protection on all products.";
$pageKeywords = "outboard motor warranty, marine engine warranty, warranty coverage, warranty terms";

includeHeader($pageTitle, $pageDescription, $pageKeywords);
?>
    <!-- Main Content -->
    <main>
        <?php displayMessage(); ?>
        
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <div class="header-content">
                    <h1>Warranty Information</h1>
                    <p>Comprehensive warranty coverage on all outboard motors and marine equipment. Your investment is protected with industry-leading warranty terms.</p>
                </div>
            </div>
        </section>
        
        <!-- Warranty Content -->
        <section class="policy-section">
            <div class="container">
                <div class="policy-content">
                    
                    <!-- Overview -->
                    <div class="policy-card">
                        <div class="policy-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="policy-text">
                            <h2>Industry-Leading Warranty Protection</h2>
                            <p>We stand behind every product we sell with comprehensive warranty coverage that exceeds industry standards. Your peace of mind is our priority.</p>
                        </div>
                    </div>
                    
                    <!-- Warranty Coverage -->
                    <div class="content-section">
                        <h2>Warranty Coverage by Product Type</h2>
                        <div class="info-grid">
                            <div class="info-card">
                                <h3><i class="fas fa-cog"></i> New Outboard Motors</h3>
                                <ul>
                                    <li><strong>3-Year Limited Warranty</strong> on all new outboard motors</li>
                                    <li>Covers manufacturing defects and material failures</li>
                                    <li>Includes parts and labor for authorized repairs</li>
                                    <li>Transferable to subsequent owners</li>
                                </ul>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-tools"></i> Marine Accessories</h3>
                                <ul>
                                    <li><strong>1-Year Limited Warranty</strong> on accessories</li>
                                    <li>Covers propellers, controls, and rigging</li>
                                    <li>Manufacturing defect protection</li>
                                    <li>Free replacement for defective items</li>
                                </ul>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-recycle"></i> Certified Pre-Owned</h3>
                                <ul>
                                    <li><strong>6-Month Limited Warranty</strong> on used motors</li>
                                    <li>Comprehensive inspection before sale</li>
                                    <li>Major component coverage included</li>
                                    <li>Optional extended warranty available</li>
                                </ul>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-plus"></i> Extended Warranty</h3>
                                <ul>
                                    <li><strong>Up to 7 Years</strong> total coverage available</li>
                                    <li>Comprehensive coverage beyond standard warranty</li>
                                    <li>Nationwide service network</li>
                                    <li>24/7 roadside assistance included</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- What's Covered -->
                    <div class="content-section">
                        <h2>What's Covered</h2>
                        <div class="grid grid-2">
                            <div class="condition-card">
                                <h3><i class="fas fa-check-circle"></i> Covered Under Warranty</h3>
                                <ul>
                                    <li>Manufacturing defects in materials or workmanship</li>
                                    <li>Engine block and internal components</li>
                                    <li>Electrical system failures</li>
                                    <li>Fuel system components</li>
                                    <li>Cooling system components</li>
                                    <li>Ignition system defects</li>
                                    <li>Factory-installed accessories</li>
                                </ul>
                            </div>
                            <div class="condition-card">
                                <h3><i class="fas fa-times-circle"></i> Not Covered</h3>
                                <ul>
                                    <li>Normal wear and tear items (spark plugs, filters, impellers)</li>
                                    <li>Damage from improper installation</li>
                                    <li>Damage from contaminated fuel</li>
                                    <li>Accident or impact damage</li>
                                    <li>Corrosion from lack of maintenance</li>
                                    <li>Commercial or rental use (special terms apply)</li>
                                    <li>Modifications or aftermarket parts</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Warranty Registration -->
                    <div class="content-section">
                        <h2>Warranty Registration</h2>
                        <p>To ensure your warranty coverage, please register your product within 30 days of purchase.</p>
                        
                        <div class="process-steps">
                            <div class="step">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <h3>Keep Your Documentation</h3>
                                    <p>Save your original purchase receipt and all product documentation. You'll need these for warranty claims.</p>
                                </div>
                            </div>
                            <div class="step">
                                <div class="step-number">2</div>
                                <div class="step-content">
                                    <h3>Register Online</h3>
                                    <p>Visit the manufacturer's website or call us to register your product. This activates your warranty coverage.</p>
                                </div>
                            </div>
                            <div class="step">
                                <div class="step-number">3</div>
                                <div class="step-content">
                                    <h3>Schedule Installation</h3>
                                    <p>Use only authorized dealers for installation to maintain warranty coverage. We provide professional installation services.</p>
                                </div>
                            </div>
                            <div class="step">
                                <div class="step-number">4</div>
                                <div class="step-content">
                                    <h3>Follow Maintenance Schedule</h3>
                                    <p>Regular maintenance is required to keep your warranty valid. We offer full-service maintenance programs.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Warranty Claims -->
                    <div class="content-section">
                        <h2>Making a Warranty Claim</h2>
                        <p>If you experience a covered issue, we make the warranty claim process as simple as possible.</p>
                        
                        <div class="highlight-box">
                            <h3><i class="fas fa-clipboard-list"></i> Warranty Claim Process</h3>
                            <ol>
                                <li>Contact our service department at (555) 123-4567</li>
                                <li>Provide your product serial number and purchase information</li>
                                <li>Describe the issue you're experiencing</li>
                                <li>Schedule an inspection at our facility or authorized service center</li>
                                <li>We'll handle all manufacturer communications</li>
                                <li>Receive repair or replacement at no cost to you</li>
                            </ol>
                        </div>
                        
                        <div class="info-grid">
                            <div class="info-card">
                                <h3><i class="fas fa-clock"></i> Response Time</h3>
                                <p>We respond to warranty claims within 24 hours and aim to resolve issues within 5-7 business days.</p>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-map-marker-alt"></i> Service Locations</h3>
                                <p>Nationwide network of authorized service centers for convenient warranty service.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Maintenance Requirements -->
                    <div class="content-section">
                        <h2>Maintenance Requirements</h2>
                        <p>Regular maintenance is essential to keep your warranty valid and your motor running at peak performance.</p>
                        
                        <div class="grid grid-2">
                            <div class="condition-card">
                                <h3><i class="fas fa-calendar-alt"></i> Required Maintenance</h3>
                                <ul>
                                    <li><strong>Every 100 Hours or Annually:</strong> Oil change, filter replacement, spark plug inspection</li>
                                    <li><strong>Every 300 Hours:</strong> Impeller replacement, thermostat inspection</li>
                                    <li><strong>Seasonally:</strong> Winterization/de-winterization service</li>
                                    <li><strong>As Needed:</strong> Propeller inspection and replacement</li>
                                </ul>
                            </div>
                            <div class="condition-card">
                                <h3><i class="fas fa-wrench"></i> Service Records</h3>
                                <ul>
                                    <li>Keep detailed records of all maintenance</li>
                                    <li>Use only genuine OEM parts</li>
                                    <li>Have service performed by authorized technicians</li>
                                    <li>Follow manufacturer's maintenance schedule</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="emergency-contact">
                            <h3><i class="fas fa-exclamation-triangle"></i> Warranty Maintenance Tips</h3>
                            <p>To protect your warranty coverage:</p>
                            <ul>
                                <li>Never skip scheduled maintenance intervals</li>
                                <li>Use only recommended oils and fluids</li>
                                <li>Flush with fresh water after every saltwater use</li>
                                <li>Store properly during off-season</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Extended Warranty -->
                    <div class="content-section">
                        <h2>Extended Warranty Options</h2>
                        <p>Protect your investment beyond the standard warranty period with our extended warranty plans.</p>
                        
                        <div class="info-grid">
                            <div class="info-card">
                                <h3><i class="fas fa-shield-alt"></i> PowerGuard Plus</h3>
                                <p><strong>5-Year Total Coverage</strong><br>
                                Extends your warranty to 5 years total coverage with comprehensive protection including mechanical breakdown coverage.</p>
                                <ul style="margin-top: 12px;">
                                    <li>Covers all major components</li>
                                    <li>No deductible required</li>
                                    <li>Transferable coverage</li>
                                </ul>
                            </div>
                            <div class="info-card">
                                <h3><i class="fas fa-crown"></i> PowerGuard Elite</h3>
                                <p><strong>7-Year Total Coverage</strong><br>
                                Our most comprehensive coverage with additional benefits including annual maintenance services.</p>
                                <ul style="margin-top: 12px;">
                                    <li>Everything in PowerGuard Plus</li>
                                    <li>Annual maintenance included</li>
                                    <li>Priority service scheduling</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Warranty Support -->
                    <div class="contact-section">
                        <h2>Warranty Support</h2>
                        <p>Our dedicated warranty department is here to help with all your warranty needs and questions.</p>
                        
                        <div class="contact-options">
                            <div class="contact-option">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <h4>Warranty Hotline</h4>
                                    <p>(555) 123-4567 ext. 2<br>Mon-Fri: 7AM-7PM EST</p>
                                </div>
                            </div>
                            <div class="contact-option">
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <h4>Email Support</h4>
                                    <p>warranty@outboardmotorspro.com<br>Response within 4 hours</p>
                                </div>
                            </div>
                            <div class="contact-option">
                                <i class="fas fa-tools"></i>
                                <div>
                                    <h4>Service Center</h4>
                                    <p>123 Marina Drive<br>Full-service facility</p>
                                </div>
                            </div>
                        </div>
                        
                        <div style="text-align: center; margin-top: 32px;">
                            <a href="contact.php" class="btn btn-primary">Contact Warranty Department</a>
                            <a href="products.php" class="btn btn-outline" style="margin-left: 8px;">Shop Products</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

<?php includeFooter(); ?>