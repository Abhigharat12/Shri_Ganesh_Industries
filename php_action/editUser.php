<?php

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {

	$userid = $_POST['userid'];
	$edituserName = $_POST['edituserName'];
	$editPassword = password_hash($_POST['editPassword'], PASSWORD_DEFAULT);
	$editEmail = $_POST['editEmail'];
	$editRole = $_POST['editRole'];

	$sql = "UPDATE users SET username = '$edituserName', password = '$editPassword', email = '$editEmail', role = '$editRole' WHERE user_id = $userid ";

	if($connect->query($sql) === TRUE) {
		$_SESSION['success'] = "User updated successfully!";
		header('location:../add_user.php');
	} else {
		$_SESSION['error'] = "Error while updating the user.";
		header('location:../add_user.php');
	}

	$connect->close();

	echo json_encode($valid);

} // /if $_POST
