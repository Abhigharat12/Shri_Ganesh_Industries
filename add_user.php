<?php
include('./constant/connect.php');

$username = 'mailabhishekgharat@gmail.com';
$password = md5('rootadmin');
$email = 'mailabhishekgharat@gmail.com';

$sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email') ON DUPLICATE KEY UPDATE password='$password'";

if ($connect->query($sql) === TRUE) {
    echo "User added or updated successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $connect->error;
}

$connect->close();
?>

