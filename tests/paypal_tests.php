<?php
/**
 * Comprehensive PayPal Integration Test Suite
 * Tests all aspects of the PayPal integration
 */

// Prevent session issues
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$testResults = [];
$overallStatus = true;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayPal Integration Tests</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f8fafc; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 32px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #0ea5e9; border-bottom: 3px solid #0ea5e9; padding-bottom: 10px; }
        h2 { color: #1f2937; margin-top: 30px; }
        .test-result { padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid; }
        .test-pass { background: #d1fae5; border-left-color: #10b981; }
        .test-fail { background: #fee2e2; border-left-color: #ef4444; }
        .test-warning { background: #fef3c7; border-left-color: #f59e0b; }
        .test-info { background: #e0f2fe; border-left-color: #0ea5e9; }
        .code { background: #1f2937; color: #e5e7eb; padding: 10px; border-radius: 4px; font-family: monospace; margin: 5px 0; }
        .summary { background: #f1f5f9; padding: 20px; border-radius: 8px; margin: 20px 0; }
        pre { background: #f8fafc; padding: 10px; border-radius: 4px; overflow-x: auto; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 PayPal Integration Test Suite</h1>
        <p>Comprehensive testing of all PayPal integration components.</p>
        
        <?php
        // Test 1: Configuration
        testConfiguration();
        
        // Test 2: Database Schema
        testDatabaseSchema();
        
        // Test 3: PayPal Service Class
        testPayPalService();
        
        // Test 4: API Endpoints
        testAPIEndpoints();
        
        // Test 5: Helper Functions
        testHelperFunctions();
        
        // Display summary
        displayTestSummary();
        ?>
    </div>
</body>
</html>

<?php

function testConfiguration() {
    global $testResults, $overallStatus;
    
    echo "<h2>🔧 Configuration Tests</h2>";
    
    // Test 1.1: Basic configuration
    try {
        require_once '../includes/config.php';
        addTestResult('✅ Config file loaded successfully', 'pass');
        
        // Check database constants
        if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER')) {
            addTestResult('✅ Database constants defined', 'pass');
        } else {
            addTestResult('❌ Missing database constants', 'fail');
        }
        
        // Check site constants
        if (defined('SITE_URL') && defined('SITE_NAME') && defined('SITE_EMAIL')) {
            addTestResult('✅ Site constants defined', 'pass');
        } else {
            addTestResult('❌ Missing site constants', 'fail');
        }
        
    } catch (Exception $e) {
        addTestResult('❌ Config file error: ' . $e->getMessage(), 'fail');
    }
    
    // Test 1.2: PayPal configuration
    try {
        require_once '../includes/paypal_config.php';
        addTestResult('✅ PayPal config file loaded', 'pass');
        
        if (defined('PAYPAL_CLIENT_ID') && defined('PAYPAL_CLIENT_SECRET')) {
            addTestResult('✅ PayPal constants defined', 'pass');
            
            // Check if credentials are set (not placeholders)
            if (PAYPAL_CLIENT_ID !== 'YOUR_PAYPAL_CLIENT_ID' && PAYPAL_CLIENT_ID !== '{{PAYPAL_CLIENT_ID}}') {
                addTestResult('✅ PayPal credentials configured', 'pass');
            } else {
                addTestResult('⚠️ PayPal credentials are placeholders', 'warning');
            }
        } else {
            addTestResult('❌ Missing PayPal constants', 'fail');
        }
        
    } catch (Exception $e) {
        addTestResult('❌ PayPal config error: ' . $e->getMessage(), 'fail');
    }
}

function testDatabaseSchema() {
    global $testResults, $overallStatus;
    
    echo "<h2>🗄️ Database Schema Tests</h2>";
    
    try {
        require_once '../includes/database.php';
        $db = Database::getInstance();
        addTestResult('✅ Database connection successful', 'pass');
        
        // Test required tables
        $requiredTables = [
            'users', 'products', 'categories', 'brands', 'orders', 
            'order_items', 'cart', 'paypal_transactions', 'accessories'
        ];
        
        foreach ($requiredTables as $table) {
            try {
                $result = $db->fetchOne("SHOW TABLES LIKE '$table'");
                if ($result) {
                    addTestResult("✅ Table '$table' exists", 'pass');
                } else {
                    addTestResult("❌ Table '$table' missing", 'fail');
                }
            } catch (Exception $e) {
                addTestResult("❌ Error checking table '$table': " . $e->getMessage(), 'fail');
            }
        }
        
        // Test PayPal-specific columns in orders table
        try {
            $columns = $db->fetchAll("SHOW COLUMNS FROM orders LIKE 'payment_%'");
            if (count($columns) >= 3) {
                addTestResult('✅ PayPal columns in orders table', 'pass');
            } else {
                addTestResult('⚠️ Missing PayPal columns in orders table', 'warning');
            }
        } catch (Exception $e) {
            addTestResult('❌ Error checking orders table: ' . $e->getMessage(), 'fail');
        }
        
    } catch (Exception $e) {
        addTestResult('❌ Database connection failed: ' . $e->getMessage(), 'fail');
    }
}

function testPayPalService() {
    global $testResults, $overallStatus;
    
    echo "<h2>💳 PayPal Service Tests</h2>";
    
    try {
        require_once '../includes/PayPalService.php';
        addTestResult('✅ PayPalService class loaded', 'pass');
        
        // Test service instantiation
        $paypalService = new PayPalService();
        addTestResult('✅ PayPalService instantiated', 'pass');
        
        // Test methods exist
        $methods = ['createOrder', 'captureOrder', 'getOrder'];
        foreach ($methods as $method) {
            if (method_exists($paypalService, $method)) {
                addTestResult("✅ Method '$method' exists", 'pass');
            } else {
                addTestResult("❌ Method '$method' missing", 'fail');
            }
        }
        
    } catch (Exception $e) {
        addTestResult('❌ PayPalService error: ' . $e->getMessage(), 'fail');
    }
}

function testAPIEndpoints() {
    global $testResults, $overallStatus;
    
    echo "<h2>🔗 API Endpoints Tests</h2>";
    
    // Test API files exist
    $apiFiles = [
        'paypal_create_order.php' => 'PayPal order creation endpoint',
        'paypal_capture_order.php' => 'PayPal payment capture endpoint'
    ];
    
    foreach ($apiFiles as $file => $description) {
        if (file_exists("../api/$file")) {
            addTestResult("✅ $description exists", 'pass');
        } else {
            addTestResult("❌ $description missing", 'fail');
        }
    }
    
    // Test webhook handler
    if (file_exists('../paypal_webhook.php')) {
        addTestResult('✅ PayPal webhook handler exists', 'pass');
    } else {
        addTestResult('❌ PayPal webhook handler missing', 'fail');
    }
}

function testHelperFunctions() {
    global $testResults, $overallStatus;
    
    echo "<h2>🛠️ Helper Functions Tests</h2>";
    
    try {
        require_once '../includes/functions.php';
        
        // Test essential functions exist
        $functions = [
            'addToCart', 'getCartItems', 'getCartTotal', 'getCartItemCount',
            'formatPrice', 'generateOrderNumber', 'getPayPalSDKUrl'
        ];
        
        foreach ($functions as $function) {
            if (function_exists($function)) {
                addTestResult("✅ Function '$function' exists", 'pass');
            } else {
                addTestResult("❌ Function '$function' missing", 'fail');
            }
        }
        
        // Test formatPrice function
        $testPrice = formatPrice(1234.56);
        if ($testPrice === '$1,234.56') {
            addTestResult('✅ formatPrice function works correctly', 'pass');
        } else {
            addTestResult("⚠️ formatPrice unexpected result: $testPrice", 'warning');
        }
        
        // Test generateOrderNumber function
        $orderNumber = generateOrderNumber();
        if (preg_match('/^ORD-\d{4}-[A-Z0-9]{8}$/', $orderNumber)) {
            addTestResult('✅ generateOrderNumber format correct', 'pass');
        } else {
            addTestResult("⚠️ generateOrderNumber unexpected format: $orderNumber", 'warning');
        }
        
    } catch (Exception $e) {
        addTestResult('❌ Helper functions error: ' . $e->getMessage(), 'fail');
    }
}

function addTestResult($message, $status) {
    global $testResults, $overallStatus;
    
    $testResults[] = ['message' => $message, 'status' => $status];
    
    $class = 'test-info';
    switch ($status) {
        case 'pass':
            $class = 'test-pass';
            break;
        case 'fail':
            $class = 'test-fail';
            $overallStatus = false;
            break;
        case 'warning':
            $class = 'test-warning';
            break;
    }
    
    echo "<div class='test-result $class'>$message</div>";
}

function displayTestSummary() {
    global $testResults, $overallStatus;
    
    echo "<h2>📊 Test Summary</h2>";
    
    $passed = count(array_filter($testResults, fn($r) => $r['status'] === 'pass'));
    $failed = count(array_filter($testResults, fn($r) => $r['status'] === 'fail'));
    $warnings = count(array_filter($testResults, fn($r) => $r['status'] === 'warning'));
    $total = count($testResults);
    
    $summaryClass = $overallStatus ? 'test-pass' : 'test-fail';
    $statusText = $overallStatus ? '✅ TESTS PASSED' : '❌ TESTS FAILED';
    
    echo "<div class='test-result $summaryClass'>";
    echo "<h3>$statusText</h3>";
    echo "<p><strong>Total Tests:</strong> $total</p>";
    echo "<p><strong>Passed:</strong> $passed</p>";
    echo "<p><strong>Failed:</strong> $failed</p>";
    echo "<p><strong>Warnings:</strong> $warnings</p>";
    echo "</div>";
    
    if ($overallStatus) {
        echo "<div class='summary'>";
        echo "<h3>🎉 All Tests Passed!</h3>";
        echo "<p>Your PayPal integration is ready for testing. Next steps:</p>";
        echo "<ol>";
        echo "<li>Test the complete checkout flow</li>";
        echo "<li>Verify PayPal sandbox payments work</li>";
        echo "<li>Check order creation and email notifications</li>";
        echo "<li>Test admin panel order management</li>";
        echo "</ol>";
        echo "<p><a href='../checkout.php' style='background: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px;'>Test Checkout Process</a></p>";
        echo "</div>";
    } else {
        echo "<div class='summary'>";
        echo "<h3>⚠️ Issues Found</h3>";
        echo "<p>Please fix the failed tests before proceeding:</p>";
        echo "<ol>";
        echo "<li>Check database connection and schema</li>";
        echo "<li>Verify PayPal configuration</li>";
        echo "<li>Ensure all required files are present</li>";
        echo "</ol>";
        echo "</div>";
    }
}
?>