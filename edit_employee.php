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

// Get employee ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('location: employees.php');
    exit();
}

$employee_id = intval($_GET['id']);

// Get employee data
$sql = "SELECT * FROM employees WHERE id = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('location: employees.php');
    exit();
}

$employee = $result->fetch_assoc();
$stmt->close();

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
            <h3 class="text-primary"><i class="fa fa-user-edit"></i> Edit Employee</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item"><a href="employees.php">Employees</a></li>
                <li class="breadcrumb-item active">Edit Employee</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-white">
                        <h4 class="card-title mb-0"><i class="fa fa-user-edit"></i> Edit Employee: <?php echo htmlspecialchars($employee['name']); ?></h4>
                    </div>
                    <div class="card-body">
                        <form id="editEmployeeForm" method="POST" action="php_action/editEmployee.php" novalidate>
                            <input type="hidden" name="employee_id" value="<?php echo $employee['id']; ?>">
                            
                            <div class="form-group">
                                <label for="name"><i class="fa fa-user"></i> Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>" required pattern="^[a-zA-Z\s]+$">
                                <div class="invalid-feedback">Please enter a valid name (letters and spaces only).</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email"><i class="fa fa-envelope"></i> Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact"><i class="fa fa-phone"></i> Contact</label>
                                <input type="text" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($employee['contact'] ?? ''); ?>" pattern="^[0-9+\-\s]{10,15}$">
                                <div class="invalid-feedback">Please enter a valid contact number.</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="google_sheet_id"><i class="fa fa-google"></i> Google Sheet ID</label>
                                <input type="text" class="form-control" id="google_sheet_id" name="google_sheet_id" value="<?php echo htmlspecialchars($employee['google_sheet_id'] ?? ''); ?>">
                                <small class="form-text text-muted">The ID from the employee Google Sheet URL (the long string after /d/)</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="status"><i class="fa fa-toggle-on"></i> Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="active" <?php echo ($employee['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo ($employee['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-warning btn-block"><i class="fa fa-save"></i> Update Employee</button>
                                <a href="employees.php" class="btn btn-secondary btn-block"><i class="fa fa-arrow-left"></i> Back to List</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('editEmployeeForm').addEventListener('submit', function(event) {
    if (!this.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
    }
    this.classList.add('was-validated');
});
</script>

<?php include('./constant/layout/footer.php'); ?>
