<?php
/**
 * PayPal Configuration
 * 
 * INSTRUCTIONS:
 * 1. Replace YOUR_PAYPAL_CLIENT_ID with your actual PayPal Client ID
 * 2. Replace YOUR_PAYPAL_CLIENT_SECRET with your actual PayPal Client Secret
 * 3. Set PAYPAL_ENVIRONMENT to 'production' when ready to go live
 */

// PayPal Environment - 'sandbox' for testing, 'production' for live
define('PAYPAL_ENVIRONMENT', 'sandbox');

// PayPal API Credentials
if (PAYPAL_ENVIRONMENT === 'sandbox') {
    // Sandbox credentials
    define('PAYPAL_CLIENT_ID', 'YOUR_PAYPAL_CLIENT_ID');
    define('PAYPAL_CLIENT_SECRET', 'YOUR_PAYPAL_CLIENT_SECRET');
    define('PAYPAL_BASE_URL', 'https://api.sandbox.paypal.com');
    define('PAYPAL_JS_SDK_URL', 'https://www.paypal.com/sdk/js');
} else {
    // Production credentials
    define('PAYPAL_CLIENT_ID', 'YOUR_PAYPAL_CLIENT_ID');
    define('PAYPAL_CLIENT_SECRET', 'YOUR_PAYPAL_CLIENT_SECRET');
    define('PAYPAL_BASE_URL', 'https://api.paypal.com');
    define('PAYPAL_JS_SDK_URL', 'https://www.paypal.com/sdk/js');
}

// PayPal Currency
define('PAYPAL_CURRENCY', 'USD');

// Return URLs
define('PAYPAL_SUCCESS_URL', SITE_URL . '/paypal_success.php');
define('PAYPAL_CANCEL_URL', SITE_URL . '/paypal_cancel.php');

/**
 * Get PayPal Access Token
 */
function getPayPalAccessToken() {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, PAYPAL_BASE_URL . '/v1/oauth2/token');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, PAYPAL_CLIENT_ID . ':' . PAYPAL_CLIENT_SECRET);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        $data = json_decode($response, true);
        return isset($data['access_token']) ? $data['access_token'] : null;
    }
    
    return null;
}

/**
 * Create PayPal Payment
 */
function createPayPalPayment($amount, $currency = 'USD', $description = 'Outboard Motor Purchase') {
    $accessToken = getPayPalAccessToken();
    
    if (!$accessToken) {
        return ['success' => false, 'error' => 'Failed to get PayPal access token'];
    }
    
    $paymentData = [
        'intent' => 'sale',
        'payer' => [
            'payment_method' => 'paypal'
        ],
        'transactions' => [
            [
                'amount' => [
                    'total' => number_format($amount, 2, '.', ''),
                    'currency' => $currency
                ],
                'description' => $description
            ]
        ],
        'redirect_urls' => [
            'return_url' => PAYPAL_SUCCESS_URL,
            'cancel_url' => PAYPAL_CANCEL_URL
        ]
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, PAYPAL_BASE_URL . '/v1/payments/payment');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 201 && $response) {
        $payment = json_decode($response, true);
        
        // Find approval URL
        foreach ($payment['links'] as $link) {
            if ($link['rel'] === 'approval_url') {
                return [
                    'success' => true,
                    'payment_id' => $payment['id'],
                    'approval_url' => $link['href']
                ];
            }
        }
    }
    
    return ['success' => false, 'error' => 'Failed to create PayPal payment', 'response' => $response];
}

/**
 * Execute PayPal Payment
 */
function executePayPalPayment($paymentId, $payerId) {
    $accessToken = getPayPalAccessToken();
    
    if (!$accessToken) {
        return ['success' => false, 'error' => 'Failed to get PayPal access token'];
    }
    
    $executeData = [
        'payer_id' => $payerId
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, PAYPAL_BASE_URL . '/v1/payments/payment/' . $paymentId . '/execute');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($executeData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 && $response) {
        $payment = json_decode($response, true);
        
        if ($payment['state'] === 'approved') {
            return [
                'success' => true,
                'payment_id' => $payment['id'],
                'payer_email' => $payment['payer']['payer_info']['email'] ?? '',
                'transaction_id' => $payment['transactions'][0]['related_resources'][0]['sale']['id'] ?? '',
                'amount' => $payment['transactions'][0]['amount']['total'] ?? 0
            ];
        }
    }
    
    return ['success' => false, 'error' => 'Failed to execute PayPal payment', 'response' => $response];
}
?>
