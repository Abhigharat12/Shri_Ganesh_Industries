<?php 	

require_once 'core.php';

//$valid['success'] = array('success' => false, 'messages' => array());
$Id = $_GET['id'];
if($_POST) {	

  $lead_name = $_POST['lead'];
    $phone = $_POST['phone'];
    $email = $_POST['email']; 
    $city = $_POST['city'];
    $interest = $_POST['interest'];
    $source = $_POST['source'];
    
    $status = $_POST['status'];

	$sql = "UPDATE `lead` SET lead_name = '$lead_name', phone = '$phone', email = '$email', city = '$city', interest = '$interest', source = '$source', status = '$status' WHERE id = '$Id'";

	if($connect->query($sql) === TRUE) {
	 	$valid['success'] = true;
		$valid['messages'] = "Successfully Updated";
		header('location:../lead.php');	
	} else {
	 	$valid['success'] = false;
	 	$valid['messages'] = "Error while updating the categories";
	}
	 
	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST
