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
    $paypalOrderId = $input['orderID'] ?? '';
    
    if (empty($paypalOrderId)) {
        throw new Exception('PayPal order ID required');
    }
    
    // Verify this order belongs to current session
    if (!isset($_SESSION['paypal_order_id']) || $_SESSION['paypal_order_id'] !== $paypalOrderId) {
        throw new Exception('Invalid order session');
    }
    
    $userId = $_SESSION['user_id'];
    $cartItems = getCartItems($userId);
    
    if (empty($cartItems)) {
        throw new Exception('Cart is empty');
    }
    
    // Capture PayPal payment
    $paypalService = new PayPalService();
    $captureResult = $paypalService->captureOrder($paypalOrderId);
    
    if (!$captureResult['success']) {
        throw new Exception($captureResult['error'] ?? 'Failed to capture payment');
    }
    
    // Start database transaction
    $db = Database::getInstance();
    $db->beginTransaction();
    
    try {
        // Calculate totals
        $cartTotal = getCartTotal($userId);
        $shipping = SHIPPING_RATE;
        $tax = round($cartTotal * TAX_RATE, 2);
        $grandTotal = $cartTotal + $shipping + $tax;
        
        // Get user info for addresses
        $user = getCurrentUser();
        $billingAddress = implode(', ', [
            $user['first_name'] . ' ' . $user['last_name'],
            $user['address'] ?? 'Address not provided',
            ($user['city'] ?? '') . ', ' . ($user['state'] ?? '') . ' ' . ($user['zip_code'] ?? ''),
            $user['country'] ?? ''
        ]);
        
        // Create order
        $orderNumber = $_SESSION['pending_order_number'] ?? generateOrderNumber();
        $orderId = $db->insert('orders', [
            'user_id' => $userId,
            'order_number' => $orderNumber,
            'total_amount' => $grandTotal,
            'shipping_cost' => $shipping,
            'tax_amount' => $tax,
            'payment_method' => 'paypal',
            'payment_status' => 'paid',
            'payment_transaction_id' => $captureResult['capture_id'],
            'payment_details' => json_encode($captureResult['full_response']),
            'status' => 'processing',
            'shipping_address' => $billingAddress,
            'billing_address' => $billingAddress
        ]);
        
        // Add order items and update stock
        foreach ($cartItems as $item) {
            $db->insert('order_items', [
                'order_id' => $orderId,
                'item_id' => $item['item_id'],
                'item_type' => $item['item_type'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['price'] * $item['quantity']
            ]);
            
            // Update stock for products only
            if ($item['item_type'] === 'product') {
                $db->query(
                    "UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?",
                    [$item['quantity'], $item['item_id']]
                );
            }
        }
        
        // Create PayPal transaction record
        $db->insert('paypal_transactions', [
            'order_id' => $orderId,
            'paypal_order_id' => $paypalOrderId,
            'paypal_capture_id' => $captureResult['capture_id'],
            'status' => 'captured',
            'amount' => $captureResult['amount'],
            'currency' => $captureResult['currency'],
            'payer_email' => $captureResult['payer_email'],
            'payer_name' => $captureResult['payer_name'],
            'transaction_fee' => $captureResult['transaction_fee'],
            'paypal_response' => json_encode($captureResult['full_response'])
        ]);
        
        // Clear cart
        $db->delete('cart', 'user_id = ?', [$userId]);
        
        // Commit transaction
        $db->commit();
        
        // Clear session data
        unset($_SESSION['paypal_order_id']);
        unset($_SESSION['pending_order_number']);
        unset($_SESSION['pending_order_amount']);
        
        // Send confirmation email
        $emailSubject = "Payment Confirmed - Order $orderNumber";
        $emailMessage = "
            <h2>Payment Confirmed!</h2>
            <p>Your PayPal payment has been successfully processed.</p>
            <p><strong>Order Number:</strong> $orderNumber</p>
            <p><strong>Amount Paid:</strong> " . formatPrice($captureResult['amount']) . "</p>
            <p><strong>Transaction ID:</strong> {$captureResult['capture_id']}</p>
            <p>Your order is now being processed and you will receive a shipping notification soon.</p>
            <p>Thank you for your business!</p>
        ";
        
        sendEmail($user['email'], $emailSubject, $emailMessage);
        
        echo json_encode([
            'success' => true,
            'order_number' => $orderNumber,
            'transaction_id' => $captureResult['capture_id'],
            'amount' => $captureResult['amount'],
            'redirect_url' => "order_success.php?order=$orderNumber&method=paypal&status=paid"
        ]);
        
    } catch (Exception $e) {
        $db->rollback();
        throw $e;
    }
    
} catch (Exception $e) {
    error_log('PayPal capture error: ' . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>