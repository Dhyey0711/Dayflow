<?php
// includes/functions.php

// Function to get setting value
function get_setting($key, $default = '') {
    global $conn;
    
    $query = "SELECT value FROM settings WHERE setting_key = '$key'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['value'];
    }
    
    return $default;
}

// Function to get employee by user ID
function get_employee_by_user_id($user_id) {
    global $conn;
    
    $query = "SELECT * FROM employees WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return false;
}

// Function to get employee leave balance
function get_employee_leave_balance($employee_id) {
    global $conn;
    
    $current_year = date('Y');
    $balance = [];
    
    $query = "SELECT lb.*, lt.name as leave_type_name, lt.short_code, lt.color_code 
              FROM leave_balance lb
              JOIN leave_types lt ON lb.leave_type_id = lt.id
              WHERE lb.employee_id = '$employee_id' 
              AND lb.year = '$current_year'
              AND lt.is_active = 1";
    
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $balance[] = $row;
        }
    }
    
    return $balance;
}

// Function to get recent payroll records
function get_employee_payroll($employee_id, $limit = 1) {
    global $conn;
    
    $query = "SELECT * FROM payroll 
              WHERE employee_id = '$employee_id' 
              ORDER BY pay_period_end DESC 
              LIMIT $limit";
    
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return null;
}

// Function to log activity
function log_activity($user_id, $action, $module, $record_id = null) {
    global $conn;
    
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $current_time = date('Y-m-d H:i:s');
    
    $query = "INSERT INTO activity_logs (user_id, action, module, record_id, ip_address, user_agent, timestamp) 
              VALUES ('$user_id', '$action', '$module', '$record_id', '$ip_address', '$user_agent', '$current_time')";
    
    return mysqli_query($conn, $query);
}

// Function to send email
function send_email($to, $subject, $message, $attachments = []) {
    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . get_setting('company_email', 'noreply@dayflow.com') . "\r\n";
    $headers .= "Reply-To: " . get_setting('company_email', 'noreply@dayflow.com') . "\r\n";
    
    // Send email (you might want to use PHPMailer or similar in production)
    $company_name = get_setting('company_name', 'Dayflow HRMS');
    $from_email = get_setting('company_email', 'noreply@dayflow.com');
    
    // In production, use a proper email library
    // return mail($to, $subject, $message, $headers);
    
    // For development, log to file
    $log_message = "To: $to\nSubject: $subject\n\n$message\n\n";
    file_put_contents('../logs/emails.log', $log_message, FILE_APPEND);
    
    return true;
}

// Function to validate date
function validate_date($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

// Function to calculate working days between dates
function calculate_working_days($start_date, $end_date) {
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $end->modify('+1 day'); // Include end date
    
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($start, $interval, $end);
    
    $working_days = 0;
    
    foreach ($period as $date) {
        $day_of_week = $date->format('N'); // 1-7 (Mon-Sun)
        if ($day_of_week < 6) { // Monday-Friday
            $working_days++;
        }
    }
    
    return $working_days;
}

// Function to check if date is holiday
function is_holiday($date) {
    global $conn;
    
    $query = "SELECT * FROM holidays WHERE date = '$date' AND is_active = 1";
    $result = mysqli_query($conn, $query);
    
    return ($result && mysqli_num_rows($result) > 0);
}

// Function to get next employee ID
function generate_employee_id() {
    global $conn;
    
    $year = date('y');
    $prefix = get_setting('employee_id_prefix', 'EMP');
    
    $query = "SELECT MAX(id) as max_id FROM employees WHERE id LIKE '$prefix$year%'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($row['max_id']) {
            $last_num = intval(substr($row['max_id'], -4));
            $next_num = str_pad($last_num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $next_num = '0001';
        }
    } else {
        $next_num = '0001';
    }
    
    return $prefix . $year . $next_num;
}

// Function to format date
function format_date($date, $format = 'F j, Y') {
    if (empty($date) || $date == '0000-00-00') {
        return 'N/A';
    }
    
    $timestamp = strtotime($date);
    return date($format, $timestamp);
}

// Function to format time
function format_time($time, $format = 'h:i A') {
    if (empty($time) || $time == '00:00:00') {
        return 'N/A';
    }
    
    $timestamp = strtotime($time);
    return date($format, $timestamp);
}

// Function to calculate age from birth date
function calculate_age($birth_date) {
    if (empty($birth_date) || $birth_date == '0000-00-00') {
        return 'N/A';
    }
    
    $birth = new DateTime($birth_date);
    $today = new DateTime();
    $age = $today->diff($birth);
    
    return $age->y;
}

// Function to sanitize input
function sanitize_input($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

// Function to check if user has permission
function has_permission($permission) {
    if (!isset($_SESSION['role'])) {
        return false;
    }
    
    $role = $_SESSION['role'];
    
    // Define permissions for each role
    $permissions = [
        'admin' => ['all'],
        'hr' => [
            'view_employees', 'add_employees', 'edit_employees',
            'view_attendance', 'manage_attendance',
            'view_leave', 'approve_leave',
            'view_payroll', 'manage_payroll',
            'view_documents', 'upload_documents'
        ],
        'employee' => [
            'view_profile', 'edit_profile',
            'view_own_attendance', 'check_in_out',
            'view_own_leave', 'apply_leave',
            'view_own_payroll', 'view_own_documents'
        ]
    ];
    
    if ($role == 'admin' || 
        (isset($permissions[$role]) && in_array($permission, $permissions[$role]))) {
        return true;
    }
    
    return false;
}

// Function to get department name by code
function get_department_name($code) {
    global $conn;
    
    $query = "SELECT name FROM departments WHERE code = '$code'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['name'];
    }
    
    return 'N/A';
}

// Function to get position name by code
function get_position_name($code) {
    global $conn;
    
    $query = "SELECT name FROM positions WHERE code = '$code'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['name'];
    }
    
    return 'N/A';
}

// Function to get employee name by ID
function get_employee_name($employee_id) {
    global $conn;
    
    $query = "SELECT CONCAT(first_name, ' ', last_name) as full_name FROM employees WHERE id = '$employee_id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['full_name'];
    }
    
    return 'Unknown Employee';
}

// Function to format currency
function format_currency($amount) {
    $currency = get_setting('currency', '₹');
    $decimal_places = get_setting('currency_decimal_places', 2);
    
    return $currency . number_format($amount, $decimal_places);
}

// Function to check if attendance is editable
function is_attendance_editable($date) {
    $max_edit_days = get_setting('attendance_edit_days', 3);
    $current_date = date('Y-m-d');
    $date_diff = abs(strtotime($current_date) - strtotime($date));
    $days_diff = floor($date_diff / (60 * 60 * 24));
    
    return $days_diff <= $max_edit_days;
}

// Function to get overtime rate
function get_overtime_rate($employee_id) {
    global $conn;
    
    // Get employee's overtime rate
    $query = "SELECT overtime_rate FROM employees WHERE id = '$employee_id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (!empty($row['overtime_rate'])) {
            return $row['overtime_rate'];
        }
    }
    
    // Get default overtime rate from settings
    return get_setting('default_overtime_rate', 1.5);
}

// Function to calculate overtime pay
function calculate_overtime_pay($hours, $hourly_rate, $overtime_rate = null) {
    if ($overtime_rate === null) {
        $overtime_rate = get_setting('default_overtime_rate', 1.5);
    }
    
    return $hours * $hourly_rate * $overtime_rate;
}

// Function to get current pay period
function get_current_pay_period() {
    $period_type = get_setting('pay_period_type', 'monthly');
    
    $today = date('Y-m-d');
    
    if ($period_type == 'monthly') {
        $start = date('Y-m-01');
        $end = date('Y-m-t');
    } elseif ($period_type == 'biweekly') {
        // Bi-weekly calculation (starting from first Monday of year)
        $week_number = date('W');
        $is_even_week = ($week_number % 2 == 0);
        
        if ($is_even_week) {
            $start = date('Y-m-d', strtotime('monday last week'));
            $end = date('Y-m-d', strtotime('sunday this week'));
        } else {
            $start = date('Y-m-d', strtotime('monday this week'));
            $end = date('Y-m-d', strtotime('sunday next week'));
        }
    } else { // weekly
        $start = date('Y-m-d', strtotime('monday this week'));
        $end = date('Y-m-d', strtotime('sunday this week'));
    }
    
    return [
        'start' => $start,
        'end' => $end
    ];
}

// Function to generate PDF
function generate_pdf($html, $filename, $output = 'download') {
    // This is a placeholder - in production, use a library like TCPDF or mPDF
    // Example with TCPDF:
    /*
    require_once('../includes/tcpdf/tcpdf.php');
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor(get_setting('company_name'));
    $pdf->SetTitle($filename);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->AddPage();
    $pdf->writeHTML($html, true, false, true, false, '');
    
    if ($output == 'download') {
        $pdf->Output($filename . '.pdf', 'D');
    } else {
        return $pdf->Output($filename . '.pdf', 'S');
    }
    */
    
    // For now, just return HTML
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '.pdf"');
    echo $html;
    exit();
}

// Function to backup database
function backup_database($path = '../backups/') {
    global $conn;
    
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
    
    $tables = array();
    $result = mysqli_query($conn, 'SHOW TABLES');
    
    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }
    
    $return = '';
    
    foreach ($tables as $table) {
        $result = mysqli_query($conn, 'SELECT * FROM ' . $table);
        $num_fields = mysqli_num_fields($result);
        
        $return .= 'DROP TABLE IF EXISTS ' . $table . ';';
        $row2 = mysqli_fetch_row(mysqli_query($conn, 'SHOW CREATE TABLE ' . $table));
        $return .= "\n\n" . $row2[1] . ";\n\n";
        
        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = mysqli_fetch_row($result)) {
                $return .= 'INSERT INTO ' . $table . ' VALUES(';
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = preg_replace("/\n/", "\\n", $row[$j]);
                    if (isset($row[$j])) {
                        $return .= '"' . $row[$j] . '"';
                    } else {
                        $return .= '""';
                    }
                    if ($j < ($num_fields - 1)) {
                        $return .= ',';
                    }
                }
                $return .= ");\n";
            }
        }
        $return .= "\n\n\n";
    }
    
    $filename = $path . 'db_backup_' . date('Y-m-d_H-i-s') . '.sql';
    $handle = fopen($filename, 'w+');
    fwrite($handle, $return);
    fclose($handle);
    
    return $filename;
}

// Function to encrypt sensitive data
function encrypt_data($data, $key = null) {
    if ($key === null) {
        $key = get_setting('encryption_key', 'dayflow_default_key');
    }
    
    $method = 'AES-256-CBC';
    $iv_length = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($iv_length);
    $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
    
    return base64_encode($encrypted . '::' . $iv);
}

// Function to decrypt data
function decrypt_data($data, $key = null) {
    if ($key === null) {
        $key = get_setting('encryption_key', 'dayflow_default_key');
    }
    
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    $method = 'AES-256-CBC';
    
    return openssl_decrypt($encrypted_data, $method, $key, 0, $iv);
}

// Function to generate random password
function generate_password($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $password;
}

// Function to validate password strength
function validate_password_strength($password) {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter';
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must contain at least one lowercase letter';
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one number';
    }
    
    if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
        $errors[] = 'Password must contain at least one special character';
    }
    
    return $errors;
}

// Function to get remaining working hours today
function get_remaining_working_hours($employee_id) {
    global $conn;
    
    $today = date('Y-m-d');
    
    $query = "SELECT check_in, total_hours FROM attendance 
              WHERE employee_id = '$employee_id' AND date = '$today'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $attendance = mysqli_fetch_assoc($result);
        
        if ($attendance['check_in'] && !$attendance['total_hours']) {
            $check_in_time = strtotime($attendance['check_in']);
            $current_time = time();
            $worked_seconds = $current_time - $check_in_time;
            $worked_hours = $worked_seconds / 3600;
            
            $work_hours = get_setting('working_hours_per_day', 8);
            $remaining_hours = max(0, $work_hours - $worked_hours);
            
            return round($remaining_hours, 2);
        }
    }
    
    return get_setting('working_hours_per_day', 8);
}

// Function to get employee's shift timing
function get_employee_shift($employee_id) {
    global $conn;
    
    $query = "SELECT shift_start, shift_end FROM employees WHERE id = '$employee_id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $employee = mysqli_fetch_assoc($result);
        
        if (!empty($employee['shift_start']) && !empty($employee['shift_end'])) {
            return [
                'start' => $employee['shift_start'],
                'end' => $employee['shift_end']
            ];
        }
    }
    
    // Return default shift timings
    return [
        'start' => get_setting('work_start_time', '09:00:00'),
        'end' => get_setting('work_end_time', '18:00:00')
    ];
}

// Function to check if employee is on leave today
function is_on_leave_today($employee_id) {
    global $conn;
    
    $today = date('Y-m-d');
    
    $query = "SELECT * FROM leave_requests 
              WHERE employee_id = '$employee_id' 
              AND start_date <= '$today' 
              AND end_date >= '$today'
              AND status = 'approved'";
    
    $result = mysqli_query($conn, $query);
    
    return ($result && mysqli_num_rows($result) > 0);
}

// Function to get employee's team members
function get_team_members($employee_id) {
    global $conn;
    
    // Get employee's department
    $query = "SELECT department FROM employees WHERE id = '$employee_id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $employee = mysqli_fetch_assoc($result);
        $department = $employee['department'];
        
        // Get all employees in the same department
        $team_query = "SELECT id, CONCAT(first_name, ' ', last_name) as name, position, profile_pic 
                       FROM employees 
                       WHERE department = '$department' 
                       AND id != '$employee_id'
                       AND status = 'active'
                       ORDER BY first_name";
        
        $team_result = mysqli_query($conn, $team_query);
        $team_members = [];
        
        if ($team_result) {
            while ($row = mysqli_fetch_assoc($team_result)) {
                $team_members[] = $row;
            }
        }
        
        return $team_members;
    }
    
    return [];
}

// Function to get employee's manager
function get_employee_manager($employee_id) {
    global $conn;
    
    $query = "SELECT manager_id FROM employees WHERE id = '$employee_id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $employee = mysqli_fetch_assoc($result);
        
        if (!empty($employee['manager_id'])) {
            $manager_query = "SELECT id, CONCAT(first_name, ' ', last_name) as name, email, profile_pic 
                              FROM employees 
                              WHERE id = '{$employee['manager_id']}'";
            
            $manager_result = mysqli_query($conn, $manager_query);
            
            if ($manager_result && mysqli_num_rows($manager_result) > 0) {
                return mysqli_fetch_assoc($manager_result);
            }
        }
    }
    
    return null;
}

// Function to get notification count
function get_unread_notification_count($user_id) {
    global $conn;
    
    $query = "SELECT COUNT(*) as count FROM notifications 
              WHERE user_id = '$user_id' AND is_read = 0";
    
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }
    
    return 0;
}

// Function to add notification
function add_notification($user_id, $title, $message, $type = 'info', $link = null) {
    global $conn;
    
    $query = "INSERT INTO notifications (user_id, title, message, type, link, created_at) 
              VALUES ('$user_id', '$title', '$message', '$type', '$link', NOW())";
    
    return mysqli_query($conn, $query);
}

// Function to format file size
function format_file_size($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    
    return round($bytes, 2) . ' ' . $units[$i];
}

// Function to check file upload type
function validate_file_type($file_name, $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']) {
    $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    return in_array($extension, $allowed_types);
}

// Function to check file upload size
function validate_file_size($file_size, $max_size_mb = 10) {
    $max_size = $max_size_mb * 1024 * 1024; // Convert MB to bytes
    return $file_size <= $max_size;
}
?>
