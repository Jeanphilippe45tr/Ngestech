<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$categories = getAllCategories();
$brands = getAllBrands();

$pageTitle = "Our Brand - PowerWave outboards";
$pageDescription = "Discover PowerWave outboards - Leading manufacturer of premium marine engines. Innovation, reliability, and performance for over 50 years.";
$pageKeywords = "PowerWave outboards, marine engines, boat motors, outboard technology, marine innovation";

includeHeader($pageTitle, $pageDescription, $pageKeywords);
?>
    <!-- Main Content -->
    <main>
        <?php displayMessage(); ?>
        
        <!-- Brand Hero Section -->
        <section class="brand-hero">
            <div class="hero-content">
                <div class="container">
                    <div class="hero-text">
                        <h1>PowerWave outboards</h1>
                        <h2>Powering Your Maritime Adventures Since 1970</h2>
                        <p>For over five decades, PowerWave has been at the forefront of marine propulsion innovation, crafting outboard motors that combine cutting-edge technology with unmatched reliability.</p>
                        <a href="products.php" class="btn btn-primary">Explore Our Motors</a>
                    </div>
                    <div class="hero-image">
                        <img src="images/hero-outboard.jpg" alt="PowerWave Outboard Motors" loading="lazy">
                    </div>
                </div>
            </div>
        </section>

        <!-- Brand Story Section -->
        <section class="brand-story">
            <div class="container">
                <div class="story-content">
                    <div class="story-text">
                        <h2>Our Story</h2>
                        <p>Founded in 1970 by marine engineering pioneer Captain James PowerWave, our company began with a simple mission: to create the most reliable and efficient outboard motors for passionate boaters worldwide.</p>
                        <p>What started in a small workshop in Miami has grown into a global leader in marine propulsion technology. Today, PowerWave outboards power vessels in over 80 countries, from weekend fishing trips to professional racing circuits.</p>
                        <p>Our commitment to innovation has led to groundbreaking developments in fuel efficiency, emissions reduction, and digital integration, making us the preferred choice for both recreational boaters and marine professionals.</p>
                    </div>
                    <div class="story-stats">
                        <div class="stat">
                            <h3>50+</h3>
                            <p>Years of Excellence</p>
                        </div>
                        <div class="stat">
                            <h3>2M+</h3>
                            <p>Motors Worldwide</p>
                        </div>
                        <div class="stat">
                            <h3>80+</h3>
                            <p>Countries Served</p>
                        </div>
                        <div class="stat">
                            <h3>#1</h3>
                            <p>Customer Satisfaction</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Brand Values Section -->
        <section class="brand-values">
            <div class="container">
                <h2 class="section-title">Our Core Values</h2>
                <div class="values-grid">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h3>Innovation</h3>
                        <p>We continuously push the boundaries of marine technology, investing heavily in R&D to bring you the most advanced outboard motors on the market.</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Reliability</h3>
                        <p>Every PowerWave motor is built to last, with rigorous testing and quality control ensuring dependable performance in any conditions.</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3>Sustainability</h3>
                        <p>We're committed to protecting the waters we love, developing eco-friendly technologies that reduce emissions and environmental impact.</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Community</h3>
                        <p>We support boating communities worldwide through sponsorships, education programs, and partnerships with marine conservation organizations.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Technology Section -->
        <section class="brand-technology">
            <div class="container">
                <div class="tech-content">
                    <div class="tech-text">
                        <h2>Cutting-Edge Technology</h2>
                        <h3>PowerWave Advanced Propulsion System (PAPS)</h3>
                        <p>Our proprietary PAPS technology optimizes fuel injection, ignition timing, and propeller design to deliver:</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Up to 30% better fuel efficiency</li>
                            <li><i class="fas fa-check"></i> 40% reduction in harmful emissions</li>
                            <li><i class="fas fa-check"></i> Whisper-quiet operation</li>
                            <li><i class="fas fa-check"></i> Smart diagnostics and monitoring</li>
                            <li><i class="fas fa-check"></i> Seamless smartphone integration</li>
                        </ul>
                        <a href="products.php" class="btn btn-outline">Discover Our Technology</a>
                    </div>
                    <div class="tech-features">
                        <div class="feature-highlight">
                            <i class="fas fa-microchip"></i>
                            <h4>Smart Engine Management</h4>
                            <p>AI-powered systems optimize performance in real-time</p>
                        </div>
                        <div class="feature-highlight">
                            <i class="fas fa-mobile-alt"></i>
                            <h4>PowerWave Connect App</h4>
                            <p>Monitor and control your motor from your smartphone</p>
                        </div>
                        <div class="feature-highlight">
                            <i class="fas fa-tools"></i>
                            <h4>Self-Diagnostic System</h4>
                            <p>Predictive maintenance alerts prevent costly repairs</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Awards and Recognition -->
        <section class="brand-awards">
            <div class="container">
                <h2 class="section-title">Awards & Recognition</h2>
                <div class="awards-grid">
                    <div class="award">
                        <i class="fas fa-trophy"></i>
                        <h3>Marine Innovation Award 2023</h3>
                        <p>International Marine Technology Association</p>
                    </div>
                    <div class="award">
                        <i class="fas fa-medal"></i>
                        <h3>Best Outboard Motor 2023</h3>
                        <p>Boating Magazine</p>
                    </div>
                    <div class="award">
                        <i class="fas fa-star"></i>
                        <h3>Customer Choice Award</h3>
                        <p>Marine Industry Association</p>
                    </div>
                    <div class="award">
                        <i class="fas fa-leaf"></i>
                        <h3>Green Technology Leader</h3>
                        <p>Environmental Marine Council</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="brand-cta">
            <div class="container">
                <div class="cta-content">
                    <h2>Ready to Experience PowerWave Excellence?</h2>
                    <p>Join millions of satisfied customers worldwide who trust PowerWave for their marine adventures.</p>
                    <div class="cta-buttons">
                        <a href="products.php" class="btn btn-primary">Shop Motors</a>
                        <a href="contact.php" class="btn btn-outline">Find a Dealer</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

<?php includeFooter(); ?>
