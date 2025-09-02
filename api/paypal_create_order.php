<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/PayPalService.php';

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Authentication required']);
    exit;
}

try {
    // Get request data
    $input = json_decode(file_get_contents('php://input'), true);
    $amount = $input['amount'] ?? 0;
    $currency = $input['currency'] ?? 'USD';
    
    if ($amount <= 0) {
        throw new Exception('Invalid amount');
    }
    
    // Verify cart total matches requested amount
    $userId = $_SESSION['user_id'];
    $cartItems = getCartItems($userId);
    
    if (empty($cartItems)) {
        throw new Exception('Cart is empty');
    }
    
    $cartTotal = getCartTotal($userId);
    $shipping = SHIPPING_RATE;
    $tax = round($cartTotal * TAX_RATE, 2);
    $expectedTotal = $cartTotal + $shipping + $tax;
    
    if (abs($amount - $expectedTotal) > 0.01) {
        throw new Exception('Amount mismatch');
    }
    
    // Create PayPal order
    $paypalService = new PayPalService();
    $orderNumber = generateOrderNumber();
    $description = "Order $orderNumber - " . SITE_NAME;
    
    $result = $paypalService->createOrder($amount, $currency, $description, $orderNumber);
    
    if ($result['success']) {
        // Store order info in session for later use
        $_SESSION['paypal_order_id'] = $result['order_id'];
        $_SESSION['pending_order_number'] = $orderNumber;
        $_SESSION['pending_order_amount'] = $amount;
        
        echo json_encode([
            'success' => true,
            'order_id' => $result['order_id'],
            'approval_url' => $result['approval_url']
        ]);
    } else {
        throw new Exception($result['error'] ?? 'Failed to create PayPal order');
    }
    
} catch (Exception $e) {
    error_log('PayPal order creation error: ' . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>