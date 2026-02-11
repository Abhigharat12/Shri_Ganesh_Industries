<?php
include('./constant/connect.php');

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

echo "Connected successfully<br>";

// Check if database exists
$sql = "SHOW DATABASES LIKE 'jaiganesh_industries'";
$result = $connect->query($sql);
if ($result->num_rows > 0) {
    echo "Database 'jaiganesh_industries' exists<br>";
} else {
    echo "Database 'jaiganesh_industries' does not exist<br>";
}

// Select the database
$connect->select_db('jaiganesh_industries');

// Check if users table exists
$sql = "SHOW TABLES LIKE 'users'";
$result = $connect->query($sql);
if ($result->num_rows > 0) {
    echo "Table 'users' exists<br>";

    // Check users in table
    $sql = "SELECT user_id, username, password FROM users";
    $result = $connect->query($sql);
    if ($result->num_rows > 0) {
        echo "Users in table:<br>";
        while($row = $result->fetch_assoc()) {
            echo "ID: " . $row["user_id"]. " - Username: " . $row["username"]. " - Password: " . $row["password"]. "<br>";
        }
    } else {
        echo "No users found in table<br>";
    }
} else {
    echo "Table 'users' does not exist<br>";
}

$connect->close();
?>
