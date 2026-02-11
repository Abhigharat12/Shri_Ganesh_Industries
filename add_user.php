<?php
include('./constant/layout/head.php');
include('./constant/layout/header.php');
include('./constant/layout/sidebar.php');
include('./constant/connect.php');

// Authorization check: Only admins can access
if (!isset($_SESSION['userId'])) {
    header('location: login.php');
    exit();
}
$userId = $_SESSION['userId'];
$sql = "SELECT role FROM users WHERE user_id = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if ($user['role'] !== 'admin') {
    echo "<script>alert('Access denied. Only admins can access this page.'); window.location.href='dashboard.php';</script>";
    exit();
}
$stmt->close();

// Display session messages as toasters
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
            <h3 class="text-primary"><i class="fa fa-user-plus"></i> Add User</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Add User</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Top Section: Add New User -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0"><i class="fa fa-user-plus"></i> Add New User</h4>
                    </div>
                    <div class="card-body">
                        <form id="addUserForm" method="POST" action="php_action/createUser.php" novalidate>
                            <div class="form-group">
                                <label for="userName"><i class="fa fa-user"></i> Username</label>
                                <input type="text" class="form-control" id="userName" name="userName" placeholder="Enter username" required pattern="^[a-zA-Z0-9]+$">
                                <div class="invalid-feedback">Please enter a valid username (alphanumeric only).</div>
                            </div>
                            <div class="form-group">
                                <label for="upassword"><i class="fa fa-lock"></i> Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="upassword" name="upassword" placeholder="Enter password" required minlength="6">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="invalid-feedback">Password must be at least 6 characters long.</div>
                            </div>
                            <div class="form-group">
                                <label for="uemail"><i class="fa fa-envelope"></i> Email</label>
                                <input type="email" class="form-control" id="uemail" name="uemail" placeholder="Enter email" required>
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>
                            <div class="form-group">
                                <label for="role"><i class="fa fa-shield"></i> Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                                <div class="invalid-feedback">Please select a role.</div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> Add User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section: User List -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="card-title mb-0"><i class="fa fa-list"></i> User List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="userTable" class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th><i class="fa fa-user"></i> Username</th>
                                        <th><i class="fa fa-envelope"></i> Email</th>
                                        <th><i class="fa fa-lock"></i> Password</th>
                                        <th><i class="fa fa-shield"></i> Role</th>
                                        <th><i class="fa fa-info-circle"></i> Status</th>
                                        <th><i class="fa fa-calendar"></i> Created Date</th>
                                        <th><i class="fa fa-cogs"></i> Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM users ORDER BY created_date DESC";
                                    $result = $connect->query($sql);
                                    $counter = 1;
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $passwordMasked = str_repeat('*', strlen($row['password']));
                                            $statusBadge = $row['status'] === 'active' ? 'success' : 'secondary';
                                            $createdDate = date('Y-m-d H:i:s', strtotime($row['created_date']));
                                    ?>
                                    <tr>
                                        <td><?php echo $counter++; ?></td>
                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td>
                                            <span id="password-<?php echo $row['user_id']; ?>" class="password-masked"><?php echo $passwordMasked; ?></span>
                                            <button type="button" class="btn btn-sm btn-outline-info ml-2" onclick="togglePassword(<?php echo $row['user_id']; ?>, '<?php echo addslashes($row['password']); ?>')">
                                                <i class="fa fa-eye" id="eye-icon-<?php echo $row['user_id']; ?>"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo $row['role'] === 'admin' ? 'danger' : 'info'; ?>">
                                                <?php echo ucfirst($row['role']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo $statusBadge; ?>">
                                                <?php echo ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $createdDate; ?></td>
                                        <td>
                                            <a href="edituser.php?id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger ml-1" onclick="deleteUser(<?php echo $row['user_id']; ?>)">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="8" class="text-center">No users found.</td></tr>';
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
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordField = document.getElementById('upassword');
    const icon = this.querySelector('i');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Form validation
document.getElementById('addUserForm').addEventListener('submit', function(event) {
    if (!this.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
    }
    this.classList.add('was-validated');
});

// Function to toggle password visibility in table
function togglePassword(userId, actualPassword) {
    const passwordSpan = document.getElementById('password-' + userId);
    const eyeIcon = document.getElementById('eye-icon-' + userId);

    if (passwordSpan.classList.contains('password-masked')) {
        passwordSpan.textContent = actualPassword;
        passwordSpan.classList.remove('password-masked');
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        const masked = '*'.repeat(actualPassword.length);
        passwordSpan.textContent = masked;
        passwordSpan.classList.add('password-masked');
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}

// Function to delete user with confirmation
function deleteUser(userId) {
    swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function(isConfirm) {
        if (isConfirm) {
            window.location.href = 'php_action/removeUser.php?id=' + userId;
        }
    });
}

// Show toaster notifications
<?php if (isset($_SESSION['success'])): ?>
    toastr.success('<?php echo addslashes($_SESSION['success']); ?>');
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    toastr.error('<?php echo addslashes($_SESSION['error']); ?>');
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
</script>

<?php include('./constant/layout/footer.php'); ?>
