<?php
require_once 'includes/db.php';

// Check if already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'hr') {
        header('Location: ' . $base_url . 'admin/dashboard.php');
    } else {
        header('Location: ' . $base_url . 'employee/dashboard.php');
    }
    exit();
}

$login_error = '';
$register_error = '';
$register_success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];
        
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            if (password_verify($password, $user['password'])) {
                if ($user['is_verified'] == 0) {
                    $login_error = "Account not verified. Please check your email.";
                } else {
                    // Set session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['employee_id'] = $user['employee_id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['logged_in'] = true;
                    
                    // Get employee details
                    $emp_sql = "SELECT * FROM employees WHERE user_id = {$user['id']}";
                    $emp_result = mysqli_query($conn, $emp_sql);
                    if ($emp_result && mysqli_num_rows($emp_result) == 1) {
                        $employee = mysqli_fetch_assoc($emp_result);
                        $_SESSION['employee_name'] = $employee['first_name'] . ' ' . $employee['last_name'];
                        $_SESSION['profile_pic'] = $employee['profile_pic'];
                        $_SESSION['employee_id_num'] = $employee['id'];
                    }
                    
                    // Redirect
                    if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'hr') {
                        header('Location: ' . $base_url . 'admin/dashboard.php');
                    } else {
                        header('Location: ' . $base_url . 'employee/dashboard.php');
                    }
                    exit();
                }
            } else {
                $login_error = "Invalid email or password.";
            }
        } else {
            $login_error = "Invalid email or password.";
        }
    }
    
    if (isset($_POST['register'])) {
        $employee_id = mysqli_real_escape_string($conn, $_POST['reg_employee_id']);
        $email = mysqli_real_escape_string($conn, $_POST['reg_email']);
        $password = password_hash($_POST['reg_password'], PASSWORD_DEFAULT);
        $role = mysqli_real_escape_string($conn, $_POST['reg_role']);
        
        $check_sql = "SELECT id FROM users WHERE email = '$email' OR employee_id = '$employee_id'";
        $check_result = mysqli_query($conn, $check_sql);
        
        if (mysqli_num_rows($check_result) == 0) {
            $sql = "INSERT INTO users (employee_id, email, password, role, is_verified) 
                    VALUES ('$employee_id', '$email', '$password', '$role', 1)";
            
            if (mysqli_query($conn, $sql)) {
                $user_id = mysqli_insert_id($conn);
                
                // Create employee record
                $emp_sql = "INSERT INTO employees (user_id, first_name, last_name, department, position, joining_date, employment_status) 
                           VALUES ($user_id, 'New', 'User', 'Not Assigned', 'Not Assigned', CURDATE(), 'active')";
                mysqli_query($conn, $emp_sql);
                
                $register_success = "Registration successful! Please login.";
            } else {
                $register_error = "Error: " . mysqli_error($conn);
            }
        } else {
            $register_error = "Email or Employee ID already exists!";
        }
    }
}
?>
<!DOCTYPE html>
 <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dayflow HRMS - Human Resource Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }
        
        .container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        /* Left Panel with Animation */
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 60px 40px;
            color: white;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .left-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: cover;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 40px;
            z-index: 1;
            position: relative;
        }
        
        .logo-icon {
            font-size: 42px;
            background: rgba(255, 255, 255, 0.2);
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .logo-text h1 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 5px;
        }
        
        .logo-text p {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 300;
        }
        
        .hero-content {
            max-width: 600px;
            z-index: 1;
            position: relative;
        }
        
        .hero-content h2 {
            font-size: 48px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
            background: linear-gradient(to right, #fff, #e2e8f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .hero-content p {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 40px;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
        
        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        
        /* Right Panel with Forms */
        .right-panel {
            flex: 1;
            background: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }
        
        .form-container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
        }
        
        .form-tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .tab-btn {
            flex: 1;
            padding: 15px;
            background: none;
            border: none;
            font-size: 16px;
            font-weight: 600;
            color: #94a3b8;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }
        
        .tab-btn.active {
            color: #4f46e5;
        }
        
        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: #4f46e5;
        }
        
        .form-content {
            display: none;
            animation: fadeIn 0.5s ease;
        }
        
        .form-content.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #4a5568;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
            background: #f8fafc;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #4f46e5;
            background: white;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        .btn-primary {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }
        
        .error-message {
            background: #fed7d7;
            color: #c53030;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #c53030;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .success-message {
            background: #c6f6d5;
            color: #276749;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #276749;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Floating Animation */
        .floating-animation {
            position: absolute;
            z-index: 0;
        }
        
        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite linear;
        }
        
        .circle:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .circle:nth-child(2) {
            width: 150px;
            height: 150px;
            bottom: 30%;
            right: 15%;
            animation-delay: -5s;
        }
        
        .circle:nth-child(3) {
            width: 80px;
            height: 80px;
            top: 60%;
            left: 20%;
            animation-delay: -10s;
        }
        
        .circle:nth-child(4) {
            width: 120px;
            height: 120px;
            top: 10%;
            right: 20%;
            animation-delay: -15s;
        }
        
        @keyframes float {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }
            25% {
                transform: translate(20px, 20px) rotate(90deg);
            }
            50% {
                transform: translate(0, 40px) rotate(180deg);
            }
            75% {
                transform: translate(-20px, 20px) rotate(270deg);
            }
            100% {
                transform: translate(0, 0) rotate(360deg);
            }
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .container {
                flex-direction: column;
            }
            
            .left-panel, .right-panel {
                padding: 40px 20px;
            }
            
            .hero-content h2 {
                font-size: 36px;
            }
        }
        
        @media (max-width: 768px) {
            .features {
                grid-template-columns: 1fr;
            }
            
            .hero-content h2 {
                font-size: 28px;
            }
        }
        
        .demo-section {
            margin-top: 30px;
            padding: 20px;
            background: #f7fafc;
            border-radius: 10px;
            text-align: center;
        }
        
        .demo-btn {
            display: inline-block;
            padding: 12px 24px;
            background: #22c55e;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            margin: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        
        .demo-btn:hover {
            background: #16a34a;
            transform: translateY(-2px);
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a0aec0;
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Left Panel with Animation -->
        <div class="left-panel">
            <!-- Floating Animation Circles -->
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
            
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div class="logo-text">
                    <h1>Dayflow HRMS</h1>
                    <p>Every workday, perfectly aligned</p>
                </div>
            </div>
            
            <div class="hero-content">
                <h2>Streamline Your HR Operations</h2>
                <p>Manage attendance, leaves, payroll, and employee data all in one platform. Boost productivity with our comprehensive Human Resource Management System.</p>
                
                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-fingerprint"></i>
                        </div>
                        <div>
                            <h4>Secure Login</h4>
                            <p>Role-based access control</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <h4>Attendance Tracking</h4>
                            <p>Real-time check-in/out</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-plane-departure"></i>
                        </div>
                        <div>
                            <h4>Leave Management</h4>
                            <p>Automated approval workflow</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <h4>Payroll System</h4>
                            <p>Accurate salary processing</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Panel with Forms -->
        <div class="right-panel">
            <div class="form-container">
                <div class="form-tabs">
                    <button class="tab-btn active" onclick="switchTab('login')">Login</button>
                    <button class="tab-btn" onclick="switchTab('register')">Register</button>
                </div>
                
                <!-- Login Form -->
                <div id="login-form" class="form-content active">
                    <?php if (!empty($login_error)): ?>
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo htmlspecialchars($login_error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_GET['registered']) && $_GET['registered'] == 'true'): ?>
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            Registration successful! Please login with your credentials.
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password"><i class="fas fa-lock"></i> Password</label>
                            <div style="position: relative;">
                                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button type="submit" name="login" class="btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Login to Dashboard
                        </button>
                    </form>
                </div>
                
                <!-- Register Form -->
                <div id="register-form" class="form-content">
                    <?php if (!empty($register_error)): ?>
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo htmlspecialchars($register_error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($register_success)): ?>
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            <?php echo htmlspecialchars($register_success); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="reg_employee_id"><i class="fas fa-id-card"></i> Employee ID</label>
                            <input type="text" id="reg_employee_id" name="reg_employee_id" class="form-control" placeholder="Enter Employee ID" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="reg_email"><i class="fas fa-envelope"></i> Email Address</label>
                            <input type="email" id="reg_email" name="reg_email" class="form-control" placeholder="Enter your email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="reg_password"><i class="fas fa-lock"></i> Password</label>
                            <div style="position: relative;">
                                <input type="password" id="reg_password" name="reg_password" class="form-control" placeholder="Create a password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('reg_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="reg_role"><i class="fas fa-user-tag"></i> Role</label>
                            <select id="reg_role" name="reg_role" class="form-control" required>
                                <option value="">Select Role</option>
                                <option value="employee">Employee</option>
                                <option value="hr">HR Officer</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        
                        <button type="submit" name="register" class="btn-primary">
                            <i class="fas fa-user-plus"></i> Create Account
                        </button>
                    </form>
                </div>
                
                <div class="demo-section">
                    <h4><i class="fas fa-rocket"></i> Quick Demo Access</h4>
                    <p>Try with demo credentials:</p>
                    <button class="demo-btn" onclick="fillDemo('admin')">
                        <i class="fas fa-user-shield"></i> Admin Login
                    </button>
                    <button class="demo-btn" onclick="fillDemo('hr')">
                        <i class="fas fa-user-tie"></i> HR Login
                    </button>
                    <button class="demo-btn" onclick="fillDemo('employee')">
                        <i class="fas fa-user"></i> Employee Login
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Tab switching
        function switchTab(tabName) {
            // Update active tab button
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Show active form
            document.querySelectorAll('.form-content').forEach(form => {
                form.classList.remove('active');
            });
            document.getElementById(tabName + '-form').classList.add('active');
        }
        
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Fill demo credentials
        function fillDemo(role) {
            const credentials = {
                'admin': {
                    email: 'admin@dayflow.com',
                    password: 'Admin@123'
                },
                'hr': {
                    email: 'hr@dayflow.com',
                    password: 'Hr@123456'
                },
                'employee': {
                    email: 'employee@dayflow.com',
                    password: 'Employee@123'
                }
            };
            
            // Switch to login tab
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.textContent === 'Login') {
                    btn.classList.add('active');
                }
            });
            
            document.querySelectorAll('.form-content').forEach(form => {
                form.classList.remove('active');
            });
            document.getElementById('login-form').classList.add('active');
            
            // Fill credentials
            document.getElementById('email').value = credentials[role].email;
            document.getElementById('password').value = credentials[role].password;
            
            // Show success message
            const loginForm = document.getElementById('login-form');
            let successMsg = loginForm.querySelector('.demo-success');
            if (!successMsg) {
                successMsg = document.createElement('div');
                successMsg.className = 'success-message demo-success';
                loginForm.prepend(successMsg);
            }
            successMsg.innerHTML = `<i class="fas fa-info-circle"></i> ${role.charAt(0).toUpperCase() + role.slice(1)} credentials filled. Click Login to continue.`;
            
            // Remove message after 5 seconds
            setTimeout(() => {
                if (successMsg.parentNode) {
                    successMsg.remove();
                }
            }, 5000);
        }
    </script>
</body> 

</html>