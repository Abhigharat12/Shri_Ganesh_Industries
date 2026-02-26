<?php
include('./constant/layout/head.php');
include('./constant/layout/header.php');
include('./constant/layout/sidebar.php');
include('./constant/connect.php');

// Authorization check
if (!isset($_SESSION['userId'])) {
    header('location: login.php');
    exit();
}

// Get employee count for dashboard
$employeeCount = 0;
$employeeCountSql = "SELECT COUNT(*) as total FROM employees";
$employeeCountResult = $connect->query($employeeCountSql);
if ($employeeCountResult && $employeeCountResult->num_rows > 0) {
    $employeeCount = $employeeCountResult->fetch_assoc()['total'];
}

// Get active employee count
$activeEmployeeCount = 0;
$activeEmployeeSql = "SELECT COUNT(*) as active FROM employees WHERE status = 'active'";
$activeEmployeeResult = $connect->query($activeEmployeeSql);
if ($activeEmployeeResult && $activeEmployeeResult->num_rows > 0) {
    $activeEmployeeCount = $activeEmployeeResult->fetch_assoc()['active'];
}

// Get all employees
$sql = "SELECT * FROM employees ORDER BY created_at DESC";
$result = $connect->query($sql);

// Display session messages
if (isset($_SESSION['success'])) {
    echo "<script>swal('Success!', '" . addslashes($_SESSION['success']) . "', 'success');</script>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo "<script>swal('Error!', '" . addslashes($_SESSION['error']) . "', 'error');</script>";
    unset($_SESSION['error']);
}
?>

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary"><i class="fa fa-users"></i> Employee Management</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Employees</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h4 class="text-white"><?php echo $employeeCount; ?></h4>
                        <p class="text-white">Total Employees</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h4 class="text-white"><?php echo $activeEmployeeCount; ?></h4>
                        <p class="text-white">Active Employees</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h4 class="text-white"><?php echo $employeeCount - $activeEmployeeCount; ?></h4>
                        <p class="text-white">Inactive Employees</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title"><i class="fa fa-list"></i> All Employees</h4>
                    <div>
                        <a href="add_employee.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add Employee</a>
                        <a href="php_action/syncAllWorkLogs.php" class="btn btn-success" onclick="return confirm('Sync work logs for all employees? This may take a while.')">
                            <i class="fa fa-sync"></i> Sync All Work Logs
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th><i class="fa fa-user"></i> Name</th>
                                <th><i class="fa fa-envelope"></i> Email</th>
                                <th><i class="fa fa-phone"></i> Contact</th>
                                <th><i class="fa fa-google"></i> Sheet ID</th>
                                <th><i class="fa fa-info-circle"></i> Status</th>
                                <th><i class="fa fa-calendar"></i> Created</th>
                                <th><i class="fa fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 1;
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $statusBadge = $row['status'] === 'active' ? 'success' : 'secondary';
                                    $createdDate = date('Y-m-d', strtotime($row['created_at']));
                            ?>
                            <tr>
                                <td><?php echo $counter++; ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['contact'] ?? '-'); ?></td>
                                <td>
                                    <?php if (!empty($row['google_sheet_id'])): ?>
                                        <span class="text-success"><i class="fa fa-check"></i> Connected</span>
                                    <?php else: ?>
                                        <span class="text-muted"><i class="fa fa-times"></i> Not Set</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo $statusBadge; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $createdDate; ?></td>
                                <td>
                                    <a href="edit_employee.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <a href="view_work_logs.php?employee_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i> View Logs
                                    </a>
                                    <a href="php_action/syncWorkLogs.php?employee_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Sync work logs for <?php echo addslashes($row['name']); ?>?')">
                                        <i class="fa fa-sync"></i> Sync
                                    </a>
                                    <a href="php_action/removeEmployee.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this employee? All associated work logs will also be deleted.')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php 
                                }
                            } else {
                                echo '<tr><td colspan="8" class="text-center">No employees found. <a href="add_employee.php">Add your first employee</a></td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#myTable').DataTable({
        "order": [[6, "desc"]]
    });
});
</script>

<?php include('./constant/layout/footer.php'); ?>
