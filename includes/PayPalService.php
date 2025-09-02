<?php
/**
 * Modern PayPal Service Class
 * Handles PayPal Orders API v2 integration
 */

class PayPalService {
    private $clientId;
    private $clientSecret;
    private $baseUrl;
    private $accessToken;
    
    public function __construct() {
        $this->clientId = PAYPAL_CLIENT_ID;
        $this->clientSecret = PAYPAL_CLIENT_SECRET;
        $this->baseUrl = PAYPAL_BASE_URL;
    }
    
    /**
     * Get PayPal access token
     */
    private function getAccessToken() {
        if ($this->accessToken) {
            return $this->accessToken;
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->clientId . ':' . $this->clientSecret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Accept-Language: en_US'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            $this->accessToken = $data['access_token'] ?? null;
            return $this->accessToken;
        }
        
        throw new Exception('Failed to get PayPal access token');
    }
    
    /**
     * Create PayPal order using Orders API v2
     */
    public function createOrder($amount, $currency = 'USD', $description = 'Order', $orderNumber = null) {
        try {
            $accessToken = $this->getAccessToken();
            
            $orderData = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $orderNumber ?: uniqid('order_'),
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => number_format($amount, 2, '.', '')
                        ],
                        'description' => $description
                    ]
                ],
                'application_context' => [
                    'return_url' => SITE_URL . '/paypal_success.php',
                    'cancel_url' => SITE_URL . '/paypal_cancel.php',
                    'brand_name' => SITE_NAME,
                    'landing_page' => 'BILLING',
                    'user_action' => 'PAY_NOW'
                ]
            ];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/v2/checkout/orders');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
                'PayPal-Request-Id: ' . uniqid()
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 201 && $response) {
                $order = json_decode($response, true);
                
                // Find approval URL
                $approvalUrl = null;
                foreach ($order['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $approvalUrl = $link['href'];
                        break;
                    }
                }
                
                return [
                    'success' => true,
                    'order_id' => $order['id'],
                    'approval_url' => $approvalUrl,
                    'status' => $order['status']
                ];
            }
            
            return [
                'success' => false,
                'error' => 'Failed to create PayPal order',
                'response' => $response,
                'http_code' => $httpCode
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Capture PayPal order payment
     */
    public function captureOrder($orderId) {
        try {
            $accessToken = $this->getAccessToken();
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/v2/checkout/orders/' . $orderId . '/capture');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
                'PayPal-Request-Id: ' . uniqid()
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 201 && $response) {
                $capture = json_decode($response, true);
                
                if ($capture['status'] === 'COMPLETED') {
                    $captureDetails = $capture['purchase_units'][0]['payments']['captures'][0];
                    
                    return [
                        'success' => true,
                        'capture_id' => $captureDetails['id'],
                        'status' => $captureDetails['status'],
                        'amount' => $captureDetails['amount']['value'],
                        'currency' => $captureDetails['amount']['currency_code'],
                        'payer_email' => $capture['payer']['email_address'] ?? '',
                        'payer_name' => ($capture['payer']['name']['given_name'] ?? '') . ' ' . ($capture['payer']['name']['surname'] ?? ''),
                        'transaction_fee' => $captureDetails['seller_receivable_breakdown']['paypal_fee']['value'] ?? 0,
                        'full_response' => $capture
                    ];
                }
            }
            
            return [
                'success' => false,
                'error' => 'Failed to capture PayPal payment',
                'response' => $response,
                'http_code' => $httpCode
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get order details
     */
    public function getOrder($orderId) {
        try {
            $accessToken = $this->getAccessToken();
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/v2/checkout/orders/' . $orderId);
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
                return [
                    'success' => true,
                    'order' => json_decode($response, true)
                ];
            }
            
            return [
                'success' => false,
                'error' => 'Failed to get PayPal order details'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}