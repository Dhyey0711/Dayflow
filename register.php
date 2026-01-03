<?php
// admin_register.php
session_start();

// Database connection without PDO
$host = 'localhost:8081';
$user = 'root';
$pass = '';
$dbname = 'hrms';

$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check if admin already exists
$check_admin = mysqli_query($conn, "SELECT * FROM users WHERE role = 'admin' LIMIT 1");
$admin_exists = mysqli_num_rows($check_admin) > 0;

// If admin exists, redirect to login
if ($admin_exists) {
    header('Location: index.php?error=admin_exists');
    exit();
}

// Handle form submission
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $secret_key = $_POST['secret_key'];
    
    // Secret key for one-time registration
    $valid_secret_key = 'ADMIN_SETUP_2024_DAYFLOW'; // Change this in production
    
    // Validation
    if (empty($first_name)) $errors[] = "First name is required";
    if (empty($last_name)) $errors[] = "Last name is required";
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    if (empty($secret_key)) {
        $errors[] = "Secret key is required";
    } elseif ($secret_key !== $valid_secret_key) {
        $errors[] = "Invalid secret key";
    }
    
    // Check if email already exists
    $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $errors[] = "Email already exists";
    }
    
    // If no errors, create admin
    if (empty($errors)) {
        // Generate unique employee ID
        $employee_id = 'ADMIN' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Generate verification token
        $verification_token = bin2hex(random_bytes(32));
        
        // Start transaction
        mysqli_autocommit($conn, false);
        
        try {
            // Insert into users table
            $insert_user = "INSERT INTO users (employee_id, email, password, role, is_verified, verification_token) 
                           VALUES ('$employee_id', '$email', '$hashed_password', 'admin', 1, '$verification_token')";
            
            if (mysqli_query($conn, $insert_user)) {
                $user_id = mysqli_insert_id($conn);
                
                // Insert into employees table
                $insert_employee = "INSERT INTO employees (user_id, first_name, last_name, email, phone, department, position, 
                                  job_type, joining_date, employment_status, basic_salary, total_salary) 
                                  VALUES ('$user_id', '$first_name', '$last_name', '$email', 'Not Set', 
                                          'Administration', 'HR Administrator', 'full-time', CURDATE(), 'active', 
                                          0, 0)";
                
                if (mysqli_query($conn, $insert_employee)) {
                    mysqli_commit($conn);
                    $success = true;
                    
                    // Create success log
                    $log_message = date('Y-m-d H:i:s') . " - ADMIN REGISTERED: $first_name $last_name ($email) - IP: " . $_SERVER['REMOTE_ADDR'];
                    file_put_contents('admin_registration.log', $log_message . PHP_EOL, FILE_APPEND);
                    
                    // Send welcome email (optional)
                    // mail($email, "Admin Account Created", "Your admin account has been created successfully.");
                    
                } else {
                    mysqli_rollback($conn);
                    $errors[] = "Failed to create employee record: " . mysqli_error($conn);
                }
            } else {
                $errors[] = "Failed to create user account: " . mysqli_error($conn);
            }
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $errors[] = "Registration failed: " . $e->getMessage();
        }
        
        mysqli_autocommit($conn, true);
    }
}

// Close connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration - Dayflow HRMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --border: #e2e8f0;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .registration-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 800px;
            display: flex;
            min-height: 600px;
        }

        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
            z-index: 1;
            position: relative;
        }

        .logo-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            display: inline-block;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .logo-text {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 0.5rem;
        }

        .tagline {
            font-size: 1rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .features {
            margin-top: 2rem;
            z-index: 1;
            position: relative;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .feature-item i {
            color: var(--secondary);
        }

        .security-notice {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 10px;
            margin-top: 2rem;
            font-size: 0.85rem;
            z-index: 1;
            position: relative;
            border-left: 4px solid var(--warning);
        }

        .security-notice i {
            color: var(--warning);
            margin-right: 0.5rem;
        }

        .right-panel {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-header h2 {
            color: var(--dark);
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: var(--gray);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark);
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        .input-with-icon input {
            padding-left: 3rem;
        }

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.8rem;
        }

        .strength-bar {
            height: 4px;
            background: var(--border);
            border-radius: 2px;
            margin-top: 0.3rem;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s;
        }

        .strength-weak { background: var(--danger); }
        .strength-medium { background: var(--warning); }
        .strength-strong { background: var(--secondary); }

        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid var(--secondary);
        }

        .alert i {
            font-size: 1.2rem;
        }

        .btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .login-link {
            text-align: center;
            margin-top: 2rem;
            color: var(--gray);
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .registration-container {
                flex-direction: column;
                max-width: 500px;
            }
            
            .left-panel {
                padding: 2rem;
            }
            
            .right-panel {
                padding: 2rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }
            
            .left-panel,
            .right-panel {
                padding: 1.5rem;
            }
        }

        .secret-key-info {
            font-size: 0.8rem;
            color: var(--gray);
            margin-top: 0.5rem;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <!-- Left Panel -->
        <div class="left-panel">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="logo-text">DAYFLOW</div>
                <div class="tagline">Human Resource Management System</div>
            </div>
            
            <div class="features">
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>One-time admin registration only</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-user-lock"></i>
                    <span>Full system access and control</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-cogs"></i>
                    <span>Complete HR management tools</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Advanced analytics and reporting</span>
                </div>
            </div>
            
            <div class="security-notice">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Important:</strong> This is a one-time setup. After registration, this page will be inaccessible. Keep your credentials secure.
            </div>
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <h4>Admin Account Created Successfully!</h4>
                        <p>Your admin account has been created. You can now login to the system.</p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                        <p><em>Please note these credentials securely.</em></p>
                        <div style="margin-top: 1rem;">
                            <a href="index.php" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Go to Login
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <h4>Registration Failed</h4>
                            <ul style="margin-left: 1rem;">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-header">
                    <h2>Admin Registration</h2>
                    <p>One-time setup for system administrator</p>
                </div>

                <form method="POST" action="" id="registrationForm">
                    <div class="form-row" style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
                        <div class="form-group" style="flex: 1;">
                            <label for="first_name">First Name *</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" id="first_name" name="first_name" class="form-control" 
                                       value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>"
                                       required>
                            </div>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label for="last_name">Last Name *</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" id="last_name" name="last_name" class="form-control"
                                       value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>"
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <div class="input-with-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="email" name="email" class="form-control"
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                   required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password *</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" class="form-control" required
                                   oninput="checkPasswordStrength(this.value)">
                        </div>
                        <div class="password-strength">
                            <div id="strengthText">Password strength</div>
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password *</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        </div>
                        <div id="passwordMatch" style="font-size: 0.8rem; margin-top: 0.5rem;"></div>
                    </div>

                    <div class="form-group">
                        <label for="secret_key">Secret Key *</label>
                        <div class="input-with-icon">
                            <i class="fas fa-key"></i>
                            <input type="text" id="secret_key" name="secret_key" class="form-control" 
                                   value="<?php echo isset($_POST['secret_key']) ? htmlspecialchars($_POST['secret_key']) : ''; ?>"
                                   required>
                        </div>
                        <div class="secret-key-info">
                            Required for one-time admin registration. Contact system administrator if you don't have this key.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Create Admin Account
                    </button>

                    <div class="login-link">
                        Already have an account? <a href="index.php">Login here</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function checkPasswordStrength(password) {
            const strengthText = document.getElementById('strengthText');
            const strengthFill = document.getElementById('strengthFill');
            
            // Reset
            let strength = 0;
            let width = 0;
            let text = 'Password strength';
            let colorClass = '';
            
            // Length check
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            
            // Complexity checks
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            // Calculate width and set text
            width = (strength / 5) * 100;
            
            if (password.length === 0) {
                text = 'Password strength';
                colorClass = '';
            } else if (strength <= 2) {
                text = 'Weak';
                colorClass = 'strength-weak';
            } else if (strength <= 3) {
                text = 'Medium';
                colorClass = 'strength-medium';
            } else {
                text = 'Strong';
                colorClass = 'strength-strong';
            }
            
            // Update UI
            strengthText.textContent = text;
            strengthFill.style.width = width + '%';
            strengthFill.className = 'strength-fill ' + colorClass;
        }
        
        // Check password match
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const matchText = document.getElementById('passwordMatch');
        
        function checkPasswordMatch() {
            if (confirmPassword.value === '') {
                matchText.textContent = '';
                matchText.style.color = '';
            } else if (password.value === confirmPassword.value) {
                matchText.textContent = '✓ Passwords match';
                matchText.style.color = '#10b981';
            } else {
                matchText.textContent = '✗ Passwords do not match';
                matchText.style.color = '#ef4444';
            }
        }
        
        password.addEventListener('input', checkPasswordMatch);
        confirmPassword.addEventListener('input', checkPasswordMatch);
        
        // Form submission validation
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
                return false;
            }
            
            // Confirm registration
            if (!confirm('Are you sure you want to create the admin account? This is a one-time action.')) {
                e.preventDefault();
                return false;
            }
            
            return true;
        });
        
        // Auto-check on page load
        if (password.value) {
            checkPasswordStrength(password.value);
            checkPasswordMatch();
        }
    </script>
</body>
</html>