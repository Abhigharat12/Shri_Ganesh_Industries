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
    $contact_person = $_POST['contact_person'];

	$sql = "UPDATE `lead` SET lead_name = ?, phone = ?, email = ?, city = ?, interest = ?, source = ?, status = ?, contact_person = ? WHERE id = ?";
	$stmt = $connect->prepare($sql);
	$stmt->bind_param("ssssssisi", $lead_name, $phone, $email, $city, $interest, $source, $status, $contact_person, $Id);

	if($stmt->execute()) {
	 	$_SESSION['success'] = "Successfully Updated";
		header('location:../editlead.php?id='.$Id);
	} else {
	 	$_SESSION['error'] = "Error while updating the lead";
		header('location:../editlead.php?id='.$Id);
	}

	$connect->close();

} // /if $_POST
