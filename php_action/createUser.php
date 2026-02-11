<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {

	$userName = $_POST['userName'];
	$upassword = password_hash($_POST['upassword'], PASSWORD_DEFAULT);
	$uemail = $_POST['uemail'];
	$role = isset($_POST['role']) ? $_POST['role'] : 'user';

	$stmt = $connect->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
	$stmt->bind_param("ssss", $userName, $upassword, $uemail, $role);

	if($stmt->execute()) {
		$_SESSION['success'] = "User added successfully!";
		header('location:../add_user.php');
	} else {
		$_SESSION['error'] = "Error while adding the user.";
		header('location:../add_user.php');
	}

	$stmt->close();

				// /else	
		
	} // if in_array 		

	$connect->close();

	echo json_encode($valid);
 

