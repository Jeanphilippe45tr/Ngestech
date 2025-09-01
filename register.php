<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

if ($_POST) {
    $firstName = sanitizeInput($_POST['first_name'] ?? '');
    $lastName = sanitizeInput($_POST['last_name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    $errors = [];
    
    if (empty($firstName)) $errors[] = 'First name is required';
    if (empty($lastName)) $errors[] = 'Last name is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
    if (empty($password)) $errors[] = 'Password is required';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters';
    if ($password !== $confirmPassword) $errors[] = 'Passwords do not match';
    
    if (!$errors) {
        $db = Database::getInstance();
        
        // Check if email already exists
        $existing = $db->fetchOne("SELECT id FROM users WHERE email = ?", [$email]);
        if ($existing) {
            $errors[] = 'An account with this email already exists';
        }
    }
    
    if (!$errors) {
        try {
            $hashedPassword = hashPassword($password);
            
            $userId = $db->insert('users', [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'username' => $email, // Use email as username
                'password' => $hashedPassword,
                'role' => 'customer'
            ]);
            
            // Auto-login the user
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_role'] = 'customer';
            $_SESSION['user_name'] = $firstName . ' ' . $lastName;
            
            // Transfer guest cart to user cart
            $sessionId = session_id();
            $db->query(
                "UPDATE cart SET user_id = ?, session_id = NULL WHERE session_id = ?",
                [$userId, $sessionId]
            );
            
            showMessage('Account created successfully! Welcome!', 'success');
            redirect('index.php');
        } catch (Exception $e) {
            $errors[] = 'Failed to create account. Please try again.';
        }
    }
    
    if ($errors) {
        showMessage(implode('<br>', $errors), 'error');
    }
}

$pageTitle = 'Register';
includeHeader($pageTitle);
?>

    <main class="container">
        <div style="max-width: 400px; margin: 40px auto; background: white; padding: 32px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <?php displayMessage(); ?>
            
            <h2 style="text-align: center; margin-bottom: 24px;">Create Account</h2>
            
            <form method="POST" action="register.php">
                <div class="grid grid-2">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="input" required 
                               value="<?php echo isset($_POST['first_name']) ? sanitizeInput($_POST['first_name']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="input" required 
                               value="<?php echo isset($_POST['last_name']) ? sanitizeInput($_POST['last_name']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="input" required 
                           value="<?php echo isset($_POST['email']) ? sanitizeInput($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="input" required minlength="6">
                    <small style="color: #64748b;">Minimum 6 characters</small>
                </div>
                
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="input" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 16px;">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                <p>Already have an account? <a href="login.php" style="color: #0ea5e9;">Sign in here</a></p>
            </div>
        </div>
    </main>

<?php includeFooter(); ?>