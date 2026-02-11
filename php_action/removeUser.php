<?php 	

require_once 'core.php';


$valid['success'] = array('success' => false, 'messages' => array());

$userid = intval($_GET['id']);

if($userid) {
    $stmt = $connect->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userid);

    if($stmt->execute()) {
        $_SESSION['success'] = "User removed successfully!";
        header('location:../add_user.php');
    } else {
        $_SESSION['error'] = "Error while removing the user.";
        header('location:../add_user.php');
    }

    $stmt->close();
 
 $connect->close();

 echo json_encode($valid);
 
} // /if $_POST
