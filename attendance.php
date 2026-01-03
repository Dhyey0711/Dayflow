<?php
// attendance.php - Attendance Management
session_start();
require_once '../includes/db.php';

// Check if user is logged in and is admin/hr
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'hr')) {
    header("Location: ../employee/dashboard.php");
    exit();
}

// Get today's date
$today = date('Y-m-d');

// Get attendance for today
$attendance_sql = "SELECT a.*, e.first_name, e.last_name, e.employee_id as emp_code, 
                   d.name as dept_name, e.position 
                   FROM attendance a 
                   JOIN employees e ON a.employee_id = e.id 
                   LEFT JOIN departments d ON e.department = d.code 
                   WHERE a.date = '$today' 
                   ORDER BY a.check_in DESC";
$attendance_result = mysqli_query($conn, $attendance_sql);

// Get attendance statistics
$stats_sql = "SELECT 
              COUNT(CASE WHEN status = 'present' THEN 1 END) as present,
              COUNT(CASE WHEN status = 'absent' THEN 1 END) as absent,
              COUNT(CASE WHEN status = 'half-day' THEN 1 END) as half_day,
              COUNT(CASE WHEN status = 'late' THEN 1 END) as late,
              COUNT(CASE WHEN status = 'leave' THEN 1 END) as on_leave
              FROM attendance 
              WHERE date = '$today'";
$stats_result = mysqli_query($conn, $stats_sql);
$stats = $stats_result->fetch_assoc();

// Get total employees
$total_emp_sql = "SELECT COUNT(*) as total FROM employees WHERE employment_status = 'active'";
$total_emp_result = mysqli_query($conn, $total_emp_sql);
$total_employees = $total_emp_result->fetch_assoc()['total'];

// Get employees without attendance for today
$missing_sql = "SELECT e.*, d.name as dept_name 
                FROM employees e 
                LEFT JOIN departments d ON e.department = d.code 
                WHERE e.employment_status = 'active' 
                AND e.id NOT IN (SELECT employee_id FROM attendance WHERE date = '$today') 
                ORDER BY e.first_name";
$missing_result = mysqli_query($conn, $missing_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Inherit all styles from dashboard.php */
        <?php include 'dashboard_styles.php'; ?>
        
        /* Attendance Specific Styles */
        .date-picker-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .date-controls {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .date-display {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
        }
        
        .attendance-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-box {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
            cursor: pointer;
        }
        
        .stat-box:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }
        
        .stat-content h3 {
            font-size: 2rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .stat-content p {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .attendance-tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
            margin-bottom: 2rem;
            background: white;
            border-radius: 12px 12px 0 0;
            overflow: hidden;
        }
        
        .attendance-tab {
            padding: 1rem 1.5rem;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--gray);
            font-weight: 500;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .attendance-tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            background: #f0f9ff;
        }
        
        .attendance-content {
            display: none;
            background: white;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .attendance-content.active {
            display: block;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .attendance-table th {
            background: #f8fafc;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 1px solid var(--border);
        }
        
        .attendance-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
        }
        
        .attendance-table tr:hover {
            background: #f8fafc;
        }
        
        .employee-cell {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .time-cell {
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }
        
        .hours-cell {
            font-weight: 600;
            color: var(--primary);
        }
        
        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-present { background: #d1fae5; color: var(--secondary); }
        .status-absent { background: #fee2e2; color: var(--danger); }
        .status-late { background: #fef3c7; color: var(--warning); }
        .status-halfday { background: #e0f2fe; color: var(--info); }
        .status-leave { background: #ede9fe; color: #8b5cf6; }
        
        .action-cell {
            display: flex;
            gap: 0.5rem;
        }
        
        .time-input {
            width: 120px;
            padding: 0.5rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-family: 'Courier New', monospace;
        }
        
        .bulk-actions-bar {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .bulk-select {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .bulk-buttons {
            display: flex;
            gap: 1rem;
        }
        
        .calendar-view {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: var(--border);
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .calendar-day {
            background: white;
            padding: 1rem;
            min-height: 100px;
            position: relative;
        }
        
        .calendar-day.weekend {
            background: #f8fafc;
        }
        
        .calendar-day.today {
            background: #f0f9ff;
            border: 2px solid var(--primary);
        }
        
        .day-number {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .attendance-indicators {
            position: absolute;
            bottom: 0.5rem;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 2px;
        }
        
        .indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        
        .indicator.present { background: var(--secondary); }
        .indicator.absent { background: var(--danger); }
        .indicator.late { background: var(--warning); }
        .indicator.leave { background: #8b5cf6; }
        
        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .summary-item {
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
        }
        
        .summary-item h4 {
            color: var(--gray);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .summary-item p {
            color: var(--dark);
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .report-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .report-card:hover {
            transform: translateY(-3px);
        }
        
        .report-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .report-content h4 {
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .report-content p {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .mark-all-section {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .date-picker-container {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .attendance-tabs {
                overflow-x: auto;
            }
            
            .bulk-actions-bar {
                flex-direction: column;
                gap: 1rem;
            }
            
            .bulk-select, .bulk-buttons {
                width: 100%;
                justify-content: space-between;
            }
            
            .calendar-grid {
                overflow-x: auto;
            }
            
            .attendance-table {
                min-width: 800px;
            }
        }
    </style>
</head>
<body>
    <!-- Same splash animation -->
    <div class="splash-animation" id="splashAnimation">
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="logo-text">ATTENDANCE</div>
            <div class="tagline">Attendance Tracking System</div>
        </div>
        <div class="loader"></div>
    </div>

    <!-- Main Layout -->
    <div class="dashboard-container">
        <!-- Same Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="header-left">
                    <h1><i class="fas fa-calendar-check"></i> Attendance Management</h1>
                </div>
                <div class="header-right">
                    <button class="btn btn-primary" id="markAttendanceBtn">
                        <i class="fas fa-user-check"></i> Mark Attendance
                    </button>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Date Picker -->
                <div class="date-picker-container">
                    <div class="date-controls">
                        <button class="btn btn-outline" id="prevDay">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div class="date-display" id="currentDateDisplay">
                            <?php echo date('l, F j, Y'); ?>
                        </div>
                        <button class="btn btn-outline" id="nextDay">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <input type="date" id="datePicker" class="form-control" 
                               value="<?php echo $today; ?>" style="width: 150px;">
                    </div>
                    <div>
                        <button class="btn btn-success" id="saveAllChanges">
                            <i class="fas fa-save"></i> Save All Changes
                        </button>
                    </div>
                </div>

                <!-- Attendance Stats -->
                <div class="attendance-stats">
                    <div class="stat-box" onclick="filterByStatus('present')">
                        <div class="stat-icon" style="background-color: var(--secondary);">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $stats['present'] ?? 0; ?></h3>
                            <p>Present Today</p>
                        </div>
                    </div>
                    <div class="stat-box" onclick="filterByStatus('absent')">
                        <div class="stat-icon" style="background-color: var(--danger);">
                            <i class="fas fa-user-times"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $stats['absent'] ?? 0; ?></h3>
                            <p>Absent Today</p>
                        </div>
                    </div>
                    <div class="stat-box" onclick="filterByStatus('late')">
                        <div class="stat-icon" style="background-color: var(--warning);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $stats['late'] ?? 0; ?></h3>
                            <p>Late Arrivals</p>
                        </div>
                    </div>
                    <div class="stat-box" onclick="filterByStatus('half-day')">
                        <div class="stat-icon" style="background-color: var(--info);">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $stats['half_day'] ?? 0; ?></h3>
                            <p>Half Day</p>
                        </div>
                    </div>
                    <div class="stat-box" onclick="filterByStatus('leave')">
                        <div class="stat-icon" style="background-color: #8b5cf6;">
                            <i class="fas fa-calendar-minus"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $stats['on_leave'] ?? 0; ?></h3>
                            <p>On Leave</p>
                        </div>
                    </div>
                </div>

                <!-- Attendance Tabs -->
                <div class="attendance-tabs">
                    <button class="attendance-tab active" data-tab="daily">Daily Attendance</button>
                    <button class="attendance-tab" data-tab="calendar">Calendar View</button>
                    <button class="attendance-tab" data-tab="reports">Reports</button>
                    <button class="attendance-tab" data-tab="settings">Settings</button>
                </div>

                <!-- Daily Attendance Tab -->
                <div class="attendance-content active" id="dailyTab">
                    <!-- Bulk Actions -->
                    <div class="bulk-actions-bar">
                        <div class="bulk-select">
                            <input type="checkbox" id="selectAllAttendance">
                            <label for="selectAllAttendance">Select All Visible</label>
                            <span id="selectedCount">0 selected</span>
                        </div>
                        <div class="bulk-buttons">
                            <select id="bulkStatus" class="form-control" style="width: 150px;">
                                <option value="">Mark as...</option>
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="half-day">Half Day</option>
                                <option value="leave">On Leave</option>
                            </select>
                            <button class="btn btn-outline" id="applyBulkStatus">
                                <i class="fas fa-check"></i> Apply
                            </button>
                            <button class="btn btn-outline" id="exportDaily">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                    </div>

                    <!-- Mark All Section -->
                    <div class="mark-all-section">
                        <span style="color: var(--gray);">Quick Mark:</span>
                        <button class="btn btn-sm btn-outline" onclick="markAll('present')">
                            <i class="fas fa-user-check"></i> All Present
                        </button>
                        <button class="btn btn-sm btn-outline" onclick="markAll('absent')">
                            <i class="fas fa-user-times"></i> All Absent
                        </button>
                        <button class="btn btn-sm btn-outline" onclick="markAll('leave')">
                            <i class="fas fa-calendar-minus"></i> All Leave
                        </button>
                    </div>

                    <!-- Attendance Table -->
                    <div class="table-container">
                        <table class="attendance-table" id="attendanceTable">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAllCheckbox">
                                    </th>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Total Hours</th>
                                    <th>Status</th>
                                    <th>Overtime</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="attendanceTableBody">
                                <?php if(mysqli_num_rows($attendance_result) > 0): ?>
                                    <?php while($att = $attendance_result->fetch_assoc()): 
                                        $status_class = 'status-' . $att['status'];
                                        $check_in = $att['check_in'] ? date('H:i', strtotime($att['check_in'])) : '';
                                        $check_out = $att['check_out'] ? date('H:i', strtotime($att['check_out'])) : '';
                                    ?>
                                    <tr data-employee-id="<?php echo $att['employee_id']; ?>" data-attendance-id="<?php echo $att['id']; ?>">
                                        <td>
                                            <input type="checkbox" class="attendance-checkbox" 
                                                   data-employee-id="<?php echo $att['employee_id']; ?>">
                                        </td>
                                        <td>
                                            <div class="employee-cell">
                                                <div class="employee-avatar small">
                                                    <?php echo strtoupper(substr($att['first_name'], 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <div style="font-weight: 600;"><?php echo htmlspecialchars($att['first_name'] . ' ' . $att['last_name']); ?></div>
                                                    <div style="font-size: 0.85rem; color: var(--gray);"><?php echo $att['emp_code']; ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($att['dept_name']); ?></td>
                                        <td class="time-cell">
                                            <input type="time" class="time-input check-in" 
                                                   value="<?php echo $check_in; ?>" 
                                                   data-original="<?php echo $check_in; ?>">
                                        </td>
                                        <td class="time-cell">
                                            <input type="time" class="time-input check-out" 
                                                   value="<?php echo $check_out; ?>" 
                                                   data-original="<?php echo $check_out; ?>">
                                        </td>
                                        <td class="hours-cell"><?php echo $att['total_hours'] ?? '0'; ?> hrs</td>
                                        <td>
                                            <select class="status-select form-control" 
                                                    data-original="<?php echo $att['status']; ?>">
                                                <option value="present" <?php echo $att['status'] == 'present' ? 'selected' : ''; ?>>Present</option>
                                                <option value="absent" <?php echo $att['status'] == 'absent' ? 'selected' : ''; ?>>Absent</option>
                                                <option value="late" <?php echo $att['status'] == 'late' ? 'selected' : ''; ?>>Late</option>
                                                <option value="half-day" <?php echo $att['status'] == 'half-day' ? 'selected' : ''; ?>>Half Day</option>
                                                <option value="leave" <?php echo $att['status'] == 'leave' ? 'selected' : ''; ?>>On Leave</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="overtime-input form-control" 
                                                   value="<?php echo $att['overtime_hours'] ?? '0'; ?>" 
                                                   step="0.5" min="0" style="width: 80px;">
                                        </td>
                                        <td class="action-cell">
                                            <button class="btn btn-sm btn-outline save-row" title="Save">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline reset-row" title="Reset">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" style="text-align: center; padding: 3rem; color: var(--gray);">
                                            <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                                            <h3>No attendance recorded for today</h3>
                                            <p>Mark attendance for employees or wait for check-ins</p>
                                            <button class="btn btn-primary mt-2" id="markAllToday">
                                                <i class="fas fa-user-check"></i> Mark All Present
                                            </button>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Missing Attendance -->
                    <?php if(mysqli_num_rows($missing_result) > 0): ?>
                    <div class="summary-card">
                        <h3 style="margin-bottom: 1rem; color: var(--danger);">
                            <i class="fas fa-exclamation-triangle"></i> Missing Attendance
                        </h3>
                        <div class="summary-grid">
                            <?php while($missing = $missing_result->fetch_assoc()): ?>
                            <div class="summary-item">
                                <h4><?php echo htmlspecialchars($missing['first_name'] . ' ' . $missing['last_name']); ?></h4>
                                <p><?php echo htmlspecialchars($missing['dept_name']); ?> • <?php echo $missing['position']; ?></p>
                                <button class="btn btn-sm btn-outline mt-2 mark-missing" 
                                        data-employee-id="<?php echo $missing['id']; ?>">
                                    <i class="fas fa-user-check"></i> Mark Attendance
                                </button>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Calendar View Tab -->
                <div class="attendance-content" id="calendarTab">
                    <div class="calendar-view">
                        <!-- Calendar will be loaded dynamically -->
                    </div>
                </div>

                <!-- Reports Tab -->
                <div class="attendance-content" id="reportsTab">
                    <div class="reports-grid">
                        <div class="report-card" onclick="generateReport('monthly')">
                            <div class="report-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="report-content">
                                <h4>Monthly Attendance Report</h4>
                                <p>Detailed monthly attendance summary with analytics</p>
                                <button class="btn btn-outline">Generate Report</button>
                            </div>
                        </div>
                        <div class="report-card" onclick="generateReport('department')">
                            <div class="report-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="report-content">
                                <h4>Department-wise Report</h4>
                                <p>Attendance analysis by departments</p>
                                <button class="btn btn-outline">Generate Report</button>
                            </div>
                        </div>
                        <div class="report-card" onclick="generateReport('individual')">
                            <div class="report-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="report-content">
                                <h4>Individual Attendance Report</h4>
                                <p>Detailed report for specific employee</p>
                                <button class="btn btn-outline">Generate Report</button>
                            </div>
                        </div>
                        <div class="report-card" onclick="generateReport('late')">
                            <div class="report-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="report-content">
                                <h4>Late Arrival Report</h4>
                                <p>Analysis of late arrivals and patterns</p>
                                <button class="btn btn-outline">Generate Report</button>
                            </div>
                        </div>
                        <div class="report-card" onclick="generateReport('overtime')">
                            <div class="report-icon">
                                <i class="fas fa-business-time"></i>
                            </div>
                            <div class="report-content">
                                <h4>Overtime Report</h4>
                                <p>Overtime hours and compensation</p>
                                <button class="btn btn-outline">Generate Report</button>
                            </div>
                        </div>
                        <div class="report-card" onclick="generateReport('summary')">
                            <div class="report-icon">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <div class="report-content">
                                <h4>Attendance Summary</h4>
                                <p>Overall attendance statistics and trends</p>
                                <button class="btn btn-outline">Generate Report</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script>
        // Attendance management JavaScript
        <?php include 'attendance_scripts.php'; ?>
    </script>
</body>
</html>