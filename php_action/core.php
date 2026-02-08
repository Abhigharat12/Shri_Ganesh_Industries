<?php

session_start();

require_once 'db_connect.php';

// echo $_SESSION['userId'];

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if(!$_SESSION['userId']) {
    if ($isAjax) {
        echo json_encode(['success' => false, 'message' => 'Session expired. Please login.']);
        exit;
    } else {
        header('location:'.$store_url);
        exit;
    }
}



?>
