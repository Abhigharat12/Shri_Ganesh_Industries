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
            <h3 class="text-primary"><i class="fa fa-user-plus"></i> Add Employee</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item"><a href="employees.php">Employees</a></li>
                <li class="breadcrumb-item active">Add Employee</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0"><i class="fa fa-user-plus"></i> Add New Employee</h4>
                    </div>
                    <div class="card-body">
                        <form id="addEmployeeForm" method="POST" action="php_action/createEmployee.php" novalidate>
                            <div class="form-group">
                                <label for="name"><i class="fa fa-user"></i> Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter employee name" required pattern="^[a-zA-Z\s]+$">
                                <div class="invalid-feedback">Please enter a valid name (letters and spaces only).</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email"><i class="fa fa-envelope"></i> Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" required>
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact"><i class="fa fa-phone"></i> Contact</label>
                                <input type="text" class="form-control" id="contact" name="contact" placeholder="Enter contact number" pattern="^[0-9+\-\s]{10,15}$">
                                <div class="invalid-feedback">Please enter a valid contact number.</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="google_sheet_id"><i class="fa fa-google"></i> Google Sheet ID</label>
                                <input type="text" class="form-control" id="google_sheet_id" name="google_sheet_id" placeholder="Enter Google Sheet ID">
                                <small class="form-text text-muted">The ID from the employee Google Sheet URL (the long string after /d/)</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="status"><i class="fa fa-toggle-on"></i> Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> Add Employee</button>
                                <a href="employees.php" class="btn btn-secondary btn-block"><i class="fa fa-arrow-left"></i> Back to List</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee List Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="card-title mb-0"><i class="fa fa-list"></i> Employee List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="employeeTable" class="table table-striped table-hover">
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
                                    $sql = "SELECT * FROM employees ORDER BY created_at DESC LIMIT 10";
                                    $result = $connect->query($sql);
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
                                            <a href="edit_employee.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="view_work_logs.php?employee_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="8" class="text-center">No employees found.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('addEmployeeForm').addEventListener('submit', function(event) {
    if (!this.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
    }
    this.classList.add('was-validated');
});

$(document).ready(function() {
    $('#employeeTable').DataTable({
        "order": [[6, "desc"]],
        "pageLength": 10
    });
});
</script>

<?php include('./constant/layout/footer.php'); ?>
