<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

$redirect = isset($_GET['redirect']) ? sanitizeInput($_GET['redirect']) : 'index.php';

if ($_POST) {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        showMessage('Please fill in all fields', 'error');
    } else {
        $db = Database::getInstance();
        $user = $db->fetchOne(
            "SELECT * FROM users WHERE email = ?",
            [$email]
        );
        
        if ($user && verifyPassword($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            
            // Transfer guest cart to user cart
            $sessionId = session_id();
            $db->query(
                "UPDATE cart SET user_id = ?, session_id = NULL WHERE session_id = ?",
                [$user['id'], $sessionId]
            );
            
            showMessage('Login successful', 'success');
            redirect($redirect);
        } else {
            showMessage('Invalid email or password', 'error');
        }
    }
}

$pageTitle = 'Login';
?>

    <main class="container">
        <div style="max-width: 400px; margin: 40px auto; background: white; padding: 32px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <?php displayMessage(); ?>
            
            <h2 style="text-align: center; margin-bottom: 24px;">Login</h2>
            
            <form method="POST" action="login.php<?php echo $redirect !== 'index.php' ? '?redirect=' . urlencode($redirect) : ''; ?>">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="input" required value="<?php echo isset($_POST['email']) ? sanitizeInput($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="input" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 16px;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                <p>Don't have an account? <a href="register.php" style="color: #0ea5e9;">Sign up here</a></p>
            </div>
            <!--
            <div style="text-align: center; margin-top: 16px;">
                <p><strong>Login Accounts:</strong></p>
                <p style="font-size: 14px; color: #64748b;">
                    <strong>Admin:</strong> gonzila@gmail.com / gonzilaib<br>
                    <strong>Customer:</strong> demo@example.com / demo123
                </p>
                <p style="font-size: 12px; color: #64748b; margin-top: 8px;">
                    If admin login doesn't work, run <a href="setup_admin.php" style="color: #0ea5e9;">setup_admin.php</a> first
                </p>
            </div>
            -->
        </div>
    </main>

    <?php includeFooter(); ?>