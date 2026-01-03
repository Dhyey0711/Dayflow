<?php
// employees.php - Employee Management
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

// Get all employees with department info
$sql = "SELECT e.*, d.name as dept_name, d.code as dept_code 
        FROM employees e 
        LEFT JOIN departments d ON e.department = d.code 
        WHERE e.employment_status != 'terminated' 
        ORDER BY e.first_name, e.last_name";
$employees_result = mysqli_query($conn, $sql);

// Get departments for filters
$dept_sql = "SELECT * FROM departments WHERE is_active = 1";
$departments = mysqli_query($conn, $dept_sql);

// Get employee count by status
$status_counts = [];
$status_sql = "SELECT employment_status, COUNT(*) as count FROM employees GROUP BY employment_status";
$status_result = mysqli_query($conn, $status_sql);
while($row = $status_result->fetch_assoc()) {
    $status_counts[$row['employment_status']] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Inherit all styles from dashboard.php */
        <?php include 'dashboard_styles.php'; ?>
        
        /* Employee Management Specific Styles */
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .filter-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }
        
        .employee-counts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .count-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .count-card:hover {
            transform: translateY(-3px);
        }
        
        .count-card.active {
            background: var(--primary);
            color: white;
        }
        
        .count-card.active .count-number {
            color: white;
        }
        
        .count-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .count-label {
            font-size: 0.9rem;
            color: var(--gray);
        }
        
        .count-card.active .count-label {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .employees-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        
        .table-actions {
            display: flex;
            gap: 1rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #f8fafc;
            padding: 1rem 1.5rem;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 1px solid var(--border);
        }
        
        td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover {
            background: #f8fafc;
        }
        
        .employee-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            overflow: hidden;
        }
        
        .employee-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .employee-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .employee-details h4 {
            color: var(--dark);
            margin-bottom: 0.3rem;
        }
        
        .employee-details p {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-active { background: #d1fae5; color: var(--secondary); }
        .status-inactive { background: #fee2e2; color: var(--danger); }
        .status-leave { background: #fef3c7; color: var(--warning); }
        .status-probation { background: #e0f2fe; color: var(--info); }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: white;
            color: var(--gray);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .action-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            padding: 1.5rem;
            border-top: 1px solid var(--border);
        }
        
        .page-link {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: white;
            color: var(--dark);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .page-link.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .page-link:hover:not(.active) {
            background: #f8fafc;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gray);
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: var(--border);
        }
        
        .bulk-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .checkbox-cell {
            width: 50px;
            text-align: center;
        }
        
        .select-all {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        
        /* Import/Export Modal Styles */
        .import-export-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin: 2rem 0;
        }
        
        .import-box, .export-box {
            border: 2px dashed var(--border);
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .import-box:hover, .export-box:hover {
            border-color: var(--primary);
            background: #f0f9ff;
        }
        
        .import-icon, .export-icon {
            width: 60px;
            height: 60px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }
        
        .file-input {
            display: none;
        }
        
        .file-label {
            display: block;
            padding: 1rem;
            background: var(--primary);
            color: white;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 1rem;
        }
        
        /* Quick Stats */
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-box {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        
        .stat-content h3 {
            font-size: 1.5rem;
            color: var(--dark);
            margin-bottom: 0.3rem;
        }
        
        .stat-content p {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }
            
            .table-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .table-actions {
                width: 100%;
                flex-wrap: wrap;
            }
            
            .action-buttons {
                flex-wrap: wrap;
            }
            
            .import-export-options {
                grid-template-columns: 1fr;
            }
            
            .quick-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Same splash animation as dashboard -->
    <div class="splash-animation" id="splashAnimation">
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-users"></i>
            </div>
            <div class="logo-text">EMPLOYEES</div>
            <div class="tagline">Employee Management System</div>
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
                    <h1><i class="fas fa-users"></i> Employee Management</h1>
                </div>
                <div class="header-right">
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="Search employees..." id="searchInput">
                    </div>
                    <button class="btn btn-primary" id="addEmployeeBtn">
                        <i class="fas fa-plus"></i> Add Employee
                    </button>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Quick Stats -->
                <div class="quick-stats">
                    <div class="stat-box">
                        <div class="stat-icon" style="background-color: var(--primary);">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $status_counts['active'] ?? 0; ?></h3>
                            <p>Active Employees</p>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon" style="background-color: var(--warning);">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $status_counts['probation'] ?? 0; ?></h3>
                            <p>On Probation</p>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon" style="background-color: var(--info);">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $status_counts['on_leave'] ?? 0; ?></h3>
                            <p>On Leave</p>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon" style="background-color: var(--secondary);">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo ($status_counts['active'] ?? 0) + ($status_counts['probation'] ?? 0); ?></h3>
                            <p>Total Workforce</p>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-section">
                    <div class="filter-grid">
                        <div>
                            <label>Department</label>
                            <select class="form-control" id="departmentFilter">
                                <option value="">All Departments</option>
                                <?php while($dept = $departments->fetch_assoc()): ?>
                                <option value="<?php echo $dept['code']; ?>">
                                    <?php echo htmlspecialchars($dept['name']); ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label>Employment Status</label>
                            <select class="form-control" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="probation">Probation</option>
                                <option value="on_leave">On Leave</option>
                            </select>
                        </div>
                        <div>
                            <label>Job Type</label>
                            <select class="form-control" id="jobTypeFilter">
                                <option value="">All Types</option>
                                <option value="full-time">Full-time</option>
                                <option value="part-time">Part-time</option>
                                <option value="contract">Contract</option>
                                <option value="intern">Intern</option>
                            </select>
                        </div>
                        <div>
                            <label>Sort By</label>
                            <select class="form-control" id="sortFilter">
                                <option value="name_asc">Name (A-Z)</option>
                                <option value="name_desc">Name (Z-A)</option>
                                <option value="date_asc">Joining Date (Oldest)</option>
                                <option value="date_desc">Joining Date (Newest)</option>
                            </select>
                        </div>
                    </div>
                    <div class="filter-actions">
                        <button class="btn btn-outline" id="resetFilters">
                            <i class="fas fa-redo"></i> Reset Filters
                        </button>
                        <button class="btn btn-primary" id="applyFilters">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                    </div>
                </div>

                <!-- Employees Table -->
                <div class="employees-table">
                    <div class="table-header">
                        <h3>Employee Records</h3>
                        <div class="table-actions">
                            <button class="btn btn-outline" id="importBtn">
                                <i class="fas fa-file-import"></i> Import
                            </button>
                            <button class="btn btn-outline" id="exportBtn">
                                <i class="fas fa-file-export"></i> Export
                            </button>
                            <button class="btn btn-outline" id="bulkActionsBtn">
                                <i class="fas fa-cog"></i> Bulk Actions
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="employeesTable">
                            <thead>
                                <tr>
                                    <th class="checkbox-cell">
                                        <div class="select-all">
                                            <input type="checkbox" id="selectAll">
                                        </div>
                                    </th>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Joining Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="employeesTableBody">
                                <?php if(mysqli_num_rows($employees_result) > 0): ?>
                                    <?php while($employee = $employees_result->fetch_assoc()): 
                                        $status_class = 'status-' . $employee['employment_status'];
                                    ?>
                                    <tr data-employee-id="<?php echo $employee['id']; ?>">
                                        <td class="checkbox-cell">
                                            <input type="checkbox" class="employee-checkbox" value="<?php echo $employee['id']; ?>">
                                        </td>
                                        <td>
                                            <div class="employee-info">
                                                <div class="employee-avatar">
                                                    <?php if (!empty($employee['profile_pic'])): ?>
                                                        <img src="<?php echo htmlspecialchars($employee['profile_pic']); ?>" alt="<?php echo htmlspecialchars($employee['first_name']); ?>">
                                                    <?php else: ?>
                                                        <?php echo strtoupper(substr($employee['first_name'], 0, 1)); ?>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="employee-details">
                                                    <h4><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></h4>
                                                    <p>ID: <?php echo $employee['id']; ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($employee['dept_name'] ?? 'Not Assigned'); ?></td>
                                        <td><?php echo htmlspecialchars($employee['position']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['email']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['phone'] ?? 'N/A'); ?></td>
                                        <td><?php echo date('d M Y', strtotime($employee['joining_date'])); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo $status_class; ?>">
                                                <?php echo ucfirst($employee['employment_status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn view-btn" title="View" onclick="viewEmployee(<?php echo $employee['id']; ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="action-btn edit-btn" title="Edit" onclick="editEmployee(<?php echo $employee['id']; ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="action-btn delete-btn" title="Delete" onclick="deleteEmployee(<?php echo $employee['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9">
                                            <div class="empty-state">
                                                <i class="fas fa-user-slash"></i>
                                                <h3>No Employees Found</h3>
                                                <p>Add your first employee to get started</p>
                                                <button class="btn btn-primary mt-3" id="addEmployeeEmptyBtn">
                                                    <i class="fas fa-plus"></i> Add Employee
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination" id="pagination">
                        <!-- Pagination will be generated dynamically -->
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modals -->
    <!-- Add/Edit Employee Modal -->
    <div class="modal-overlay" id="employeeModal">
        <div class="modal">
            <div class="modal-header">
                <h3 id="modalTitle">Add New Employee</h3>
                <button class="modal-close" id="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="employeeForm">
                    <!-- Form will be loaded dynamically -->
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" id="cancelModal">Cancel</button>
                <button class="btn btn-primary" id="saveEmployee">Save Employee</button>
            </div>
        </div>
    </div>

    <!-- Import/Export Modal -->
    <div class="modal-overlay" id="importExportModal">
        <div class="modal">
            <div class="modal-header">
                <h3 id="importExportTitle">Import/Export Employees</h3>
                <button class="modal-close" id="closeImportExportModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="import-export-options">
                    <div class="import-box" id="importSection">
                        <div class="import-icon">
                            <i class="fas fa-file-import"></i>
                        </div>
                        <h4>Import Employees</h4>
                        <p>Upload CSV/Excel file with employee data</p>
                        <input type="file" id="importFile" class="file-input" accept=".csv,.xlsx,.xls">
                        <label for="importFile" class="file-label">
                            <i class="fas fa-upload"></i> Choose File
                        </label>
                        <div id="importProgress" style="display: none; margin-top: 1rem;">
                            <div class="progress-bar" style="height: 4px; background: var(--border); border-radius: 2px;">
                                <div id="importProgressBar" style="height: 100%; background: var(--primary); width: 0%; transition: width 0.3s;"></div>
                            </div>
                            <div id="importStatus" style="text-align: center; margin-top: 0.5rem;"></div>
                        </div>
                        <a href="#" id="downloadTemplate" style="display: block; margin-top: 1rem; color: var(--primary);">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                    
                    <div class="export-box" id="exportSection">
                        <div class="export-icon">
                            <i class="fas fa-file-export"></i>
                        </div>
                        <h4>Export Employees</h4>
                        <p>Download employee data in various formats</p>
                        <div class="export-options" style="margin-top: 1rem;">
                            <button class="btn btn-outline btn-sm export-format" data-format="csv">
                                <i class="fas fa-file-csv"></i> CSV
                            </button>
                            <button class="btn btn-outline btn-sm export-format" data-format="excel">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <button class="btn btn-outline btn-sm export-format" data-format="pdf">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                        <div class="export-filters" style="margin-top: 1rem; padding: 1rem; background: #f8fafc; border-radius: 8px;">
                            <label style="display: block; margin-bottom: 0.5rem;">Filter Export:</label>
                            <select id="exportFilter" class="form-control">
                                <option value="all">All Employees</option>
                                <option value="active">Active Only</option>
                                <option value="current">Current Page</option>
                                <option value="selected">Selected Only</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" id="cancelImportExport">Close</button>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Modal -->
    <div class="modal-overlay" id="bulkActionsModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Bulk Actions</h3>
                <button class="modal-close" id="closeBulkModal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="selectedCount" style="text-align: center; margin-bottom: 2rem; font-size: 1.2rem;"></div>
                
                <div class="bulk-options">
                    <div class="form-group">
                        <label>Action to Perform:</label>
                        <select id="bulkAction" class="form-control">
                            <option value="">Select Action</option>
                            <option value="update_status">Update Status</option>
                            <option value="update_department">Update Department</option>
                            <option value="update_position">Update Position</option>
                            <option value="send_email">Send Email</option>
                            <option value="generate_contracts">Generate Contracts</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                    </div>
                    
                    <div id="actionDetails" style="display: none; margin-top: 1rem;">
                        <!-- Dynamic content based on selected action -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" id="cancelBulk">Cancel</button>
                <button class="btn btn-primary" id="applyBulkAction">Apply Action</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Confirm Delete</h3>
                <button class="modal-close" id="closeDeleteModal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="deleteMessage">Are you sure you want to delete this employee?</p>
                <div id="deleteDetails"></div>
                <div class="alert alert-warning" style="margin-top: 1rem; padding: 1rem; background: #fef3c7; border-radius: 8px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning:</strong> This action cannot be undone. All associated records will be removed.
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" id="cancelDelete">Cancel</button>
                <button class="btn btn-danger" id="confirmDelete">Delete Permanently</button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast"></div>

    <script>
        // JavaScript for Employee Management
        <?php include 'dashboard_scripts.php'; ?>
        
        // Employee Management Specific Functions
        let selectedEmployees = new Set();
        let currentPage = 1;
        const itemsPerPage = 10;
        
        document.addEventListener('DOMContentLoaded', function() {
            initializeEmployeePage();
            setupEmployeeEventListeners();
            loadPagination();
        });
        
        function initializeEmployeePage() {
            // Set active menu
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
                if(item.getAttribute('data-page') === 'employees') {
                    item.classList.add('active');
                }
            });
        }
        
        function setupEmployeeEventListeners() {
            // Add Employee Button
            document.getElementById('addEmployeeBtn').addEventListener('click', openAddModal);
            document.getElementById('addEmployeeEmptyBtn')?.addEventListener('click', openAddModal);
            
            // Filter functionality
            document.getElementById('applyFilters').addEventListener('click', applyFilters);
            document.getElementById('resetFilters').addEventListener('click', resetFilters);
            document.getElementById('searchInput').addEventListener('input', searchEmployees);
            
            // Import/Export
            document.getElementById('importBtn').addEventListener('click', () => openImportExportModal('import'));
            document.getElementById('exportBtn').addEventListener('click', () => openImportExportModal('export'));
            
            // Bulk Actions
            document.getElementById('bulkActionsBtn').addEventListener('click', openBulkActionsModal);
            
            // Checkbox handling
            document.getElementById('selectAll').addEventListener('change', toggleSelectAll);
            document.querySelectorAll('.employee-checkbox').forEach(cb => {
                cb.addEventListener('change', updateSelectedCount);
            });
            
            // Modal close buttons
            document.getElementById('closeModal').addEventListener('click', () => closeModal('employeeModal'));
            document.getElementById('cancelModal').addEventListener('click', () => closeModal('employeeModal'));
            document.getElementById('closeImportExportModal').addEventListener('click', () => closeModal('importExportModal'));
            document.getElementById('cancelImportExport').addEventListener('click', () => closeModal('importExportModal'));
            document.getElementById('closeBulkModal').addEventListener('click', () => closeModal('bulkActionsModal'));
            document.getElementById('cancelBulk').addEventListener('click', () => closeModal('bulkActionsModal'));
            document.getElementById('closeDeleteModal').addEventListener('click', () => closeModal('deleteModal'));
            document.getElementById('cancelDelete').addEventListener('click', () => closeModal('deleteModal'));
            
            // Save employee
            document.getElementById('saveEmployee').addEventListener('click', saveEmployee);
            document.getElementById('confirmDelete').addEventListener('click', confirmDelete);
            document.getElementById('applyBulkAction').addEventListener('click', applyBulkAction);
            
            // Import/Export actions
            document.getElementById('importFile').addEventListener('change', handleFileImport);
            document.querySelectorAll('.export-format').forEach(btn => {
                btn.addEventListener('click', () => exportEmployees(btn.dataset.format));
            });
            
            // Bulk action selection
            document.getElementById('bulkAction').addEventListener('change', showBulkActionDetails);
        }
        
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add New Employee';
            loadEmployeeForm();
            openModal('employeeModal');
        }
        
        function editEmployee(id) {
            document.getElementById('modalTitle').textContent = 'Edit Employee';
            loadEmployeeForm(id);
            openModal('employeeModal');
        }
        
        function viewEmployee(id) {
            // Redirect to employee details page
            window.location.href = `employee_details.php?id=${id}`;
        }
        
        function loadEmployeeForm(employeeId = null) {
            const formContainer = document.getElementById('employeeForm');
            
            if (employeeId) {
                // Load existing employee data via AJAX
                fetch(`ajax/get_employee.php?id=${employeeId}`)
                    .then(response => response.json())
                    .then(data => {
                        formContainer.innerHTML = generateEmployeeForm(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        formContainer.innerHTML = generateEmployeeForm();
                    });
            } else {
                // New employee form
                formContainer.innerHTML = generateEmployeeForm();
            }
        }
        
        function generateEmployeeForm(data = {}) {
            return `
                <input type="hidden" name="employee_id" value="${data.id || ''}">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" 
                               value="${data.first_name || ''}" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" 
                               value="${data.last_name || ''}" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="${data.email || ''}" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" class="form-control" 
                               value="${data.phone || ''}" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="department">Department *</label>
                        <select id="department" name="department" class="form-control" required>
                            <option value="">Select Department</option>
                            <?php 
                            $departments->data_seek(0);
                            while($dept = $departments->fetch_assoc()): 
                            ?>
                            <option value="<?php echo $dept['code']; ?>" ${data.department == '<?php echo $dept['code']; ?>' ? 'selected' : ''}>
                                <?php echo htmlspecialchars($dept['name']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="position">Position *</label>
                        <input type="text" id="position" name="position" class="form-control" 
                               value="${data.position || ''}" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="job_type">Job Type *</label>
                        <select id="job_type" name="job_type" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="full-time" ${data.job_type == 'full-time' ? 'selected' : ''}>Full-time</option>
                            <option value="part-time" ${data.job_type == 'part-time' ? 'selected' : ''}>Part-time</option>
                            <option value="contract" ${data.job_type == 'contract' ? 'selected' : ''}>Contract</option>
                            <option value="intern" ${data.job_type == 'intern' ? 'selected' : ''}>Intern</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="joining_date">Joining Date *</label>
                        <input type="date" id="joining_date" name="joining_date" class="form-control" 
                               value="${data.joining_date || new Date().toISOString().split('T')[0]}" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="employment_status">Employment Status</label>
                        <select id="employment_status" name="employment_status" class="form-control">
                            <option value="active" ${data.employment_status == 'active' ? 'selected' : ''}>Active</option>
                            <option value="probation" ${data.employment_status == 'probation' ? 'selected' : ''}>Probation</option>
                            <option value="on_leave" ${data.employment_status == 'on_leave' ? 'selected' : ''}>On Leave</option>
                            <option value="inactive" ${data.employment_status == 'inactive' ? 'selected' : ''}>Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="basic_salary">Basic Salary (₹)</label>
                        <input type="number" id="basic_salary" name="basic_salary" class="form-control" 
                               value="${data.basic_salary || '0'}" step="0.01">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" class="form-control" rows="3">${data.address || ''}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="emergency_contact">Emergency Contact</label>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name" 
                                   class="form-control" placeholder="Contact Name" value="${data.emergency_contact_name || ''}">
                        </div>
                        <div class="form-group">
                            <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" 
                                   class="form-control" placeholder="Contact Phone" value="${data.emergency_contact_phone || ''}">
                        </div>
                    </div>
                </div>
            `;
        }
        
        function saveEmployee() {
            const formData = new FormData(document.getElementById('employeeForm'));
            
            // Validate form
            if (!validateEmployeeForm()) {
                return;
            }
            
            // Show loading
            const saveBtn = document.getElementById('saveEmployee');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveBtn.disabled = true;
            
            // Send AJAX request
            fetch('ajax/save_employee.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    closeModal('employeeModal');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showToast(data.message, 'error');
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                }
            })
            .catch(error => {
                showToast('Error saving employee: ' + error.message, 'error');
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }
        
        function validateEmployeeForm() {
            const requiredFields = ['first_name', 'last_name', 'email', 'phone', 'department', 'position', 'job_type', 'joining_date'];
            
            for (const field of requiredFields) {
                const input = document.getElementById(field);
                if (!input.value.trim()) {
                    showToast(`Please fill in ${field.replace('_', ' ')}`, 'error');
                    input.focus();
                    return false;
                }
            }
            
            // Validate email
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showToast('Please enter a valid email address', 'error');
                return false;
            }
            
            return true;
        }
        
        function deleteEmployee(id) {
            // Get employee details for confirmation
            fetch(`ajax/get_employee.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('deleteMessage').textContent = 
                        `Are you sure you want to delete ${data.first_name} ${data.last_name}?`;
                    
                    document.getElementById('deleteDetails').innerHTML = `
                        <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                            <p><strong>Employee:</strong> ${data.first_name} ${data.last_name}</p>
                            <p><strong>Position:</strong> ${data.position}</p>
                            <p><strong>Department:</strong> ${data.dept_name}</p>
                            <p><strong>Employee ID:</strong> ${data.id}</p>
                        </div>
                    `;
                    
                    // Store employee ID for deletion
                    document.getElementById('confirmDelete').dataset.employeeId = id;
                    openModal('deleteModal');
                })
                .catch(error => {
                    showToast('Error loading employee details', 'error');
                });
        }
        
        function confirmDelete() {
            const employeeId = this.dataset.employeeId;
            
            fetch(`ajax/delete_employee.php?id=${employeeId}`, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    closeModal('deleteModal');
                    // Remove row from table
                    const row = document.querySelector(`tr[data-employee-id="${employeeId}"]`);
                    if (row) {
                        row.style.opacity = '0.5';
                        setTimeout(() => {
                            row.remove();
                            // If no rows left, show empty state
                            if (document.querySelectorAll('#employeesTableBody tr').length === 0) {
                                location.reload();
                            }
                        }, 500);
                    }
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Error deleting employee', 'error');
            });
        }
        
        function applyFilters() {
            const filters = {
                department: document.getElementById('departmentFilter').value,
                status: document.getElementById('statusFilter').value,
                jobType: document.getElementById('jobTypeFilter').value,
                sort: document.getElementById('sortFilter').value,
                search: document.getElementById('searchInput').value
            };
            
            // Show loading
            showToast('Applying filters...', 'info');
            
            // In real application, this would be an AJAX call
            // For now, we'll just filter client-side
            filterTableRows(filters);
        }
        
        function resetFilters() {
            document.getElementById('departmentFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('jobTypeFilter').value = '';
            document.getElementById('sortFilter').value = 'name_asc';
            document.getElementById('searchInput').value = '';
            
            applyFilters();
            showToast('Filters reset', 'success');
        }
        
        function searchEmployees() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#employeesTableBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }
        
        function filterTableRows(filters) {
            const rows = document.querySelectorAll('#employeesTableBody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                let show = true;
                
                // Department filter
                if (filters.department) {
                    const dept = row.querySelector('td:nth-child(3)').textContent;
                    if (!dept.includes(filters.department)) show = false;
                }
                
                // Status filter
                if (filters.status) {
                    const status = row.querySelector('.status-badge').textContent.toLowerCase();
                    if (status !== filters.status) show = false;
                }
                
                // Job type filter
                if (filters.jobType) {
                    // This would check the job type column
                    // For now, we'll just show all
                }
                
                // Search filter
                if (filters.search) {
                    const text = row.textContent.toLowerCase();
                    if (!text.includes(filters.search.toLowerCase())) show = false;
                }
                
                row.style.display = show ? '' : 'none';
                if (show) visibleCount++;
            });
            
            // Update count display
            showToast(`Found ${visibleCount} employees`, 'success');
        }
        
        function toggleSelectAll() {
            const checked = this.checked;
            document.querySelectorAll('.employee-checkbox').forEach(cb => {
                cb.checked = checked;
                if (checked) {
                    selectedEmployees.add(cb.value);
                } else {
                    selectedEmployees.delete(cb.value);
                }
            });
            updateSelectedCount();
        }
        
        function updateSelectedCount() {
            selectedEmployees.clear();
            document.querySelectorAll('.employee-checkbox:checked').forEach(cb => {
                selectedEmployees.add(cb.value);
            });
            
            // Update UI if needed
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.employee-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            const someChecked = Array.from(checkboxes).some(cb => cb.checked);
            
            selectAll.checked = allChecked;
            selectAll.indeterminate = !allChecked && someChecked;
        }
        
        function openImportExportModal(type) {
            document.getElementById('importExportTitle').textContent = 
                type === 'import' ? 'Import Employees' : 'Export Employees';
            
            if (type === 'import') {
                document.getElementById('importSection').style.display = 'block';
                document.getElementById('exportSection').style.display = 'none';
            } else {
                document.getElementById('importSection').style.display = 'none';
                document.getElementById('exportSection').style.display = 'block';
            }
            
            openModal('importExportModal');
        }
        
        function handleFileImport(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            // Validate file type
            const validTypes = ['.csv', '.xlsx', '.xls'];
            const fileExt = '.' + file.name.split('.').pop().toLowerCase();
            
            if (!validTypes.includes(fileExt)) {
                showToast('Please upload a CSV or Excel file', 'error');
                return;
            }
            
            // Show progress
            document.getElementById('importProgress').style.display = 'block';
            document.getElementById('importStatus').textContent = 'Processing file...';
            
            // Simulate import progress
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                document.getElementById('importProgressBar').style.width = `${progress}%`;
                
                if (progress >= 100) {
                    clearInterval(interval);
                    document.getElementById('importStatus').textContent = 'File imported successfully!';
                    document.getElementById('importStatus').style.color = 'var(--secondary)';
                    
                    // Reload page after delay
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            }, 300);
        }
        
        function exportEmployees(format) {
            const filter = document.getElementById('exportFilter').value;
            let url = `ajax/export_employees.php?format=${format}`;
            
            if (filter === 'selected') {
                if (selectedEmployees.size === 0) {
                    showToast('Please select employees to export', 'warning');
                    return;
                }
                url += `&ids=${Array.from(selectedEmployees).join(',')}`;
            } else if (filter !== 'all') {
                url += `&filter=${filter}`;
            }
            
            // Trigger download
            window.open(url, '_blank');
            showToast(`Exporting to ${format.toUpperCase()}...`, 'success');
            closeModal('importExportModal');
        }
        
        function openBulkActionsModal() {
            if (selectedEmployees.size === 0) {
                showToast('Please select employees first', 'warning');
                return;
            }
            
            document.getElementById('selectedCount').innerHTML = `
                <i class="fas fa-users"></i> ${selectedEmployees.size} employees selected
            `;
            
            openModal('bulkActionsModal');
        }
        
        function showBulkActionDetails() {
            const action = this.value;
            const detailsDiv = document.getElementById('actionDetails');
            
            if (!action) {
                detailsDiv.style.display = 'none';
                return;
            }
            
            let html = '';
            
            switch(action) {
                case 'update_status':
                    html = `
                        <div class="form-group">
                            <label>New Status:</label>
                            <select id="newStatus" class="form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="probation">Probation</option>
                                <option value="on_leave">On Leave</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Effective Date:</label>
                            <input type="date" id="statusDate" class="form-control" 
                                   value="${new Date().toISOString().split('T')[0]}">
                        </div>
                        <div class="form-group">
                            <label>Reason (Optional):</label>
                            <input type="text" id="statusReason" class="form-control" 
                                   placeholder="Reason for status change">
                        </div>
                    `;
                    break;
                    
                case 'update_department':
                    html = `
                        <div class="form-group">
                            <label>New Department:</label>
                            <select id="newDepartment" class="form-control">
                                <option value="">Select Department</option>
                                <?php 
                                $departments->data_seek(0);
                                while($dept = $departments->fetch_assoc()): 
                                ?>
                                <option value="<?php echo $dept['code']; ?>">
                                    <?php echo htmlspecialchars($dept['name']); ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Effective Date:</label>
                            <input type="date" id="deptDate" class="form-control" 
                                   value="${new Date().toISOString().split('T')[0]}">
                        </div>
                    `;
                    break;
                    
                case 'update_position':
                    html = `
                        <div class="form-group">
                            <label>New Position:</label>
                            <input type="text" id="newPosition" class="form-control" 
                                   placeholder="Enter new position title">
                        </div>
                        <div class="form-group">
                            <label>Effective Date:</label>
                            <input type="date" id="positionDate" class="form-control" 
                                   value="${new Date().toISOString().split('T')[0]}">
                        </div>
                    `;
                    break;
                    
                case 'send_email':
                    html = `
                        <div class="form-group">
                            <label>Email Subject:</label>
                            <input type="text" id="emailSubject" class="form-control" 
                                   placeholder="Enter email subject">
                        </div>
                        <div class="form-group">
                            <label>Email Message:</label>
                            <textarea id="emailMessage" class="form-control" rows="4" 
                                      placeholder="Enter email message"></textarea>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="includeAttachment"> Include attachment
                            </label>
                        </div>
                    `;
                    break;
                    
                case 'delete':
                    html = `
                        <div class="alert alert-danger" style="background: #fee2e2; padding: 1rem; border-radius: 8px; color: #dc2626;">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Warning:</strong> This will permanently delete ${selectedEmployees.size} employees.
                            This action cannot be undone.
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="confirmBulkDelete"> 
                                I understand this action cannot be undone
                            </label>
                        </div>
                    `;
                    break;
            }
            
            detailsDiv.innerHTML = html;
            detailsDiv.style.display = 'block';
        }
        
        function applyBulkAction() {
            const action = document.getElementById('bulkAction').value;
            
            if (!action) {
                showToast('Please select an action', 'warning');
                return;
            }
            
            // Prepare data
            const data = {
                action: action,
                employee_ids: Array.from(selectedEmployees),
                details: {}
            };
            
            // Get additional details based on action
            switch(action) {
                case 'update_status':
                    data.details.new_status = document.getElementById('newStatus').value;
                    data.details.effective_date = document.getElementById('statusDate').value;
                    data.details.reason = document.getElementById('statusReason').value;
                    break;
                    
                case 'update_department':
                    const newDept = document.getElementById('newDepartment').value;
                    if (!newDept) {
                        showToast('Please select a department', 'warning');
                        return;
                    }
                    data.details.new_department = newDept;
                    data.details.effective_date = document.getElementById('deptDate').value;
                    break;
                    
                case 'update_position':
                    const newPosition = document.getElementById('newPosition').value;
                    if (!newPosition.trim()) {
                        showToast('Please enter a position', 'warning');
                        return;
                    }
                    data.details.new_position = newPosition;
                    data.details.effective_date = document.getElementById('positionDate').value;
                    break;
                    
                case 'send_email':
                    const subject = document.getElementById('emailSubject').value;
                    const message = document.getElementById('emailMessage').value;
                    if (!subject.trim() || !message.trim()) {
                        showToast('Please fill in email subject and message', 'warning');
                        return;
                    }
                    data.details.email_subject = subject;
                    data.details.email_message = message;
                    data.details.include_attachment = document.getElementById('includeAttachment').checked;
                    break;
                    
                case 'delete':
                    if (!document.getElementById('confirmBulkDelete')?.checked) {
                        showToast('Please confirm deletion', 'warning');
                        return;
                    }
                    break;
            }
            
            // Show loading
            const applyBtn = document.getElementById('applyBulkAction');
            const originalText = applyBtn.innerHTML;
            applyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            applyBtn.disabled = true;
            
            // Send AJAX request
            fetch('ajax/bulk_employee_actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    closeModal('bulkActionsModal');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showToast(data.message, 'error');
                    applyBtn.innerHTML = originalText;
                    applyBtn.disabled = false;
                }
            })
            .catch(error => {
                showToast('Error processing bulk action: ' + error.message, 'error');
                applyBtn.innerHTML = originalText;
                applyBtn.disabled = false;
            });
        }
        
        function loadPagination() {
            // Calculate total pages based on employee count
            const totalEmployees = <?php echo mysqli_num_rows($employees_result); ?>;
            const totalPages = Math.ceil(totalEmployees / itemsPerPage);
            
            if (totalPages <= 1) return;
            
            const paginationDiv = document.getElementById('pagination');
            let html = '';
            
            // Previous button
            html += `<div class="page-link" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'style="opacity:0.5;cursor:not-allowed"' : ''}>
                        <i class="fas fa-chevron-left"></i>
                     </div>`;
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    html += `<div class="page-link ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</div>`;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    html += `<div class="page-link" style="cursor:default">...</div>`;
                }
            }
            
            // Next button
            html += `<div class="page-link" onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'style="opacity:0.5;cursor:not-allowed"' : ''}>
                        <i class="fas fa-chevron-right"></i>
                     </div>`;
            
            paginationDiv.innerHTML = html;
        }
        
        function changePage(page) {
            if (page < 1 || page > Math.ceil(<?php echo mysqli_num_rows($employees_result); ?> / itemsPerPage)) {
                return;
            }
            
            currentPage = page;
            loadPagination();
            // In real application, load page data via AJAX
            showToast(`Loading page ${page}...`, 'info');
        }
        
        // Utility functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }
        
        function showToast(message, type) {
            const toast = document.getElementById('toast');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                <span>${message}</span>
            `;
            
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    </script>
</body>
</html>