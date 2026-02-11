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

$sql = "SELECT * FROM users";
$result = $connect->query($sql);
?>

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary"><i class="fa fa-users"></i> User Management</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title"><i class="fa fa-list"></i> All Users</h4>
                    <a href="add_user.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add User</a>
                </div>

                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th><i class="fa fa-user"></i> Username</th>
                                <th><i class="fa fa-lock"></i> Password</th>
                                <th><i class="fa fa-shield"></i> Role</th>
                                <th><i class="fa fa-cogs"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 1;
                            foreach ($result as $row) {
                                $passwordMasked = str_repeat('*', strlen($row['password']));
                            ?>
                            <tr>
                                <td><?php echo $counter++; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td>
                                    <span id="password-<?php echo $row['user_id']; ?>" class="password-masked"><?php echo $passwordMasked; ?></span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary ml-2" onclick="togglePassword(<?php echo $row['user_id']; ?>, '<?php echo addslashes($row['password']); ?>')">
                                        <i class="fa fa-eye" id="eye-icon-<?php echo $row['user_id']; ?>"></i>
                                    </button>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo $row['role'] === 'admin' ? 'danger' : 'info'; ?>">
                                        <?php echo ucfirst($row['role']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edituser.php?id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <a href="php_action/removeUser.php?id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-danger ml-1" onclick="return confirm('Are you sure to delete this user?')">
                                        <i class="fa fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
</script>

<?php include('./constant/layout/footer.php'); ?>


