<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success' => false, 'message' => 'Invalid request']);
  exit;
}

if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'message' => 'Invalid request token.']);
    exit;
}

$itemId = isset($_POST['item_id']) ? (int)$_POST['item_id'] : 0;
$itemType = isset($_POST['item_type']) ? $_POST['item_type'] : '';
$quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

try {
  if ($itemId <= 0 || !in_array($itemType, ['product', 'accessory'])) {
      throw new Exception('Invalid item data');
  }

  // You might want to add a check here to ensure the product/accessory exists and is active

  addToCart($itemId, $itemType, $quantity, isLoggedIn() ? $_SESSION['user_id'] : null);

  $count = (int)getCartItemCount(isLoggedIn() ? $_SESSION['user_id'] : null);
  $total = (float)getCartTotal(isLoggedIn() ? $_SESSION['user_id'] : null);

  echo json_encode([
    'success' => true,
    'cart_count' => $count,
    'cart_total' => $total,
    'cart_total_formatted' => formatPrice($total)
  ]);
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

