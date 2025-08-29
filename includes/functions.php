<?php
require_once 'database.php';

// Security functions
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time'])) {
        return false;
    }
    
    if (time() - $_SESSION['csrf_token_time'] > CSRF_TOKEN_EXPIRE) {
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// User session functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: index.php');
        exit;
    }
}

function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $db = Database::getInstance();
    return $db->fetchOne(
        "SELECT * FROM users WHERE id = ?",
        [$_SESSION['user_id']]
    );
}

// Product functions
function getFeaturedProducts($limit = 6) {
    $db = Database::getInstance();
    return $db->fetchAll(
        "SELECT p.*, b.name as brand_name, c.name as category_name 
         FROM products p 
         JOIN brands b ON p.brand_id = b.id 
         JOIN categories c ON p.category_id = c.id 
         WHERE p.featured = 1 AND p.status = 'active' 
         ORDER BY p.created_at DESC 
         LIMIT ?",
        [$limit]
    );
}

function getProductById($id) {
    $db = Database::getInstance();
    return $db->fetchOne(
        "SELECT p.*, b.name as brand_name, c.name as category_name 
         FROM products p 
         JOIN brands b ON p.brand_id = b.id 
         JOIN categories c ON p.category_id = c.id 
         WHERE p.id = ? AND p.status = 'active'",
        [$id]
    );
}

function getProductImages($productId) {
    $db = Database::getInstance();
    return $db->fetchAll(
        "SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order",
        [$productId]
    );
}

function searchProducts($query, $category = null, $brand = null, $minPrice = null, $maxPrice = null, $limit = 12, $offset = 0) {
    $db = Database::getInstance();
    $params = [];
    $whereConditions = ["p.status = 'active'"];
    
    if ($query) {
        $whereConditions[] = "(p.name LIKE ? OR p.description LIKE ? OR p.model LIKE ?)";
        $searchTerm = "%{$query}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    if ($category) {
        $whereConditions[] = "p.category_id = ?";
        $params[] = $category;
    }
    
    if ($brand) {
        $whereConditions[] = "p.brand_id = ?";
        $params[] = $brand;
    }
    
    if ($minPrice !== null) {
        $whereConditions[] = "p.price >= ?";
        $params[] = $minPrice;
    }
    
    if ($maxPrice !== null) {
        $whereConditions[] = "p.price <= ?";
        $params[] = $maxPrice;
    }
    
    $whereClause = implode(' AND ', $whereConditions);
    $params[] = $limit;
    $params[] = $offset;
    
    return $db->fetchAll(
        "SELECT p.*, b.name as brand_name, c.name as category_name 
         FROM products p 
         JOIN brands b ON p.brand_id = b.id 
         JOIN categories c ON p.category_id = c.id 
         WHERE {$whereClause}
         ORDER BY p.name 
         LIMIT ? OFFSET ?",
        $params
    );
}

// Category and Brand functions
function getAllCategories() {
    $db = Database::getInstance();
    return $db->fetchAll("SELECT * FROM categories ORDER BY name");
}

function getAllBrands() {
    $db = Database::getInstance();
    return $db->fetchAll("SELECT * FROM brands ORDER BY name");
}

// Cart functions
function addToCart($productId, $quantity = 1, $userId = null) {
    $db = Database::getInstance();
    $sessionId = session_id();
    
    // Check if item already in cart
    if ($userId) {
        $existing = $db->fetchOne(
            "SELECT * FROM cart WHERE user_id = ? AND product_id = ?",
            [$userId, $productId]
        );
    } else {
        $existing = $db->fetchOne(
            "SELECT * FROM cart WHERE session_id = ? AND product_id = ?",
            [$sessionId, $productId]
        );
    }
    
    if ($existing) {
        // Update quantity
        $newQuantity = $existing['quantity'] + $quantity;
        if ($userId) {
            $db->update('cart', 
                ['quantity' => $newQuantity], 
                'user_id = ? AND product_id = ?', 
                [$userId, $productId]
            );
        } else {
            $db->update('cart', 
                ['quantity' => $newQuantity], 
                'session_id = ? AND product_id = ?', 
                [$sessionId, $productId]
            );
        }
    } else {
        // Add new item
        $cartData = [
            'product_id' => $productId,
            'quantity' => $quantity
        ];
        
        if ($userId) {
            $cartData['user_id'] = $userId;
        } else {
            $cartData['session_id'] = $sessionId;
        }
        
        $db->insert('cart', $cartData);
    }
    
    return true;
}

function getCartItems($userId = null) {
    $db = Database::getInstance();
    $sessionId = session_id();
    
    if ($userId) {
        return $db->fetchAll(
            "SELECT c.*, p.name, p.price, p.main_image, b.name as brand_name
             FROM cart c
             JOIN products p ON c.product_id = p.id
             JOIN brands b ON p.brand_id = b.id
             WHERE c.user_id = ?
             ORDER BY c.added_at DESC",
            [$userId]
        );
    } else {
        return $db->fetchAll(
            "SELECT c.*, p.name, p.price, p.main_image, b.name as brand_name
             FROM cart c
             JOIN products p ON c.product_id = p.id
             JOIN brands b ON p.brand_id = b.id
             WHERE c.session_id = ?
             ORDER BY c.added_at DESC",
            [$sessionId]
        );
    }
}

function getCartTotal($userId = null) {
    $items = getCartItems($userId);
    $total = 0;
    
    foreach ($items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    
    return $total;
}

function getCartItemCount($userId = null) {
    $db = Database::getInstance();
    $sessionId = session_id();
    
    if ($userId) {
        return $db->fetchColumn(
            "SELECT SUM(quantity) FROM cart WHERE user_id = ?",
            [$userId]
        ) ?: 0;
    } else {
        return $db->fetchColumn(
            "SELECT SUM(quantity) FROM cart WHERE session_id = ?",
            [$sessionId]
        ) ?: 0;
    }
}

// Utility functions
function formatPrice($price) {
    return CURRENCY_SYMBOL . number_format($price, 2);
}

function formatWeight($weight) {
    return $weight . ' lbs';
}

function getProductImageUrl($imagePath) {
    if ($imagePath && file_exists($imagePath)) {
        return SITE_URL . '/' . $imagePath;
    }
    return SITE_URL . '/images/no-image.jpg';
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function showMessage($message, $type = 'info') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

function displayMessage() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $type = $_SESSION['message_type'] ?? 'info';
        
        echo "<div class='alert alert-{$type}'>{$message}</div>";
        
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
}

function generateOrderNumber() {
    return 'ORD-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(4)));
}

// Email function (basic implementation)
function sendEmail($to, $subject, $message, $headers = null) {
    if (!$headers) {
        $headers = "From: " . SITE_EMAIL . "\r\n";
        $headers .= "Reply-To: " . SITE_EMAIL . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    }
    
    return mail($to, $subject, $message, $headers);
}

// File upload function
function uploadImage($file, $folder = 'products') {
    $uploadDir = UPLOAD_PATH . $folder . '/';
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $allowedTypes = ALLOWED_IMAGE_TYPES;
    $maxSize = MAX_FILE_SIZE;
    
    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileTmp = $file['tmp_name'];
    $fileError = $file['error'];
    
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    if ($fileError !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error');
    }
    
    if (!in_array($fileExt, $allowedTypes)) {
        throw new Exception('Invalid file type');
    }
    
    if ($fileSize > $maxSize) {
        throw new Exception('File too large');
    }
    
    $newFileName = uniqid() . '.' . $fileExt;
    $destination = $uploadDir . $newFileName;
    
    if (move_uploaded_file($fileTmp, $destination)) {
        return $folder . '/' . $newFileName;
    }
    
    throw new Exception('Failed to upload file');
}
?>
