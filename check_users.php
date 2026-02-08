<?php
include('./constant/connect.php');

$sql = "SELECT * FROM users";
$result = $connect->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["user_id"]. " - Username: " . $row["username"]. " - Email: " . $row["email"]. "<br>";
    }
} else {
    echo "No users found.";
}

$connect->close();
?>

