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
if (!isset($_GET['employee_id']) || empty($_GET['employee_id'])) {
    header('location: employees.php');
    exit();
}

$employee_id = intval($_GET['employee_id']);

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

// Get filter parameters
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Build query with filters
$whereClause = "WHERE employee_id = ?";
$params = [$employee_id];
$types = "i";

if (!empty($start_date)) {
    $whereClause .= " AND system_record_date >= ?";
    $params[] = $start_date;
    $types .= "s";
}

if (!empty($end_date)) {
    $whereClause .= " AND system_record_date <= ?";
    $params[] = $end_date;
    $types .= "s";
}

// Get work logs
$sql = "SELECT * FROM work_logs $whereClause ORDER BY system_record_date DESC, id DESC";
$stmt = $connect->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$workLogs = [];
$totalHours = 0;
$totalOvertime = 0;

while ($row = $result->fetch_assoc()) {
    $workLogs[] = $row;
    $totalHours += floatval($row['hours']);
    $totalOvertime += floatval($row['overtime']);
}
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
            <h3 class="text-primary"><i class="fa fa-clock"></i> Work Records</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item"><a href="employees.php">Employees</a></li>
                <li class="breadcrumb-item active">Work Records: <?php echo htmlspecialchars($employee['name']); ?></li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Employee Info & Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4><i class="fa fa-user"></i> <?php echo htmlspecialchars($employee['name']); ?></h4>
                        <p class="text-muted">
                            <i class="fa fa-envelope"></i> <?php echo htmlspecialchars($employee['email']); ?>
                            <?php if (!empty($employee['contact'])): ?>
                                | <i class="fa fa-phone"></i> <?php echo htmlspecialchars($employee['contact']); ?>
                            <?php endif; ?>
                            <?php if (!empty($employee['google_sheet_id'])): ?>
                                | <i class="fa fa-google"></i> Sheet: <a href="https://docs.google.com/spreadsheets/d/<?php echo $employee['google_sheet_id']; ?>" target="_blank"><?php echo htmlspecialchars($employee['google_sheet_id']); ?></a>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h4 class="text-white"><?php echo count($workLogs); ?></h4>
                        <p class="text-white">Total Records</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h4 class="text-white"><?php echo number_format($totalHours, 2); ?></h4>
                        <p class="text-white">Total Hours</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h4 class="text-white"><?php echo number_format($totalOvertime, 2); ?></h4>
                        <p class="text-white">Total Overtime</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="view_work_logs.php" class="form-inline">
                    <input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>">
                    <div class="form-group mr-3">
                        <label for="start_date">From Date:</label>
                        <input type="date" class="form-control ml-2" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                    </div>
                    <div class="form-group mr-3">
                        <label for="end_date">To Date:</label>
                        <input type="date" class="form-control ml-2" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary mr-2"><i class="fa fa-filter"></i> Filter</button>
                    <a href="view_work_logs.php?employee_id=<?php echo $employee_id; ?>" class="btn btn-secondary"><i class="fa fa-times"></i> Clear</a>
                    <a href="php_action/syncWorkLogs.php?employee_id=<?php echo $employee_id; ?>" class="btn btn-success ml-auto" onclick="return confirm('Sync work logs from Google Sheet?')">
                        <i class="fa fa-sync"></i> Sync Now
                    </a>
                </form>
            </div>
        </div>

        <!-- Work Logs Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="workLogsTable" class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th><i class="fa fa-calendar"></i> Editable Date</th>
                                <th><i class="fa fa-calendar-check-o"></i> System Date</th>
                                <th><i class="fa fa-align-left"></i> Description</th>
                                <th><i class="fa fa-clock"></i> Hours</th>
                                <th><i class="fa fa-hourglass-half"></i> Overtime</th>
                                <th><i class="fa fa-comment"></i> Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 1;
                            if (count($workLogs) > 0) {
                                foreach ($workLogs as $row) {
                            ?>
                            <tr>
                                <td><?php echo $counter++; ?></td>
                                <td><?php echo !empty($row['editable_date']) ? date('Y-m-d', strtotime($row['editable_date'])) : '-'; ?></td>
                                <td><?php echo !empty($row['system_record_date']) ? date('Y-m-d', strtotime($row['system_record_date'])) : '-'; ?></td>
                                <td><?php echo htmlspecialchars($row['description'] ?? '-'); ?></td>
                                <td><?php echo number_format(floatval($row['hours']), 2); ?></td>
                                <td><?php echo number_format(floatval($row['overtime']), 2); ?></td>
                                <td><?php echo htmlspecialchars($row['remarks'] ?? '-'); ?></td>
                            </tr>
                            <?php 
                                }
                            } else {
                                echo '<tr><td colspan="7" class="text-center">No work logs found. Click "Sync Now" to fetch from Google Sheet.</td></tr>';
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <th colspan="4" class="text-right">Total:</th>
                                <th><?php echo number_format($totalHours, 2); ?></th>
                                <th><?php echo number_format($totalOvertime, 2); ?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#workLogsTable').DataTable({
        "order": [[1, "desc"]],
        "pageLength": 25
    });
});
</script>

<?php include('./constant/layout/footer.php'); ?>
