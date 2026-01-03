<?php
// Employee-side static version - complete with all tabs
session_start();

// Simulate logged-in employee
$_SESSION['employee_id'] = 'EMP001';
$_SESSION['role'] = 'employee';

// Current employee data
$employee = [
    'id' => 'EMP001',
    'first_name' => 'Sarah',
    'last_name' => 'Johnson',
    'email' => 'sarah.j@company.com',
    'role' => 'employee',
    'profile_pic' => '',
    'position' => 'Software Engineer',
    'department' => 'Technology',
    'phone' => '+1 (555) 234-5678',
    'date_of_birth' => '1992-08-22',
    'gender' => 'female',
    'address' => '456 Tech Ave, San Francisco, CA 94107',
    'joining_date' => '2023-03-15',
    'job_type' => 'full-time',
    'employment_status' => 'active',
    'basic_salary' => 85000,
    'emergency_contact' => 'John Johnson (Father): +1 (555) 987-6543',
    'bank_account' => 'XXXX-XXXX-1234',
    'pan_number' => 'ABCDE1234F',
    'pf_number' => 'PF/2023/00123',
    'uan_number' => '123456789012'
];

// Employee dashboard stats
$employee_stats = [
    'total_attendance_days' => 185,
    'leaves_taken' => 12,
    'remaining_leaves' => 18,
    'next_payday' => date('Y-m-28'),
    'current_month_attendance' => 18,
    'late_arrivals' => 2,
    'early_departures' => 1
];

// Today's date
$today = date('Y-m-d');

// Employee's attendance records (last 30 days)
$my_attendance = [];
for ($i = 29; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $statuses = ['present', 'present', 'present', 'present', 'present', 'half-day', 'absent'];
    $status = $statuses[array_rand($statuses)];
    
    $my_attendance[] = [
        'date' => $date,
        'check_in' => ($status == 'present' || $status == 'half-day') ? '09:' . sprintf('%02d', rand(0, 15)) . ':00' : null,
        'check_out' => ($status == 'present') ? '18:' . sprintf('%02d', rand(0, 30)) . ':00' : (($status == 'half-day') ? '13:00:00' : null),
        'total_hours' => ($status == 'present') ? 8.5 : (($status == 'half-day') ? 4.0 : 0),
        'status' => $status,
        'remarks' => ($status == 'absent') ? 'Sick Leave' : (($status == 'half-day') ? 'Doctor Appointment' : '')
    ];
}

// Employee's leave requests
$my_leave_requests = [
    [
        'id' => 1,
        'leave_type_name' => 'Sick Leave',
        'start_date' => date('Y-m-d', strtotime('+2 days')),
        'end_date' => date('Y-m-d', strtotime('+2 days')),
        'total_days' => 1,
        'reason' => 'Medical appointment with dentist',
        'applied_date' => date('Y-m-d', strtotime('-1 days')),
        'status' => 'pending',
        'comments' => '',
        'approved_by' => '',
        'approved_date' => null
    ],
    [
        'id' => 2,
        'leave_type_name' => 'Annual Leave',
        'start_date' => date('Y-m-d', strtotime('+10 days')),
        'end_date' => date('Y-m-d', strtotime('+15 days')),
        'total_days' => 5,
        'reason' => 'Family vacation to Hawaii',
        'applied_date' => date('Y-m-d', strtotime('-5 days')),
        'status' => 'approved',
        'comments' => 'Enjoy your vacation!',
        'approved_by' => 'John Doe (HR Manager)',
        'approved_date' => date('Y-m-d', strtotime('-3 days'))
    ],
    [
        'id' => 3,
        'leave_type_name' => 'Personal Leave',
        'start_date' => date('Y-m-d', strtotime('-7 days')),
        'end_date' => date('Y-m-d', strtotime('-7 days')),
        'total_days' => 1,
        'reason' => 'Personal work at home - internet installation',
        'applied_date' => date('Y-m-d', strtotime('-10 days')),
        'status' => 'rejected',
        'comments' => 'Please plan personal leaves in advance during non-peak project times',
        'approved_by' => 'John Doe (HR Manager)',
        'approved_date' => date('Y-m-d', strtotime('-9 days'))
    ],
    [
        'id' => 4,
        'leave_type_name' => 'Sick Leave',
        'start_date' => date('Y-m-d', strtotime('-15 days')),
        'end_date' => date('Y-m-d', strtotime('-15 days')),
        'total_days' => 1,
        'reason' => 'Fever and cold',
        'applied_date' => date('Y-m-d', strtotime('-16 days')),
        'status' => 'approved',
        'comments' => 'Get well soon',
        'approved_by' => 'Lisa Taylor (HR Coordinator)',
        'approved_date' => date('Y-m-d', strtotime('-16 days'))
    ]
];

// Employee's payroll history
$my_payroll = [
    [
        'id' => 1,
        'month_year' => '2024-01',
        'basic_salary' => 7083.33,
        'hra' => 1416.67,
        'da' => 708.33,
        'conveyance' => 800.00,
        'medical' => 1250.00,
        'special_allowance' => 3250.00,
        'other_allowances' => 500.00,
        'income_tax' => 850.00,
        'professional_tax' => 200.00,
        'pf_deduction' => 850.00,
        'other_deductions' => 150.00,
        'total_earnings' => 14008.33,
        'total_deductions' => 2050.00,
        'net_salary' => 11958.33,
        'payment_status' => 'paid',
        'payment_date' => '2024-01-28',
        'payment_mode' => 'Bank Transfer'
    ],
    [
        'id' => 2,
        'month_year' => '2023-12',
        'basic_salary' => 7083.33,
        'hra' => 1416.67,
        'da' => 708.33,
        'conveyance' => 800.00,
        'medical' => 1250.00,
        'special_allowance' => 3250.00,
        'other_allowances' => 500.00,
        'income_tax' => 850.00,
        'professional_tax' => 200.00,
        'pf_deduction' => 850.00,
        'other_deductions' => 150.00,
        'total_earnings' => 14008.33,
        'total_deductions' => 2050.00,
        'net_salary' => 11958.33,
        'payment_status' => 'paid',
        'payment_date' => '2023-12-28',
        'payment_mode' => 'Bank Transfer'
    ],
    [
        'id' => 3,
        'month_year' => '2023-11',
        'basic_salary' => 7083.33,
        'hra' => 1416.67,
        'da' => 708.33,
        'conveyance' => 800.00,
        'medical' => 1250.00,
        'special_allowance' => 3250.00,
        'other_allowances' => 500.00,
        'income_tax' => 850.00,
        'professional_tax' => 200.00,
        'pf_deduction' => 850.00,
        'other_deductions' => 150.00,
        'total_earnings' => 14008.33,
        'total_deductions' => 2050.00,
        'net_salary' => 11958.33,
        'payment_status' => 'paid',
        'payment_date' => '2023-11-28',
        'payment_mode' => 'Bank Transfer'
    ],
    [
        'id' => 4,
        'month_year' => '2023-10',
        'basic_salary' => 7083.33,
        'hra' => 1416.67,
        'da' => 708.33,
        'conveyance' => 800.00,
        'medical' => 1250.00,
        'special_allowance' => 3250.00,
        'other_allowances' => 500.00,
        'income_tax' => 850.00,
        'professional_tax' => 200.00,
        'pf_deduction' => 850.00,
        'other_deductions' => 150.00,
        'total_earnings' => 14008.33,
        'total_deductions' => 2050.00,
        'net_salary' => 11958.33,
        'payment_status' => 'paid',
        'payment_date' => '2023-10-28',
        'payment_mode' => 'Bank Transfer'
    ]
];

// Employee documents
$my_documents = [
    [
        'id' => 1,
        'name' => 'Offer Letter.pdf',
        'type' => 'Offer Letter',
        'upload_date' => '2023-03-10',
        'size' => '1.2 MB',
        'category' => 'Employment'
    ],
    [
        'id' => 2,
        'name' => 'Appointment Letter.pdf',
        'type' => 'Appointment Letter',
        'upload_date' => '2023-03-12',
        'size' => '1.5 MB',
        'category' => 'Employment'
    ],
    [
        'id' => 3,
        'name' => 'PAN Card.pdf',
        'type' => 'PAN Card',
        'upload_date' => '2023-03-15',
        'size' => '0.8 MB',
        'category' => 'Identity'
    ],
    [
        'id' => 4,
        'name' => 'Aadhar Card.pdf',
        'type' => 'Aadhar Card',
        'upload_date' => '2023-03-15',
        'size' => '1.0 MB',
        'category' => 'Identity'
    ],
    [
        'id' => 5,
        'name' => 'January 2024 Payslip.pdf',
        'type' => 'Payslip',
        'upload_date' => '2024-01-29',
        'size' => '0.5 MB',
        'category' => 'Payroll'
    ],
    [
        'id' => 6,
        'name' => 'December 2023 Payslip.pdf',
        'type' => 'Payslip',
        'upload_date' => '2023-12-29',
        'size' => '0.5 MB',
        'category' => 'Payroll'
    ]
];

// Leave types
$leave_types = [
    ['name' => 'Annual Leave', 'days_allowed' => 30],
    ['name' => 'Sick Leave', 'days_allowed' => 12],
    ['name' => 'Personal Leave', 'days_allowed' => 5],
    ['name' => 'Maternity Leave', 'days_allowed' => 180],
    ['name' => 'Paternity Leave', 'days_allowed' => 15],
    ['name' => 'Bereavement Leave', 'days_allowed' => 7],
    ['name' => 'Marriage Leave', 'days_allowed' => 10]
];

// Recent activities/notifications
$notifications = [
    [
        'id' => 1,
        'title' => 'Leave Approved',
        'message' => 'Your annual leave for Feb 10-15 has been approved',
        'date' => date('Y-m-d H:i:s', strtotime('-2 hours')),
        'read' => false,
        'type' => 'leave'
    ],
    [
        'id' => 2,
        'title' => 'Payslip Available',
        'message' => 'January 2024 payslip is now available for download',
        'date' => date('Y-m-d H:i:s', strtotime('-1 days')),
        'read' => true,
        'type' => 'payroll'
    ],
    [
        'id' => 3,
        'title' => 'Attendance Regularized',
        'message' => 'Your attendance for Jan 15 has been updated',
        'date' => date('Y-m-d H:i:s', strtotime('-2 days')),
        'read' => true,
        'type' => 'attendance'
    ],
    [
        'id' => 4,
        'title' => 'Holiday Notice',
        'message' => 'Republic Day holiday on January 26',
        'date' => date('Y-m-d H:i:s', strtotime('-3 days')),
        'read' => true,
        'type' => 'announcement'
    ]
];

// Holidays
$holidays = [
    ['date' => '2024-01-26', 'name' => 'Republic Day', 'type' => 'National Holiday'],
    ['date' => '2024-03-25', 'name' => 'Holi', 'type' => 'Festival'],
    ['date' => '2024-04-14', 'name' => 'Ambedkar Jayanti', 'type' => 'Public Holiday'],
    ['date' => '2024-05-01', 'name' => 'Labour Day', 'type' => 'Public Holiday'],
    ['date' => '2024-08-15', 'name' => 'Independence Day', 'type' => 'National Holiday'],
    ['date' => '2024-10-02', 'name' => 'Gandhi Jayanti', 'type' => 'National Holiday'],
    ['date' => '2024-12-25', 'name' => 'Christmas', 'type' => 'Festival']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dayflow - Employee Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Base Styles */
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
            --info: #06b6d4;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --border: #e2e8f0;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
        }

        body {
            background-color: #f1f5f9;
            color: var(--dark);
            overflow-x: hidden;
        }

        /* Dashboard Layout */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .sidebar-menu {
            flex: 1;
            padding: 1.5rem 0;
            list-style: none;
            overflow-y: auto;
        }

        .menu-item {
            padding: 0.9rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
            color: #cbd5e1;
            border-left: 4px solid transparent;
        }

        .menu-item:hover {
            background-color: var(--sidebar-hover);
            color: white;
        }

        .menu-item.active {
            background-color: var(--sidebar-hover);
            color: white;
            border-left-color: var(--primary);
            font-weight: 600;
        }

        .menu-item i {
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            overflow: hidden;
            flex-shrink: 0;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.95rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 0.8rem;
            color: #94a3b8;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-left: 260px;
        }

        /* Top Header */
        .top-header {
            background-color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .header-left h1 {
            color: var(--dark);
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-btn {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--gray);
            cursor: pointer;
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .current-time {
            background: var(--primary);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Content Area */
        .content-area {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        .page-content {
            display: none;
        }

        .page-content.active {
            display: block;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .quick-action-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 2px solid transparent;
        }

        .quick-action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        .action-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            flex-shrink: 0;
        }

        .action-info {
            flex: 1;
        }

        .action-info h3 {
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .action-info p {
            color: var(--gray);
            font-size: 0.9rem;
            line-height: 1.4;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 1.2rem;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            flex-shrink: 0;
        }

        .stat-info {
            flex: 1;
        }

        .stat-info h3 {
            font-size: 0.9rem;
            color: var(--gray);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.2rem;
        }

        .stat-detail {
            font-size: 0.85rem;
            color: var(--gray);
        }

        .positive {
            color: var(--secondary);
        }

        .negative {
            color: var(--danger);
        }

        /* Section Cards */
        .section-card {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-header h3 {
            color: var(--dark);
            font-size: 1.2rem;
        }

        /* Table Styles */
        .table-container {
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .table-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        thead {
            background-color: #f8fafc;
        }

        th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
            min-width: 80px;
        }

        .status-present { background-color: #d1fae5; color: var(--secondary); }
        .status-absent { background-color: #fee2e2; color: var(--danger); }
        .status-half-day { background-color: #fef3c7; color: var(--warning); }
        .status-pending { background-color: #e0e7ff; color: var(--primary); }
        .status-approved { background-color: #d1fae5; color: var(--secondary); }
        .status-rejected { background-color: #fee2e2; color: var(--danger); }
        .status-paid { background-color: #d1fae5; color: var(--secondary); }
        .status-unpaid { background-color: #fee2e2; color: var(--danger); }

        /* Button Styles */
        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-success {
            background-color: var(--secondary);
            color: white;
        }

        .btn-warning {
            background-color: var(--warning);
            color: white;
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-info {
            background-color: var(--info);
            color: white;
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--border);
            color: var(--dark);
        }

        .btn-outline:hover {
            background-color: #f8fafc;
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            justify-content: center;
            border-radius: 8px;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        .modal {
            background-color: white;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.2);
            transform: translateY(-20px);
            transition: transform 0.3s;
        }

        .modal-overlay.active .modal {
            transform: translateY(0);
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            color: var(--dark);
            font-size: 1.3rem;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray);
            line-height: 1;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
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
            padding: 0.8rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            background-size: 1rem;
            padding-right: 2.5rem;
        }

        /* Profile Styles */
        .profile-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border);
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            flex-shrink: 0;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-details {
            flex: 1;
        }

        .profile-details h2 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .profile-details p {
            color: var(--gray);
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .profile-details p i {
            width: 20px;
            color: var(--primary);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .info-card h3 {
            color: var(--dark);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border);
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border);
        }

        .info-label {
            color: var(--gray);
            font-weight: 500;
        }

        .info-value {
            font-weight: 600;
            color: var(--dark);
            text-align: right;
            max-width: 60%;
            word-break: break-word;
        }

        /* Attendance Calendar */
        .attendance-calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-top: 1rem;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            padding: 5px;
            font-size: 0.9rem;
        }

        .calendar-day.weekday {
            font-weight: 600;
            color: var(--gray);
            background: none;
        }

        .calendar-day.present {
            background-color: #d1fae5;
            color: var(--secondary);
        }

        .calendar-day.absent {
            background-color: #fee2e2;
            color: var(--danger);
        }

        .calendar-day.half-day {
            background-color: #fef3c7;
            color: var(--warning);
        }

        .calendar-day.leave {
            background-color: #e0e7ff;
            color: var(--primary);
        }

        .calendar-day.today {
            border: 2px solid var(--primary);
        }

        /* Notification Panel */
        .notification-panel {
            position: fixed;
            top: 80px;
            right: 20px;
            width: 350px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            z-index: 1001;
            display: none;
        }

        .notification-panel.active {
            display: block;
        }

        .notification-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .notification-item:hover {
            background-color: #f8fafc;
        }

        .notification-item.unread {
            background-color: #f0f9ff;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 0.3rem;
            color: var(--dark);
        }

        .notification-message {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .notification-time {
            font-size: 0.8rem;
            color: #94a3b8;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar-header h2 span,
            .menu-item span,
            .user-info {
                display: none;
            }
            
            .sidebar-header {
                justify-content: center;
                padding: 1rem;
            }
            
            .menu-item {
                justify-content: center;
                padding: 1rem;
            }
            
            .main-content {
                margin-left: 70px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -260px;
                width: 260px;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .top-header {
                padding: 1rem;
            }
            
            .header-left h1 {
                font-size: 1.2rem;
            }
            
            .content-area {
                padding: 1rem;
            }
        }

        /* Check In/Out Timer */
        .timer-display {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin: 1rem 0;
            color: var(--primary);
        }

        /* File Upload */
        .file-upload {
            border: 2px dashed var(--border);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .file-upload:hover {
            border-color: var(--primary);
            background-color: #f0f9ff;
        }

        .file-upload i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        /* Salary Breakdown */
        .salary-breakdown {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .salary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid var(--border);
        }

        .salary-total {
            border-top: 2px solid var(--border);
            margin-top: 1rem;
            padding-top: 1rem;
            font-size: 1.2rem;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <!-- Notification Panel -->
    <div class="notification-panel" id="notificationPanel">
        <div class="notification-header">
            <h3>Notifications</h3>
            <button class="btn btn-sm btn-outline" id="markAllRead">Mark all as read</button>
        </div>
        <div class="notification-list" id="notificationList">
            <!-- Notifications will be loaded here -->
        </div>
    </div>

    <!-- Dashboard Container -->
    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-calendar-day"></i> <span>Dayflow</span></h2>
                <p style="font-size: 0.9rem; color: #94a3b8; margin-top: 0.5rem;">Employee Portal</p>
            </div>
            
            <ul class="sidebar-menu">
                <li class="menu-item active" data-page="dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </li>
                <li class="menu-item" data-page="profile">
                    <i class="fas fa-user"></i>
                    <span>My Profile</span>
                </li>
                <li class="menu-item" data-page="attendance">
                    <i class="fas fa-calendar-check"></i>
                    <span>Attendance</span>
                </li>
                <li class="menu-item" data-page="leave">
                    <i class="fas fa-calendar-minus"></i>
                    <span>Leave Management</span>
                </li>
                <li class="menu-item" data-page="payroll">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Payroll</span>
                </li>
                <li class="menu-item" data-page="documents">
                    <i class="fas fa-file-alt"></i>
                    <span>Documents</span>
                </li>
                <li class="menu-item" data-page="holidays">
                    <i class="fas fa-umbrella-beach"></i>
                    <span>Holidays</span>
                </li>
            </ul>
            
            <div class="sidebar-footer">
                <div class="user-avatar" id="userAvatar">
                    <?php if (!empty($employee['profile_pic'])): ?>
                        <img src="<?php echo htmlspecialchars($employee['profile_pic']); ?>" alt="<?php echo htmlspecialchars($employee['first_name']); ?>">
                    <?php else: ?>
                        <?php echo strtoupper(substr($employee['first_name'], 0, 1)); ?>
                    <?php endif; ?>
                </div>
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></div>
                    <div class="user-role"><?php echo htmlspecialchars($employee['position']); ?></div>
                </div>
                <button class="btn btn-icon btn-outline" id="sidebarLogoutBtn" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="header-left">
                    <button class="btn btn-icon btn-outline" id="menuToggle" style="display: none;">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1><i class="fas fa-tachometer-alt"></i> Employee Dashboard</h1>
                </div>
                <div class="header-right">
                    <div class="current-time" id="currentTimeDisplay">
                        <?php echo date('h:i A'); ?>
                    </div>
                    
                    <button class="btn btn-primary" id="checkInOutBtn">
                        <i class="fas fa-clock"></i> <span id="checkActionText">Check In</span>
                    </button>
                    
                    <button class="notification-btn" id="notificationBtn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge" id="notificationCount">2</span>
                    </button>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Dashboard Page -->
                <div class="page-content active" id="dashboardPage">
                    <!-- Welcome Section -->
                    <div style="margin-bottom: 2rem;">
                        <h2 style="margin-bottom: 0.5rem;">Welcome, <?php echo htmlspecialchars($employee['first_name']); ?>!</h2>
                        <p style="color: var(--gray);">Here's your overview for <?php echo date('l, F j, Y'); ?></p>
                    </div>

                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <div class="quick-action-card" data-action="checkInOut">
                            <div class="action-icon" style="background-color: var(--primary);">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="action-info">
                                <h3>Attendance</h3>
                                <p>Mark your attendance for today</p>
                            </div>
                            <i class="fas fa-chevron-right" style="color: var(--gray);"></i>
                        </div>
                        
                        <div class="quick-action-card" data-action="applyLeave">
                            <div class="action-icon" style="background-color: var(--secondary);">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <div class="action-info">
                                <h3>Apply Leave</h3>
                                <p>Submit a new leave request</p>
                            </div>
                            <i class="fas fa-chevron-right" style="color: var(--gray);"></i>
                        </div>
                        
                        <div class="quick-action-card" data-action="viewPayslip">
                            <div class="action-icon" style="background-color: var(--warning);">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div class="action-info">
                                <h3>View Payslip</h3>
                                <p>Check your latest salary details</p>
                            </div>
                            <i class="fas fa-chevron-right" style="color: var(--gray);"></i>
                        </div>
                        
                        <div class="quick-action-card" data-action="updateProfile">
                            <div class="action-icon" style="background-color: var(--info);">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <div class="action-info">
                                <h3>Update Profile</h3>
                                <p>Edit your personal information</p>
                            </div>
                            <i class="fas fa-chevron-right" style="color: var(--gray);"></i>
                        </div>
                    </div>

                    <!-- Stats Overview -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon" style="background-color: var(--primary);">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Attendance Days</h3>
                                <div class="stat-value"><?php echo $employee_stats['total_attendance_days']; ?></div>
                                <div class="stat-detail positive">
                                    <i class="fas fa-check-circle"></i> This month: <?php echo $employee_stats['current_month_attendance']; ?> days
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon" style="background-color: var(--secondary);">
                                <i class="fas fa-umbrella-beach"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Leaves Available</h3>
                                <div class="stat-value"><?php echo $employee_stats['remaining_leaves']; ?></div>
                                <div class="stat-detail">
                                    Taken: <?php echo $employee_stats['leaves_taken']; ?> days
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon" style="background-color: var(--warning);">
                                <i class="fas fa-money-check-alt"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Next Payday</h3>
                                <div class="stat-value"><?php echo date('M j', strtotime($employee_stats['next_payday'])); ?></div>
                                <div class="stat-detail positive">
                                    <i class="fas fa-clock"></i> 
                                    <?php 
                                    $days_remaining = date_diff(date_create(date('Y-m-d')), date_create($employee_stats['next_payday']))->format('%a');
                                    echo $days_remaining . ' days remaining';
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon" style="background-color: var(--info);">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Performance</h3>
                                <div class="stat-value">94%</div>
                                <div class="stat-detail positive">
                                    <i class="fas fa-arrow-up"></i> 2% increase
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Attendance Status -->
                    <div class="section-card">
                        <div class="section-header">
                            <h3>Today's Attendance</h3>
                            <button class="btn btn-primary" id="markAttendanceBtn">
                                <i class="fas fa-edit"></i> Update Attendance
                            </button>
                        </div>
                        <?php 
                        $today_record = array_filter($my_attendance, function($a) use ($today) {
                            return $a['date'] === $today;
                        });
                        $today_record = reset($today_record);
                        $status_class = 'status-' . ($today_record['status'] ?? 'pending');
                        ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                            <div style="flex: 1; min-width: 300px;">
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                                    <div>
                                        <p style="color: var(--gray); margin-bottom: 0.5rem;">Status</p>
                                        <span class="status-badge <?php echo $status_class; ?>" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                            <?php echo ucfirst(str_replace('-', ' ', $today_record['status'] ?? 'Not Marked')); ?>
                                        </span>
                                    </div>
                                    <div>
                                        <p style="color: var(--gray); margin-bottom: 0.5rem;">Date</p>
                                        <p style="font-weight: 600; font-size: 1.1rem;"><?php echo date('F j, Y'); ?></p>
                                    </div>
                                    <div>
                                        <p style="color: var(--gray); margin-bottom: 0.5rem;">Check In</p>
                                        <p style="font-weight: 600; font-size: 1.1rem;">
                                            <?php echo isset($today_record['check_in']) ? date('h:i A', strtotime($today_record['check_in'])) : '--:--'; ?>
                                        </p>
                                    </div>
                                    <div>
                                        <p style="color: var(--gray); margin-bottom: 0.5rem;">Check Out</p>
                                        <p style="font-weight: 600; font-size: 1.1rem;">
                                            <?php echo isset($today_record['check_out']) ? date('h:i A', strtotime($today_record['check_out'])) : '--:--'; ?>
                                        </p>
                                    </div>
                                </div>
                                <div style="margin-top: 1rem;">
                                    <p style="color: var(--gray); margin-bottom: 0.5rem;">Total Hours</p>
                                    <p style="font-weight: 600; font-size: 1.2rem; color: var(--primary);">
                                        <?php echo $today_record['total_hours'] ?? '0'; ?> hours
                                    </p>
                                </div>
                            </div>
                            <div style="text-align: center; min-width: 200px;">
                                <div class="timer-display" id="workingTimer">00:00:00</div>
                                <p style="color: var(--gray); font-size: 0.9rem;">Working hours today</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Leave Requests -->
                    <div class="section-card">
                        <div class="section-header">
                            <h3>Recent Leave Requests</h3>
                            <button class="btn btn-outline" id="viewAllLeavesBtn">
                                <i class="fas fa-list"></i> View All
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach(array_slice($my_leave_requests, 0, 3) as $leave): 
                                        $status_class = 'status-' . $leave['status'];
                                    ?>
                                    <tr>
                                        <td><?php echo $leave['leave_type_name']; ?></td>
                                        <td><?php echo date('M j, Y', strtotime($leave['start_date'])); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($leave['end_date'])); ?></td>
                                        <td><?php echo $leave['total_days']; ?> day(s)</td>
                                        <td><span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst($leave['status']); ?></span></td>
                                        <td>
                                            <div style="display: flex; gap: 5px;">
                                                <button class="btn btn-icon btn-sm btn-outline view-leave-btn" data-id="<?php echo $leave['id']; ?>" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <?php if($leave['status'] == 'pending'): ?>
                                                <button class="btn btn-icon btn-sm btn-outline cancel-leave-btn" data-id="<?php echo $leave['id']; ?>" title="Cancel Request">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Profile Page -->
                <div class="page-content" id="profilePage">
                    <div class="section-card">
                        <div class="section-header">
                            <h3>My Profile</h3>
                            <button class="btn btn-primary" id="editProfileBtn">
                                <i class="fas fa-edit"></i> Edit Profile
                            </button>
                        </div>
                        
                        <div class="profile-header">
                            <div class="profile-avatar">
                                <?php if (!empty($employee['profile_pic'])): ?>
                                    <img src="<?php echo htmlspecialchars($employee['profile_pic']); ?>" alt="<?php echo htmlspecialchars($employee['first_name']); ?>">
                                <?php else: ?>
                                    <div style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                                        <?php echo strtoupper(substr($employee['first_name'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="profile-details">
                                <h2><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></h2>
                                <p><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($employee['position']); ?></p>
                                <p><i class="fas fa-building"></i> <?php echo htmlspecialchars($employee['department']); ?></p>
                                <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($employee['email']); ?></p>
                                <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($employee['phone']); ?></p>
                            </div>
                        </div>
                        
                        <div class="info-grid">
                            <div class="info-card">
                                <h3>Personal Information</h3>
                                <div class="info-item">
                                    <span class="info-label">Employee ID</span>
                                    <span class="info-value"><?php echo $employee['id']; ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Date of Birth</span>
                                    <span class="info-value"><?php echo date('F j, Y', strtotime($employee['date_of_birth'])); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Gender</span>
                                    <span class="info-value"><?php echo ucfirst($employee['gender']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Address</span>
                                    <span class="info-value"><?php echo htmlspecialchars($employee['address']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Emergency Contact</span>
                                    <span class="info-value"><?php echo htmlspecialchars($employee['emergency_contact']); ?></span>
                                </div>
                            </div>
                            
                            <div class="info-card">
                                <h3>Employment Details</h3>
                                <div class="info-item">
                                    <span class="info-label">Joining Date</span>
                                    <span class="info-value"><?php echo date('F j, Y', strtotime($employee['joining_date'])); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Job Type</span>
                                    <span class="info-value"><?php echo ucfirst(str_replace('-', ' ', $employee['job_type'])); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Employment Status</span>
                                    <span class="info-value status-badge status-present"><?php echo ucfirst($employee['employment_status']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Department</span>
                                    <span class="info-value"><?php echo htmlspecialchars($employee['department']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Position</span>
                                    <span class="info-value"><?php echo htmlspecialchars($employee['position']); ?></span>
                                </div>
                            </div>
                            
                            <div class="info-card">
                                <h3>Financial Information</h3>
                                <div class="info-item">
                                    <span class="info-label">Basic Salary</span>
                                    <span class="info-value">₹<?php echo number_format($employee['basic_salary'], 2); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Bank Account</span>
                                    <span class="info-value"><?php echo $employee['bank_account']; ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">PAN Number</span>
                                    <span class="info-value"><?php echo $employee['pan_number']; ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">PF Number</span>
                                    <span class="info-value"><?php echo $employee['pf_number']; ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">UAN Number</span>
                                    <span class="info-value"><?php echo $employee['uan_number']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Page -->
                <div class="page-content" id="attendancePage">
                    <div class="section-card">
                        <div class="section-header">
                            <h3>My Attendance</h3>
                            <div class="table-actions">
                                <input type="month" class="form-control" id="attendanceMonth" value="<?php echo date('Y-m'); ?>" style="width: auto;">
                                <button class="btn btn-outline">
                                    <i class="fas fa-download"></i> Export
                                </button>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 1.5rem;">
                            <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 20px; height: 20px; background-color: #d1fae5; border-radius: 4px;"></div>
                                    <span>Present</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 20px; height: 20px; background-color: #fee2e2; border-radius: 4px;"></div>
                                    <span>Absent</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 20px; height: 20px; background-color: #fef3c7; border-radius: 4px;"></div>
                                    <span>Half Day</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 20px; height: 20px; background-color: #e0e7ff; border-radius: 4px;"></div>
                                    <span>Leave</span>
                                </div>
                            </div>
                            
                            <div class="attendance-calendar">
                                <div class="calendar-day weekday">Mon</div>
                                <div class="calendar-day weekday">Tue</div>
                                <div class="calendar-day weekday">Wed</div>
                                <div class="calendar-day weekday">Thu</div>
                                <div class="calendar-day weekday">Fri</div>
                                <div class="calendar-day weekday">Sat</div>
                                <div class="calendar-day weekday">Sun</div>
                                
                                <?php
                                $first_day = date('N', strtotime(date('Y-m-01')));
                                for ($i = 1; $i < $first_day; $i++) {
                                    echo '<div class="calendar-day"></div>';
                                }
                                
                                $days_in_month = date('t');
                                for ($day = 1; $day <= $days_in_month; $day++) {
                                    $date = date('Y-m-' . sprintf('%02d', $day));
                                    $attendance = array_filter($my_attendance, function($a) use ($date) {
                                        return $a['date'] === $date;
                                    });
                                    $attendance = reset($attendance);
                                    
                                    $status_class = '';
                                    if ($attendance) {
                                        $status_class = $attendance['status'];
                                    }
                                    
                                    $today_class = ($date === $today) ? 'today' : '';
                                    
                                    echo '<div class="calendar-day ' . $status_class . ' ' . $today_class . '">' . $day . '</div>';
                                }
                                ?>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Day</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
                                        <th>Total Hours</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach(array_slice($my_attendance, -10) as $attendance): 
                                        $status_class = 'status-' . $attendance['status'];
                                    ?>
                                    <tr>
                                        <td><?php echo date('M j, Y', strtotime($attendance['date'])); ?></td>
                                        <td><?php echo date('D', strtotime($attendance['date'])); ?></td>
                                        <td><?php echo $attendance['check_in'] ? date('h:i A', strtotime($attendance['check_in'])) : '--:--'; ?></td>
                                        <td><?php echo $attendance['check_out'] ? date('h:i A', strtotime($attendance['check_out'])) : '--:--'; ?></td>
                                        <td><?php echo $attendance['total_hours']; ?> hrs</td>
                                        <td><span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst(str_replace('-', ' ', $attendance['status'])); ?></span></td>
                                        <td><?php echo htmlspecialchars($attendance['remarks']); ?></td>
                                        <td>
                                            <button class="btn btn-icon btn-sm btn-outline edit-attendance-btn" data-date="<?php echo $attendance['date']; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Leave Management Page -->
                <div class="page-content" id="leavePage">
                    <div class="section-card">
                        <div class="section-header">
                            <h3>Leave Management</h3>
                            <button class="btn btn-primary" id="applyNewLeaveBtn">
                                <i class="fas fa-plus"></i> Apply for Leave
                            </button>
                        </div>
                        
                        <div class="stats-grid" style="margin-bottom: 2rem;">
                            <div class="stat-card">
                                <div class="stat-icon" style="background-color: var(--secondary);">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>Available Leaves</h3>
                                    <div class="stat-value"><?php echo $employee_stats['remaining_leaves']; ?></div>
                                    <div class="stat-detail">Annual Leaves: 30</div>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon" style="background-color: var(--primary);">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>Leaves Taken</h3>
                                    <div class="stat-value"><?php echo $employee_stats['leaves_taken']; ?></div>
                                    <div class="stat-detail">This year</div>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon" style="background-color: var(--warning);">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>Pending Requests</h3>
                                    <div class="stat-value">
                                        <?php 
                                        $pending = array_filter($my_leave_requests, function($l) {
                                            return $l['status'] === 'pending';
                                        });
                                        echo count($pending);
                                        ?>
                                    </div>
                                    <div class="stat-detail">Awaiting approval</div>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon" style="background-color: var(--info);">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>Leave Balance</h3>
                                    <div class="stat-value">
                                        <?php echo $employee_stats['remaining_leaves'] - count($pending); ?>
                                    </div>
                                    <div class="stat-detail">After pending requests</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Applied On</th>
                                        <th>Duration</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($my_leave_requests as $leave): 
                                        $status_class = 'status-' . $leave['status'];
                                    ?>
                                    <tr>
                                        <td><?php echo $leave['leave_type_name']; ?></td>
                                        <td><?php echo date('M j, Y', strtotime($leave['start_date'])); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($leave['end_date'])); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($leave['applied_date'])); ?></td>
                                        <td><?php echo $leave['total_days']; ?> day(s)</td>
                                        <td><?php echo htmlspecialchars(substr($leave['reason'], 0, 30)) . (strlen($leave['reason']) > 30 ? '...' : ''); ?></td>
                                        <td><span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst($leave['status']); ?></span></td>
                                        <td>
                                            <div style="display: flex; gap: 5px;">
                                                <button class="btn btn-icon btn-sm btn-outline view-leave-details-btn" data-id="<?php echo $leave['id']; ?>" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <?php if($leave['status'] == 'pending'): ?>
                                                <button class="btn btn-icon btn-sm btn-outline cancel-leave-request-btn" data-id="<?php echo $leave['id']; ?>" title="Cancel Request">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Payroll Page -->
                <div class="page-content" id="payrollPage">
                    <div class="section-card">
                        <div class="section-header">
                            <h3>Payroll & Salary</h3>
                            <div class="table-actions">
                                <select class="form-control" id="payrollYear" style="width: auto;">
                                    <option value="2024">2024</option>
                                    <option value="2023">2023</option>
                                </select>
                                <button class="btn btn-outline">
                                    <i class="fas fa-download"></i> Export All
                                </button>
                            </div>
                        </div>
                        
                        <div class="stats-grid" style="margin-bottom: 2rem;">
                            <div class="stat-card">
                                <div class="stat-icon" style="background-color: var(--secondary);">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>Net Salary</h3>
                                    <div class="stat-value">₹<?php echo number_format($my_payroll[0]['net_salary'], 0); ?></div>
                                    <div class="stat-detail">Per month</div>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon" style="background-color: var(--primary);">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>Next Payday</h3>
                                    <div class="stat-value"><?php echo date('M j', strtotime($employee_stats['next_payday'])); ?></div>
                                    <div class="stat-detail">
                                        <?php 
                                        $days_remaining = date_diff(date_create(date('Y-m-d')), date_create($employee_stats['next_payday']))->format('%a');
                                        echo $days_remaining . ' days remaining';
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon" style="background-color: var(--warning);">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>Tax Deduction</h3>
                                    <div class="stat-value">₹<?php echo number_format($my_payroll[0]['income_tax'], 0); ?></div>
                                    <div class="stat-detail">Per month</div>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon" style="background-color: var(--info);">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>Annual Salary</h3>
                                    <div class="stat-value">₹<?php echo number_format($employee['basic_salary'] * 12, 0); ?></div>
                                    <div class="stat-detail">Gross annual</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Basic Salary</th>
                                        <th>Allowances</th>
                                        <th>Deductions</th>
                                        <th>Net Salary</th>
                                        <th>Payment Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($my_payroll as $pay): 
                                        $status_class = 'status-' . $pay['payment_status'];
                                    ?>
                                    <tr>
                                        <td><?php echo date('F Y', strtotime($pay['month_year'] . '-01')); ?></td>
                                        <td>₹<?php echo number_format($pay['basic_salary'], 2); ?></td>
                                        <td>₹<?php echo number_format($pay['hra'] + $pay['da'] + $pay['conveyance'] + $pay['medical'] + $pay['special_allowance'] + $pay['other_allowances'], 2); ?></td>
                                        <td>₹<?php echo number_format($pay['total_deductions'], 2); ?></td>
                                        <td><strong>₹<?php echo number_format($pay['net_salary'], 2); ?></strong></td>
                                        <td><?php echo date('M j, Y', strtotime($pay['payment_date'])); ?></td>
                                        <td><span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst($pay['payment_status']); ?></span></td>
                                        <td>
                                            <div style="display: flex; gap: 5px;">
                                                <button class="btn btn-icon btn-sm btn-outline view-payslip-btn" data-id="<?php echo $pay['id']; ?>" title="View Payslip">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-icon btn-sm btn-outline download-payslip-btn" data-id="<?php echo $pay['id']; ?>" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                                <button class="btn btn-icon btn-sm btn-outline print-payslip-btn" data-id="<?php echo $pay['id']; ?>" title="Print">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Documents Page -->
                <div class="page-content" id="documentsPage">
                    <div class="section-card">
                        <div class="section-header">
                            <h3>My Documents</h3>
                            <button class="btn btn-primary" id="uploadDocumentBtn">
                                <i class="fas fa-upload"></i> Upload Document
                            </button>
                        </div>
                        
                        <div style="margin-bottom: 2rem;">
                            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                                <button class="btn btn-outline active" data-category="all">
                                    All Documents
                                </button>
                                <button class="btn btn-outline" data-category="Employment">
                                    Employment
                                </button>
                                <button class="btn btn-outline" data-category="Identity">
                                    Identity
                                </button>
                                <button class="btn btn-outline" data-category="Payroll">
                                    Payroll
                                </button>
                                <button class="btn btn-outline" data-category="Other">
                                    Other
                                </button>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Document Name</th>
                                        <th>Type</th>
                                        <th>Category</th>
                                        <th>Upload Date</th>
                                        <th>Size</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($my_documents as $doc): ?>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <i class="fas fa-file-pdf" style="color: #ef4444; font-size: 1.2rem;"></i>
                                                <div>
                                                    <div style="font-weight: 600;"><?php echo $doc['name']; ?></div>
                                                    <div style="font-size: 0.85rem; color: var(--gray);">PDF Document</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo $doc['type']; ?></td>
                                        <td>
                                            <span class="status-badge" style="background: #e0e7ff; color: var(--primary);">
                                                <?php echo $doc['category']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M j, Y', strtotime($doc['upload_date'])); ?></td>
                                        <td><?php echo $doc['size']; ?></td>
                                        <td>
                                            <div style="display: flex; gap: 5px;">
                                                <button class="btn btn-icon btn-sm btn-outline view-doc-btn" data-id="<?php echo $doc['id']; ?>" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-icon btn-sm btn-outline download-doc-btn" data-id="<?php echo $doc['id']; ?>" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                                <button class="btn btn-icon btn-sm btn-outline delete-doc-btn" data-id="<?php echo $doc['id']; ?>" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Holidays Page -->
                <div class="page-content" id="holidaysPage">
                    <div class="section-card">
                        <div class="section-header">
                            <h3>Company Holidays - 2024</h3>
                            <button class="btn btn-outline">
                                <i class="fas fa-download"></i> Download Calendar
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Day</th>
                                        <th>Holiday Name</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($holidays as $holiday): ?>
                                    <tr>
                                        <td><?php echo date('F j, Y', strtotime($holiday['date'])); ?></td>
                                        <td><?php echo date('l', strtotime($holiday['date'])); ?></td>
                                        <td style="font-weight: 600;"><?php echo $holiday['name']; ?></td>
                                        <td>
                                            <span class="status-badge" style="background: #fef3c7; color: var(--warning);">
                                                <?php echo $holiday['type']; ?>
                                            </span>
                                        </td>
                                        <td>Company holiday - Office will remain closed</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div style="margin-top: 2rem; padding: 1.5rem; background: #f0f9ff; border-radius: 8px;">
                            <h4 style="margin-bottom: 1rem; color: var(--primary);">
                                <i class="fas fa-info-circle"></i> Holiday Policy
                            </h4>
                            <ul style="color: var(--gray); line-height: 1.6; padding-left: 1.5rem;">
                                <li>All national holidays are paid holidays</li>
                                <li>Festival holidays may vary based on regional offices</li>
                                <li>Additional leaves can be applied through the leave management system</li>
                                <li>For any queries regarding holidays, please contact HR department</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- All Modals -->
    
    <!-- Check In/Out Modal -->
    <div class="modal-overlay" id="checkInOutModal">
        <div class="modal">
            <div class="modal-header">
                <h3 id="checkModalTitle">Check In</h3>
                <button class="modal-close" data-modal="checkInOutModal">&times;</button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div class="timer-display" id="modalTimer">00:00:00</div>
                    <p style="color: var(--gray); font-size: 0.9rem;">Current time will be recorded</p>
                    <p style="margin-top: 0.5rem; font-weight: 600; color: var(--primary);">
                        <?php echo date('l, F j, Y'); ?>
                    </p>
                </div>
                <div class="form-group">
                    <label>Location (Optional)</label>
                    <input type="text" class="form-control" id="attendanceLocation" placeholder="Enter your location">
                </div>
                <div class="form-group">
                    <label>Remarks (Optional)</label>
                    <textarea class="form-control" id="attendanceRemarks" rows="3" placeholder="Add any remarks..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" data-modal="checkInOutModal">Cancel</button>
                <button class="btn btn-primary" id="confirmAttendance">Confirm</button>
            </div>
        </div>
    </div>

    <!-- Apply Leave Modal -->
    <div class="modal-overlay" id="leaveApplyModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Apply for Leave</h3>
                <button class="modal-close" data-modal="leaveApplyModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="leaveApplyForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Leave Type *</label>
                            <select class="form-control" id="leaveType" required>
                                <option value="">Select Leave Type</option>
                                <?php foreach($leave_types as $type): ?>
                                <option value="<?php echo $type['name']; ?>"><?php echo $type['name']; ?> (<?php echo $type['days_allowed']; ?> days/year)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Available Balance</label>
                            <input type="text" class="form-control" value="<?php echo $employee_stats['remaining_leaves']; ?> days" readonly style="background: #f8fafc;">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>From Date *</label>
                            <input type="date" class="form-control" id="leaveFrom" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group">
                            <label>To Date *</label>
                            <input type="date" class="form-control" id="leaveTo" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Number of Days</label>
                        <input type="number" class="form-control" id="leaveDays" readonly style="background: #f8fafc;">
                    </div>
                    <div class="form-group">
                        <label>Reason *</label>
                        <textarea class="form-control" id="leaveReason" rows="4" required placeholder="Please provide a detailed reason for your leave..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Contact During Leave</label>
                        <input type="text" class="form-control" id="leaveContact" placeholder="Phone number where you can be reached">
                    </div>
                    <div class="form-group">
                        <label>Handover Notes (Optional)</label>
                        <textarea class="form-control" id="leaveHandover" rows="3" placeholder="Any important work that needs handover..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" data-modal="leaveApplyModal">Cancel</button>
                <button class="btn btn-primary" id="submitLeaveRequest">Submit Request</button>
            </div>
        </div>
    </div>

    <!-- View Payslip Modal -->
    <div class="modal-overlay" id="payslipModal">
        <div class="modal">
            <div class="modal-header">
                <h3 id="payslipTitle">Salary Details - January 2024</h3>
                <button class="modal-close" data-modal="payslipModal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="payslipContent">
                    <!-- Payslip content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" data-modal="payslipModal">Close</button>
                <button class="btn btn-primary" id="downloadPayslipBtn">
                    <i class="fas fa-download"></i> Download
                </button>
                <button class="btn btn-outline" id="printPayslipBtn">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal-overlay" id="editProfileModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Edit Profile</h3>
                <button class="modal-close" data-modal="editProfileModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm">
                    <div class="form-group">
                        <label>Profile Picture</label>
                        <div class="file-upload" id="profileUpload">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to upload profile picture</p>
                            <input type="file" id="profilePicture" accept="image/*" style="display: none;">
                        </div>
                        <div id="profilePreview" style="margin-top: 1rem; display: none;">
                            <img id="previewImage" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control" id="editFirstName" value="<?php echo htmlspecialchars($employee['first_name']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control" id="editLastName" value="<?php echo htmlspecialchars($employee['last_name']); ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" class="form-control" id="editPhone" value="<?php echo htmlspecialchars($employee['phone']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" class="form-control" id="editEmail" value="<?php echo htmlspecialchars($employee['email']); ?>" readonly style="background: #f8fafc;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea class="form-control" id="editAddress" rows="3"><?php echo htmlspecialchars($employee['address']); ?></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Emergency Contact</label>
                            <input type="text" class="form-control" id="editEmergencyContact" value="<?php echo htmlspecialchars($employee['emergency_contact']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" class="form-control" id="editDOB" value="<?php echo $employee['date_of_birth']; ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" data-modal="editProfileModal">Cancel</button>
                <button class="btn btn-primary" id="saveProfileBtn">Save Changes</button>
            </div>
        </div>
    </div>

    <!-- Leave Details Modal -->
    <div class="modal-overlay" id="leaveDetailsModal">
        <div class="modal">
            <div class="modal-header">
                <h3 id="leaveDetailsTitle">Leave Details</h3>
                <button class="modal-close" data-modal="leaveDetailsModal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="leaveDetailsContent">
                    <!-- Leave details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" data-modal="leaveDetailsModal">Close</button>
            </div>
        </div>
    </div>

    <!-- Upload Document Modal -->
    <div class="modal-overlay" id="uploadDocModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Upload Document</h3>
                <button class="modal-close" data-modal="uploadDocModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="uploadDocForm">
                    <div class="form-group">
                        <label>Document Type *</label>
                        <select class="form-control" id="docType" required>
                            <option value="">Select Type</option>
                            <option value="PAN Card">PAN Card</option>
                            <option value="Aadhar Card">Aadhar Card</option>
                            <option value="Passport">Passport</option>
                            <option value="Driving License">Driving License</option>
                            <option value="Degree Certificate">Degree Certificate</option>
                            <option value="Experience Letter">Experience Letter</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Category *</label>
                        <select class="form-control" id="docCategory" required>
                            <option value="Identity">Identity</option>
                            <option value="Employment">Employment</option>
                            <option value="Education">Education</option>
                            <option value="Payroll">Payroll</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" id="docDescription" placeholder="Brief description of the document">
                    </div>
                    <div class="form-group">
                        <label>Upload File *</label>
                        <div class="file-upload" id="docUpload">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to upload document (PDF, JPEG, PNG)</p>
                            <p style="font-size: 0.9rem; color: var(--gray);">Max file size: 5MB</p>
                            <input type="file" id="documentFile" accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
                        </div>
                        <div id="filePreview" style="margin-top: 1rem; display: none;">
                            <p id="fileName" style="font-weight: 600;"></p>
                            <p id="fileSize" style="color: var(--gray); font-size: 0.9rem;"></p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" data-modal="uploadDocModal">Cancel</button>
                <button class="btn btn-primary" id="saveDocumentBtn">Upload Document</button>
            </div>
        </div>
    </div>

    <!-- View Document Modal -->
    <div class="modal-overlay" id="viewDocModal">
        <div class="modal">
            <div class="modal-header">
                <h3 id="docViewTitle">Document Preview</h3>
                <button class="modal-close" data-modal="viewDocModal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="docViewContent" style="text-align: center;">
                    <!-- Document preview will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" data-modal="viewDocModal">Close</button>
                <button class="btn btn-primary" id="downloadDocBtn">
                    <i class="fas fa-download"></i> Download
                </button>
            </div>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal-overlay" id="logoutModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Confirm Logout</h3>
                <button class="modal-close" data-modal="logoutModal">&times;</button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; padding: 2rem;">
                    <i class="fas fa-sign-out-alt" style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;"></i>
                    <h3 style="margin-bottom: 0.5rem;">Are you sure you want to logout?</h3>
                    <p style="color: var(--gray);">You will be redirected to the login page</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" data-modal="logoutModal">Cancel</button>
                <button class="btn btn-danger" id="confirmLogoutBtn">Yes, Logout</button>
            </div>
        </div>
    </div>

    <script>
        // Static data storage
        const employeeData = <?php echo json_encode($employee); ?>;
        const myAttendance = <?php echo json_encode($my_attendance); ?>;
        const myLeaves = <?php echo json_encode($my_leave_requests); ?>;
        const myPayroll = <?php echo json_encode($my_payroll); ?>;
        const myDocuments = <?php echo json_encode($my_documents); ?>;
        const holidays = <?php echo json_encode($holidays); ?>;
        const notifications = <?php echo json_encode($notifications); ?>;
        const leaveTypes = <?php echo json_encode($leave_types); ?>;
        
        // State variables
        let isCheckedIn = false;
        let checkInTime = null;
        let workingTimer = null;
        let currentPage = 'dashboard';
        let pendingNotifications = notifications.filter(n => !n.read).length;
        
        // DOM Ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeApp();
            setupEventListeners();
            updateNotificationsDisplay();
            updateWorkingTimer();
            setInterval(updateClock, 1000);
            setInterval(updateWorkingTimer, 1000);
        });
        
        function initializeApp() {
            // Check if already checked in today
            const todayRecord = myAttendance.find(a => a.date === '<?php echo $today; ?>');
            if (todayRecord && todayRecord.check_in) {
                isCheckedIn = true;
                checkInTime = todayRecord.check_in;
                document.getElementById('checkActionText').textContent = 'Check Out';
                if (todayRecord.check_out) {
                    document.getElementById('checkInOutBtn').disabled = true;
                    document.getElementById('checkActionText').textContent = 'Completed';
                }
            }
            
            // Set current time
            updateClock();
            
            // Initialize notification badge
            document.getElementById('notificationCount').textContent = pendingNotifications;
        }
        
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
            document.getElementById('currentTimeDisplay').textContent = timeStr;
            document.getElementById('modalTimer').textContent = now.toLocaleTimeString('en-US', { hour12: false });
        }
        
        function updateWorkingTimer() {
            if (isCheckedIn && checkInTime && !document.getElementById('checkInOutBtn').disabled) {
                const checkIn = new Date('<?php echo $today; ?>T' + checkInTime);
                const now = new Date();
                const diff = now - checkIn;
                const hours = Math.floor(diff / 3600000);
                const minutes = Math.floor((diff % 3600000) / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);
                document.getElementById('workingTimer').textContent = 
                    `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
        }
        
        function setupEventListeners() {
            // Sidebar navigation
            document.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('click', function() {
                    const page = this.getAttribute('data-page');
                    navigateToPage(page);
                });
            });
            
            // Quick action cards
            document.querySelectorAll('.quick-action-card').forEach(card => {
                card.addEventListener('click', function() {
                    const action = this.getAttribute('data-action');
                    handleQuickAction(action);
                });
            });
            
            // Check in/out button
            document.getElementById('checkInOutBtn').addEventListener('click', function() {
                if (this.disabled) return;
                openCheckInOutModal();
            });
            
            // Mark attendance button
            document.getElementById('markAttendanceBtn').addEventListener('click', function() {
                openCheckInOutModal();
            });
            
            // Apply leave buttons
            document.getElementById('applyNewLeaveBtn')?.addEventListener('click', function() {
                openLeaveApplyModal();
            });
            
            // Edit profile button
            document.getElementById('editProfileBtn')?.addEventListener('click', function() {
                openEditProfileModal();
            });
            
            // Upload document button
            document.getElementById('uploadDocumentBtn')?.addEventListener('click', function() {
                openUploadDocumentModal();
            });
            
            // View all leaves button
            document.getElementById('viewAllLeavesBtn')?.addEventListener('click', function() {
                navigateToPage('leave');
            });
            
            // Document category filters
            document.querySelectorAll('[data-category]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const category = this.getAttribute('data-category');
                    filterDocuments(category);
                });
            });
            
            // Notification button
            document.getElementById('notificationBtn').addEventListener('click', function() {
                toggleNotificationPanel();
            });
            
            // Mark all notifications as read
            document.getElementById('markAllRead')?.addEventListener('click', function() {
                markAllNotificationsRead();
            });
            
            // Modal close buttons
            document.querySelectorAll('.modal-close, [data-modal]').forEach(btn => {
                if (btn.hasAttribute('data-modal')) {
                    btn.addEventListener('click', function() {
                        const modalId = this.getAttribute('data-modal');
                        closeModal(modalId);
                    });
                }
            });
            
            // Close modals when clicking outside
            document.querySelectorAll('.modal-overlay').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        const modalId = this.id;
                        closeModal(modalId);
                    }
                });
            });
            
            // Check in/out confirmation
            document.getElementById('confirmAttendance')?.addEventListener('click', function() {
                performCheckInOut();
            });
            
            // Submit leave request
            document.getElementById('submitLeaveRequest')?.addEventListener('click', function() {
                submitLeaveRequest();
            });
            
            // Save profile changes
            document.getElementById('saveProfileBtn')?.addEventListener('click', function() {
                saveProfileChanges();
            });
            
            // Upload document
            document.getElementById('saveDocumentBtn')?.addEventListener('click', function() {
                uploadDocument();
            });
            
            // Logout buttons
            document.getElementById('sidebarLogoutBtn')?.addEventListener('click', function() {
                openLogoutModal();
            });
            
            // Confirm logout
            document.getElementById('confirmLogoutBtn')?.addEventListener('click', function() {
                performLogout();
            });
            
            // Menu toggle for mobile
            document.getElementById('menuToggle')?.addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
            });
            
            // File upload preview
            document.getElementById('profileUpload')?.addEventListener('click', function() {
                document.getElementById('profilePicture').click();
            });
            
            document.getElementById('profilePicture')?.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('previewImage').src = e.target.result;
                        document.getElementById('profilePreview').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            document.getElementById('docUpload')?.addEventListener('click', function() {
                document.getElementById('documentFile').click();
            });
            
            document.getElementById('documentFile')?.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    document.getElementById('fileName').textContent = file.name;
                    document.getElementById('fileSize').textContent = formatFileSize(file.size);
                    document.getElementById('filePreview').style.display = 'block';
                }
            });
            
            // Leave form date calculations
            document.getElementById('leaveFrom')?.addEventListener('change', calculateLeaveDays);
            document.getElementById('leaveTo')?.addEventListener('change', calculateLeaveDays);
            
            // Event delegation for dynamic buttons
            document.addEventListener('click', function(e) {
                // View leave details
                if (e.target.closest('.view-leave-btn, .view-leave-details-btn')) {
                    const btn = e.target.closest('.view-leave-btn, .view-leave-details-btn');
                    const leaveId = btn.getAttribute('data-id');
                    viewLeaveDetails(leaveId);
                }
                
                // Cancel leave request
                if (e.target.closest('.cancel-leave-btn, .cancel-leave-request-btn')) {
                    const btn = e.target.closest('.cancel-leave-btn, .cancel-leave-request-btn');
                    const leaveId = btn.getAttribute('data-id');
                    cancelLeaveRequest(leaveId);
                }
                
                // View payslip
                if (e.target.closest('.view-payslip-btn')) {
                    const btn = e.target.closest('.view-payslip-btn');
                    const payslipId = btn.getAttribute('data-id');
                    viewPayslip(payslipId);
                }
                
                // Download payslip
                if (e.target.closest('.download-payslip-btn')) {
                    const btn = e.target.closest('.download-payslip-btn');
                    const payslipId = btn.getAttribute('data-id');
                    downloadPayslip(payslipId);
                }
                
                // Print payslip
                if (e.target.closest('.print-payslip-btn')) {
                    const btn = e.target.closest('.print-payslip-btn');
                    const payslipId = btn.getAttribute('data-id');
                    printPayslip(payslipId);
                }
                
                // View document
                if (e.target.closest('.view-doc-btn')) {
                    const btn = e.target.closest('.view-doc-btn');
                    const docId = btn.getAttribute('data-id');
                    viewDocument(docId);
                }
                
                // Download document
                if (e.target.closest('.download-doc-btn')) {
                    const btn = e.target.closest('.download-doc-btn');
                    const docId = btn.getAttribute('data-id');
                    downloadDocument(docId);
                }
                
                // Delete document
                if (e.target.closest('.delete-doc-btn')) {
                    const btn = e.target.closest('.delete-doc-btn');
                    const docId = btn.getAttribute('data-id');
                    deleteDocument(docId);
                }
                
                // Edit attendance
                if (e.target.closest('.edit-attendance-btn')) {
                    const btn = e.target.closest('.edit-attendance-btn');
                    const date = btn.getAttribute('data-date');
                    editAttendance(date);
                }
            });
            
            // Download payslip from modal
            document.getElementById('downloadPayslipBtn')?.addEventListener('click', function() {
                const payslipId = this.getAttribute('data-payslip-id');
                if (payslipId) downloadPayslip(payslipId);
            });
            
            // Print payslip from modal
            document.getElementById('printPayslipBtn')?.addEventListener('click', function() {
                const payslipId = this.getAttribute('data-payslip-id');
                if (payslipId) printPayslip(payslipId);
            });
            
            // Download document from modal
            document.getElementById('downloadDocBtn')?.addEventListener('click', function() {
                const docId = this.getAttribute('data-doc-id');
                if (docId) downloadDocument(docId);
            });
        }
        
        function navigateToPage(page) {
            // Update sidebar
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('data-page') === page) {
                    item.classList.add('active');
                }
            });
            
            // Update page content
            document.querySelectorAll('.page-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(page + 'Page').classList.add('active');
            
            // Update page title
            const pageTitles = {
                dashboard: 'Employee Dashboard',
                profile: 'My Profile',
                attendance: 'Attendance',
                leave: 'Leave Management',
                payroll: 'Payroll',
                documents: 'Documents',
                holidays: 'Holidays'
            };
            
            document.querySelector('.header-left h1').innerHTML = 
                `<i class="fas fa-${getPageIcon(page)}"></i> ${pageTitles[page]}`;
            
            currentPage = page;
            
            // Close sidebar on mobile
            if (window.innerWidth <= 768) {
                document.getElementById('sidebar').classList.remove('active');
            }
        }
        
        function getPageIcon(page) {
            const icons = {
                dashboard: 'tachometer-alt',
                profile: 'user',
                attendance: 'calendar-check',
                leave: 'calendar-minus',
                payroll: 'file-invoice-dollar',
                documents: 'file-alt',
                holidays: 'umbrella-beach'
            };
            return icons[page] || 'circle';
        }
        
        function handleQuickAction(action) {
            switch(action) {
                case 'checkInOut':
                    openCheckInOutModal();
                    break;
                case 'applyLeave':
                    openLeaveApplyModal();
                    break;
                case 'viewPayslip':
                    viewPayslip(myPayroll[0].id);
                    break;
                case 'updateProfile':
                    openEditProfileModal();
                    break;
            }
        }
        
        function openCheckInOutModal() {
            const modal = document.getElementById('checkInOutModal');
            const title = document.getElementById('checkModalTitle');
            
            if (isCheckedIn) {
                title.textContent = 'Check Out';
            } else {
                title.textContent = 'Check In';
            }
            
            openModal('checkInOutModal');
        }
        
        function openLeaveApplyModal() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('leaveFrom').value = today;
            document.getElementById('leaveTo').value = today;
            document.getElementById('leaveDays').value = 1;
            document.getElementById('leaveReason').value = '';
            document.getElementById('leaveContact').value = '';
            document.getElementById('leaveHandover').value = '';
            
            openModal('leaveApplyModal');
        }
        
        function openEditProfileModal() {
            openModal('editProfileModal');
        }
        
        function openUploadDocumentModal() {
            openModal('uploadDocModal');
        }
        
        function openLogoutModal() {
            openModal('logoutModal');
        }
        
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }
        
        function calculateLeaveDays() {
            const from = document.getElementById('leaveFrom').value;
            const to = document.getElementById('leaveTo').value;
            
            if (from && to) {
                const fromDate = new Date(from);
                const toDate = new Date(to);
                
                if (toDate < fromDate) {
                    document.getElementById('leaveTo').value = from;
                    document.getElementById('leaveDays').value = 1;
                    return;
                }
                
                // Calculate working days (excluding weekends)
                let days = 0;
                let current = new Date(fromDate);
                
                while (current <= toDate) {
                    const day = current.getDay();
                    if (day !== 0 && day !== 6) { // Exclude Sunday (0) and Saturday (6)
                        days++;
                    }
                    current.setDate(current.getDate() + 1);
                }
                
                document.getElementById('leaveDays').value = days;
            }
        }
        
        function performCheckInOut() {
            const remarks = document.getElementById('attendanceRemarks').value;
            const location = document.getElementById('attendanceLocation').value;
            const now = new Date();
            
            if (isCheckedIn) {
                // Check out
                showNotification('Attendance Marked', `Checked out at ${now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`, 'success');
                document.getElementById('checkActionText').textContent = 'Completed';
                document.getElementById('checkInOutBtn').disabled = true;
                isCheckedIn = false;
            } else {
                // Check in
                checkInTime = now.toTimeString().split(' ')[0];
                showNotification('Attendance Marked', `Checked in at ${now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`, 'success');
                document.getElementById('checkActionText').textContent = 'Check Out';
                isCheckedIn = true;
            }
            
            closeModal('checkInOutModal');
            
            // Reset form
            document.getElementById('attendanceRemarks').value = '';
            document.getElementById('attendanceLocation').value = '';
        }
        
        function submitLeaveRequest() {
            const leaveType = document.getElementById('leaveType').value;
            const leaveFrom = document.getElementById('leaveFrom').value;
            const leaveTo = document.getElementById('leaveTo').value;
            const leaveDays = document.getElementById('leaveDays').value;
            const leaveReason = document.getElementById('leaveReason').value;
            const leaveContact = document.getElementById('leaveContact').value;
            const leaveHandover = document.getElementById('leaveHandover').value;
            
            if (!leaveType || !leaveFrom || !leaveTo || !leaveReason) {
                showNotification('Validation Error', 'Please fill all required fields!', 'error');
                return;
            }
            
            if (parseInt(leaveDays) > employeeData.stats.remaining_leaves) {
                showNotification('Insufficient Leave Balance', 'You do not have enough leave balance!', 'error');
                return;
            }
            
            // In a real app, this would be an AJAX call
            showNotification('Leave Request Submitted', `Your ${leaveType} request has been submitted successfully!`, 'success');
            
            closeModal('leaveApplyModal');
            
            // Reset form
            document.getElementById('leaveApplyForm').reset();
            document.getElementById('leaveDays').value = '';
        }
        
        function saveProfileChanges() {
            const firstName = document.getElementById('editFirstName').value;
            const lastName = document.getElementById('editLastName').value;
            const phone = document.getElementById('editPhone').value;
            const address = document.getElementById('editAddress').value;
            const emergencyContact = document.getElementById('editEmergencyContact').value;
            const dob = document.getElementById('editDOB').value;
            
            // In a real app, this would be an AJAX call
            showNotification('Profile Updated', 'Your profile has been updated successfully!', 'success');
            
            closeModal('editProfileModal');
        }
        
        function uploadDocument() {
            const docType = document.getElementById('docType').value;
            const docCategory = document.getElementById('docCategory').value;
            const docDescription = document.getElementById('docDescription').value;
            const fileInput = document.getElementById('documentFile');
            
            if (!docType || !docCategory || !fileInput.files[0]) {
                showNotification('Validation Error', 'Please fill all required fields and select a file!', 'error');
                return;
            }
            
            // In a real app, this would be an AJAX call with file upload
            showNotification('Document Uploaded', 'Your document has been uploaded successfully!', 'success');
            
            closeModal('uploadDocModal');
            
            // Reset form
            document.getElementById('uploadDocForm').reset();
            document.getElementById('filePreview').style.display = 'none';
        }
        
        function viewLeaveDetails(leaveId) {
            const leave = myLeaves.find(l => l.id == leaveId);
            if (!leave) {
                showNotification('Error', 'Leave request not found!', 'error');
                return;
            }
            
            const content = `
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <p style="color: var(--gray); margin-bottom: 0.5rem;">Leave Type</p>
                        <p style="font-weight: 600;">${leave.leave_type_name}</p>
                    </div>
                    <div>
                        <p style="color: var(--gray); margin-bottom: 0.5rem;">Status</p>
                        <span class="status-badge status-${leave.status}" style="font-size: 0.9rem;">
                            ${leave.status.charAt(0).toUpperCase() + leave.status.slice(1)}
                        </span>
                    </div>
                    <div>
                        <p style="color: var(--gray); margin-bottom: 0.5rem;">From Date</p>
                        <p style="font-weight: 600;">${new Date(leave.start_date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</p>
                    </div>
                    <div>
                        <p style="color: var(--gray); margin-bottom: 0.5rem;">To Date</p>
                        <p style="font-weight: 600;">${new Date(leave.end_date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</p>
                    </div>
                    <div>
                        <p style="color: var(--gray); margin-bottom: 0.5rem;">Duration</p>
                        <p style="font-weight: 600;">${leave.total_days} day(s)</p>
                    </div>
                    <div>
                        <p style="color: var(--gray); margin-bottom: 0.5rem;">Applied On</p>
                        <p style="font-weight: 600;">${new Date(leave.applied_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</p>
                    </div>
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <p style="color: var(--gray); margin-bottom: 0.5rem;">Reason</p>
                    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px;">
                        ${leave.reason}
                    </div>
                </div>
                
                ${leave.comments ? `
                <div style="margin-bottom: 1.5rem;">
                    <p style="color: var(--gray); margin-bottom: 0.5rem;">Comments from HR</p>
                    <div style="background: #f0f9ff; padding: 1rem; border-radius: 8px;">
                        ${leave.comments}
                    </div>
                </div>
                ` : ''}
                
                ${leave.approved_by ? `
                <div style="background: #f8fafc; padding: 1rem; border-radius: 8px;">
                    <p style="color: var(--gray); margin-bottom: 0.5rem;">Approval Details</p>
                    <p><strong>Approved by:</strong> ${leave.approved_by}</p>
                    ${leave.approved_date ? `<p><strong>Approved on:</strong> ${new Date(leave.approved_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>` : ''}
                </div>
                ` : ''}
            `;
            
            document.getElementById('leaveDetailsTitle').textContent = `Leave Request #${leave.id}`;
            document.getElementById('leaveDetailsContent').innerHTML = content;
            openModal('leaveDetailsModal');
        }
        
        function cancelLeaveRequest(leaveId) {
            if (!confirm('Are you sure you want to cancel this leave request?')) {
                return;
            }
            
            // In a real app, this would be an AJAX call
            showNotification('Leave Request Cancelled', 'Your leave request has been cancelled successfully!', 'success');
        }
        
        function viewPayslip(payslipId) {
            const payslip = myPayroll.find(p => p.id == payslipId);
            if (!payslip) {
                showNotification('Error', 'Payslip not found!', 'error');
                return;
            }
            
            const content = `
                <div style="margin-bottom: 2rem;">
                    <div style="text-align: center; margin-bottom: 1.5rem;">
                        <h3 style="color: var(--primary); margin-bottom: 0.5rem;">Salary Slip</h3>
                        <p style="color: var(--gray);">${new Date(payslip.month_year + '-01').toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}</p>
                        <p style="font-weight: 600;">${employeeData.first_name} ${employeeData.last_name}</p>
                        <p style="color: var(--gray); font-size: 0.9rem;">${employeeData.position} • ${employeeData.department}</p>
                    </div>
                    
                    <div class="salary-breakdown">
                        <div>
                            <h4 style="margin-bottom: 1rem; color: var(--dark);">Earnings</h4>
                            <div class="salary-item">
                                <span>Basic Salary</span>
                                <span>₹${payslip.basic_salary.toFixed(2)}</span>
                            </div>
                            <div class="salary-item">
                                <span>House Rent Allowance</span>
                                <span>₹${payslip.hra.toFixed(2)}</span>
                            </div>
                            <div class="salary-item">
                                <span>Dearness Allowance</span>
                                <span>₹${payslip.da.toFixed(2)}</span>
                            </div>
                            <div class="salary-item">
                                <span>Conveyance Allowance</span>
                                <span>₹${payslip.conveyance.toFixed(2)}</span>
                            </div>
                            <div class="salary-item">
                                <span>Medical Allowance</span>
                                <span>₹${payslip.medical.toFixed(2)}</span>
                            </div>
                            <div class="salary-item">
                                <span>Special Allowance</span>
                                <span>₹${payslip.special_allowance.toFixed(2)}</span>
                            </div>
                            <div class="salary-item">
                                <span>Other Allowances</span>
                                <span>₹${payslip.other_allowances.toFixed(2)}</span>
                            </div>
                            <div class="salary-item salary-total">
                                <span><strong>Total Earnings</strong></span>
                                <span><strong>₹${payslip.total_earnings.toFixed(2)}</strong></span>
                            </div>
                        </div>
                        
                        <div>
                            <h4 style="margin-bottom: 1rem; color: var(--dark);">Deductions</h4>
                            <div class="salary-item">
                                <span>Income Tax</span>
                                <span>₹${payslip.income_tax.toFixed(2)}</span>
                            </div>
                            <div class="salary-item">
                                <span>Professional Tax</span>
                                <span>₹${payslip.professional_tax.toFixed(2)}</span>
                            </div>
                            <div class="salary-item">
                                <span>Provident Fund</span>
                                <span>₹${payslip.pf_deduction.toFixed(2)}</span>
                            </div>
                            <div class="salary-item">
                                <span>Other Deductions</span>
                                <span>₹${payslip.other_deductions.toFixed(2)}</span>
                            </div>
                            <div class="salary-item salary-total">
                                <span><strong>Total Deductions</strong></span>
                                <span><strong>₹${payslip.total_deductions.toFixed(2)}</strong></span>
                            </div>
                        </div>
                    </div>
                    
                    <div style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; margin-top: 2rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <p style="color: var(--gray); margin-bottom: 0.5rem;">Net Salary Payable</p>
                                <h2 style="color: var(--primary);">₹${payslip.net_salary.toFixed(2)}</h2>
                            </div>
                            <div style="text-align: right;">
                                <p style="color: var(--gray); margin-bottom: 0.5rem;">Payment Status</p>
                                <span class="status-badge status-${payslip.payment_status}" style="font-size: 1rem;">
                                    ${payslip.payment_status.charAt(0).toUpperCase() + payslip.payment_status.slice(1)}
                                </span>
                                <p style="margin-top: 0.5rem; color: var(--gray); font-size: 0.9rem;">
                                    Paid on: ${new Date(payslip.payment_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}
                                </p>
                                <p style="color: var(--gray); font-size: 0.9rem;">
                                    Mode: ${payslip.payment_mode}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('payslipTitle').textContent = `Payslip - ${new Date(payslip.month_year + '-01').toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}`;
            document.getElementById('payslipContent').innerHTML = content;
            document.getElementById('downloadPayslipBtn').setAttribute('data-payslip-id', payslipId);
            document.getElementById('printPayslipBtn').setAttribute('data-payslip-id', payslipId);
            openModal('payslipModal');
        }
        
        function downloadPayslip(payslipId) {
            const payslip = myPayroll.find(p => p.id == payslipId);
            if (payslip) {
                showNotification('Download Started', `Downloading payslip for ${payslip.month_year}...`, 'success');
                // In a real app, this would trigger a file download
            }
        }
        
        function printPayslip(payslipId) {
            const payslip = myPayroll.find(p => p.id == payslipId);
            if (payslip) {
                showNotification('Print', `Printing payslip for ${payslip.month_year}...`, 'success');
                // In a real app, this would open print dialog
            }
        }
        
        function viewDocument(docId) {
            const doc = myDocuments.find(d => d.id == docId);
            if (!doc) {
                showNotification('Error', 'Document not found!', 'error');
                return;
            }
            
            const content = `
                <div style="margin-bottom: 2rem;">
                    <div style="text-align: center; margin-bottom: 1.5rem;">
                        <i class="fas fa-file-pdf" style="font-size: 4rem; color: #ef4444; margin-bottom: 1rem;"></i>
                        <h3 style="margin-bottom: 0.5rem;">${doc.name}</h3>
                        <p style="color: var(--gray);">${doc.type} • ${doc.category}</p>
                    </div>
                    
                    <div style="background: #f8fafc; padding: 1.5rem; border-radius: 8px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <p style="color: var(--gray); margin-bottom: 0.5rem;">Upload Date</p>
                                <p style="font-weight: 600;">${new Date(doc.upload_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                            </div>
                            <div>
                                <p style="color: var(--gray); margin-bottom: 0.5rem;">File Size</p>
                                <p style="font-weight: 600;">${doc.size}</p>
                            </div>
                        </div>
                        
                        <div style="border: 2px dashed var(--border); border-radius: 8px; padding: 3rem; text-align: center; background: white;">
                            <i class="fas fa-file-pdf" style="font-size: 3rem; color: #ef4444; margin-bottom: 1rem;"></i>
                            <p style="color: var(--gray);">Document Preview</p>
                            <p style="font-size: 0.9rem; color: var(--gray); margin-top: 0.5rem;">PDF documents can be viewed after download</p>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('docViewTitle').textContent = doc.name;
            document.getElementById('docViewContent').innerHTML = content;
            document.getElementById('downloadDocBtn').setAttribute('data-doc-id', docId);
            openModal('viewDocModal');
        }
        
        function downloadDocument(docId) {
            const doc = myDocuments.find(d => d.id == docId);
            if (doc) {
                showNotification('Download Started', `Downloading ${doc.name}...`, 'success');
                // In a real app, this would trigger a file download
            }
        }
        
        function deleteDocument(docId) {
            if (!confirm('Are you sure you want to delete this document?')) {
                return;
            }
            
            // In a real app, this would be an AJAX call
            showNotification('Document Deleted', 'Document has been deleted successfully!', 'success');
        }
        
        function editAttendance(date) {
            showNotification('Edit Attendance', `Editing attendance for ${new Date(date).toLocaleDateString()}`, 'info');
            // In a real app, this would open an edit modal
        }
        
        function filterDocuments(category) {
            document.querySelectorAll('[data-category]').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            showNotification('Filter Applied', `Showing ${category === 'all' ? 'all' : category} documents`, 'info');
            // In a real app, this would filter the table
        }
        
        function toggleNotificationPanel() {
            const panel = document.getElementById('notificationPanel');
            panel.classList.toggle('active');
        }
        
        function updateNotificationsDisplay() {
            const container = document.getElementById('notificationList');
            if (!container) return;
            
            container.innerHTML = notifications.map(notification => `
                <div class="notification-item ${notification.read ? '' : 'unread'}" data-id="${notification.id}">
                    <div class="notification-title">${notification.title}</div>
                    <div class="notification-message">${notification.message}</div>
                    <div class="notification-time">
                        ${formatTimeAgo(new Date(notification.date))}
                        ${notification.type ? `• ${notification.type}` : ''}
                    </div>
                </div>
            `).join('');
        }
        
        function markAllNotificationsRead() {
            pendingNotifications = 0;
            document.getElementById('notificationCount').textContent = '0';
            document.getElementById('notificationPanel').classList.remove('active');
            showNotification('Notifications', 'All notifications marked as read', 'success');
        }
        
        function performLogout() {
            showNotification('Logout', 'You have been logged out successfully!', 'success');
            setTimeout(() => {
                // In a real app, this would redirect to logout.php
                alert('Logout successful! Redirecting to login page...');
                // window.location.href = 'logout.php';
            }, 1000);
            closeModal('logoutModal');
        }
        
        function showNotification(title, message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification-toast notification-${type}`;
            notification.innerHTML = `
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-${getNotificationIcon(type)}" style="color: ${getNotificationColor(type)};"></i>
                    <div>
                        <div style="font-weight: 600;">${title}</div>
                        <div style="font-size: 0.9rem; color: var(--gray);">${message}</div>
                    </div>
                </div>
            `;
            
            // Add styles for toast
            notification.style.cssText = `
                position: fixed;
                top: 100px;
                right: 20px;
                background: white;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                z-index: 10000;
                min-width: 300px;
                max-width: 400px;
                animation: slideIn 0.3s ease;
                border-left: 4px solid ${getNotificationColor(type)};
            `;
            
            document.body.appendChild(notification);
            
            // Remove after 5 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }
        
        function getNotificationIcon(type) {
            const icons = {
                success: 'check-circle',
                error: 'exclamation-circle',
                warning: 'exclamation-triangle',
                info: 'info-circle'
            };
            return icons[type] || 'info-circle';
        }
        
        function getNotificationColor(type) {
            const colors = {
                success: '#10b981',
                error: '#ef4444',
                warning: '#f59e0b',
                info: '#3b82f6'
            };
            return colors[type] || '#3b82f6';
        }
        
        function formatTimeAgo(date) {
            const now = new Date();
            const diff = now - date;
            
            const minutes = Math.floor(diff / 60000);
            const hours = Math.floor(diff / 3600000);
            const days = Math.floor(diff / 86400000);
            
            if (minutes < 1) return 'Just now';
            if (minutes < 60) return `${minutes}m ago`;
            if (hours < 24) return `${hours}h ago`;
            if (days < 7) return `${days}d ago`;
            
            return date.toLocaleDateString();
        }
        
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>