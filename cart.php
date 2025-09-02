<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$userId = isLoggedIn() ? $_SESSION['user_id'] : null;
$cartItems = getCartItems($userId);
$cartTotal = getCartTotal($userId);
$cartCount = getCartItemCount($userId);

$pageTitle = 'Shopping Cart';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo generateCSRFToken(); ?>">
    <title><?php echo $pageTitle; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .quantity-control {
            display: flex;
            align-items: center;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
        }
        .quantity-control button {
            background: #f8fafc;
            border: none;
            cursor: pointer;
            padding: 8px 12px;
            font-size: 16px;
        }
        .quantity-control button:hover { background: #f1f5f9; }
        .quantity-control input {
            width: 50px;
            text-align: center;
            border: none;
            border-left: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
            padding: 8px;
        }
        .cart-item-total { font-weight: 600; }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="main-header">
                <div class="logo">
                    <a href="index.php">
                        <h1><i class="fas fa-anchor"></i> <?php echo SITE_NAME; ?></h1>
                    </a>
                </div>
                <div class="search-bar">
                    <form action="products.php" method="GET" class="search-form">
                        <input type="text" name="q" placeholder="Search...">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="cart-info">
                    <a href="cart.php" class="cart-link">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count"><?php echo $cartCount; ?></span>
                        <span class="cart-total"><?php echo formatPrice($cartTotal); ?></span>
                    </a>
                </div>
            </div>
            <nav class="navigation">
                <ul class="nav-menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
                <div class="mobile-menu-toggle"><i class="fas fa-bars"></i></div>
            </nav>
        </div>
    </header>

    <main class="container">
        <?php displayMessage(); ?>
        
        <h2 class="section-title">Shopping Cart</h2>
        
        <?php if (empty($cartItems)): ?>
            <div style="text-align: center; padding: 40px; background: white; border-radius: 12px; border: 1px solid #e2e8f0;">
                <i class="fas fa-shopping-cart" style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px;"></i>
                <h3>Your cart is empty</h3>
                <p>Add some outboard motors to your cart to get started.</p>
                <a href="products.php" class="btn btn-primary">Shop Now</a>
            </div>
        <?php else: ?>
            <div class="cart-table" style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
                <div class="cart-header" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 16px; padding: 16px; background: #f8fafc; font-weight: 600; border-bottom: 1px solid #e2e8f0;">
                    <div>Product</div>
                    <div>Price</div>
                    <div>Quantity</div>
                    <div>Total</div>
                    <div></div>
                </div>
                
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item" data-item-id="<?php echo $item['id']; ?>" data-item-price="<?php echo $item['price']; ?>" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 16px; padding: 16px; align-items: center; border-bottom: 1px solid #f1f5f9;">
                        <div style="display: flex; gap: 12px; align-items: center;">
                            <img src="<?php echo getProductImageUrl($item['main_image']); ?>" 
                                 alt="<?php echo sanitizeInput($item['name']); ?>" 
                                 style="width: 64px; height: 64px; object-fit: cover; border-radius: 8px;">
                            <div>
                                <h4 style="margin: 0; font-size: 16px;">
                                    <a href="product.php?id=<?php echo $item['product_id']; ?>">
                                        <?php echo sanitizeInput($item['name']); ?>
                                    </a>
                                </h4>
                                <p style="margin: 4px 0 0; color: #64748b; font-size: 14px;">
                                    <?php echo sanitizeInput($item['brand_name']); ?>
                                </p>
                            </div>
                        </div>
                        <div><?php echo formatPrice($item['price']); ?></div>
                        <td>
                            <div class="quantity-control">
                                <button class="quantity-btn" data-action="decrease" data-cart-id="<?php echo $item['id']; ?>">-</button>
                                <input type="text" class="quantity-input" value="<?php echo $item['quantity']; ?>" readonly>
                                <button class="quantity-btn" data-action="increase" data-cart-id="<?php echo $item['id']; ?>">+</button>
                            </div>
                        </td>
                        <div class="cart-item-total"><?php echo formatPrice($item['price'] * $item['quantity']); ?></div>
                        <div>
                            <button type="button" class="remove-item" data-cart-id="<?php echo $item['id']; ?>" 
                                    style="color: #ef4444; background: none; border: none; cursor: pointer; padding: 8px;" title="Remove item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div style="display: flex; justify-content: flex-end; align-items: center; margin: 16px 0;">
                <div style="text-align: right;">
                    <div style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">
                        Subtotal: <span id="cart-subtotal"><?php echo formatPrice($cartTotal); ?></span>
                    </div>
                    <div style="color: #64748b; font-size: 14px;">
                        + <?php echo formatPrice(SHIPPING_RATE); ?> shipping (calculated at checkout)
                    </div>
                </div>
            </div>
            
            <div style="text-align: center; margin: 24px 0;">
                <?php if (!isLoggedIn()): ?>
                    <p style="margin-bottom: 16px; color: #64748b;">Please log in to proceed to checkout</p>
                    <a href="login.php?redirect=cart.php" class="btn btn-primary">Login to Checkout</a>
                <?php else: ?>
                    <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
                <?php endif; ?>
                <a href="products.php" class="btn btn-outline" style="margin-left: 8px;">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
                <div class="footer-links">
                    <a href="privacy.php">Privacy Policy</a>
                    <a href="terms.php">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function handleCartUpdate(cartId, newQuantity) {
            const formData = new FormData();
            formData.append('cart_id', cartId);
            formData.append('quantity', newQuantity);
            formData.append('csrf_token', csrfToken);

            fetch('update_cart_item_quantity.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartInfo(data.cart_count, data.cart_total_formatted);
                    if (newQuantity === 0) {
                        const itemRow = document.querySelector(`.cart-item[data-item-id="${cartId}"]`);
                        if (itemRow) {
                            itemRow.remove();
                            if (document.querySelectorAll('.cart-item').length === 0) {
                                window.location.reload(); // Reload to show empty cart message
                            }
                        }
                    } else {
                        const itemRow = document.querySelector(`.cart-item[data-item-id="${cartId}"]`);
                        if(itemRow) {
                            itemRow.querySelector('.quantity-input').value = newQuantity;
                            const price = parseFloat(itemRow.getAttribute('data-item-price'));
                            itemRow.querySelector('.cart-item-total').textContent = formatPrice(price * newQuantity);
                        }
                    }
                    // Update subtotal
                    document.getElementById('cart-subtotal').textContent = data.cart_total_formatted;
                } else {
                    alert('Error: ' + data.message);
                    // Optionally revert visual changes
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An unexpected error occurred.');
                window.location.reload();
            });
        }

        function updateCartInfo(count, totalFormatted) {
            const cartCountEl = document.querySelector('.cart-count');
            const cartTotalEl = document.querySelector('.cart-total');
            if (cartCountEl) cartCountEl.textContent = count;
            if (cartTotalEl) cartTotalEl.textContent = totalFormatted;
        }

        function formatPrice(amount) {
            // This is a simple client-side formatter, assumes USD. 
            // For multi-currency, this should be more sophisticated.
            return '
 + parseFloat(amount).toFixed(2);
        }

        // Event listeners for quantity buttons
        document.querySelectorAll('.quantity-btn').forEach(button => {
            button.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                const cartId = this.getAttribute('data-cart-id');
                const input = this.parentElement.querySelector('.quantity-input');
                let currentQuantity = parseInt(input.value);

                if (action === 'increase') {
                    currentQuantity++;
                } else if (action === 'decrease') {
                    currentQuantity--;
                }
                
                if (currentQuantity >= 0) {
                    handleCartUpdate(cartId, currentQuantity);
                }
            });
        });

        // Event listeners for remove buttons
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Are you sure you want to remove this item?')) {
                    const cartId = this.getAttribute('data-cart-id');
                    handleCartUpdate(cartId, 0); // Set quantity to 0 to delete
                }
            });
        });
    });
    </script>
</body>
</html>

