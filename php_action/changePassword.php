<?php

require_once 'core.php';

if($_POST) {

	$valid['success'] = array('success' => false, 'messages' => array());

	$currentPassword = $_POST['password'];
	$newPassword = $_POST['npassword'];
	$conformPassword = $_POST['cpassword'];
	$userId = $_POST['user_id'];

	$sql = "SELECT password FROM users WHERE user_id = ?";
	$stmt = $connect->prepare($sql);
	$stmt->bind_param("i", $userId);
	$stmt->execute();
	$result = $stmt->get_result()->fetch_assoc();
	$stmt->close();

	if($result) {
		$storedPassword = $result['password'];

		// Check if password is hashed (starts with $) or MD5
		$passwordValid = false;
		if (substr($storedPassword, 0, 1) === '$') {
			$passwordValid = password_verify($currentPassword, $storedPassword);
		} else {
			$passwordValid = md5($currentPassword) === $storedPassword;
		}

		if($passwordValid) {

			if($newPassword == $conformPassword) {

				$hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

				$updateSql = "UPDATE users SET password = ? WHERE user_id = ?";
				$stmt = $connect->prepare($updateSql);
				$stmt->bind_param("si", $hashedNewPassword, $userId);

				if($stmt->execute()) {
					$valid['success'] = true;
					$valid['messages'] = "Password updated successfully";
				} else {
					$valid['success'] = false;
					$valid['messages'] = "Error while updating the password";
				}

				$stmt->close();

			} else {
				$valid['success'] = false;
				$valid['messages'] = "New password does not match with Confirm password";
			}

		} else {
			$valid['success'] = false;
			$valid['messages'] = "Current password is incorrect";
		}

	} else {
		$valid['success'] = false;
		$valid['messages'] = "User not found";
	}

	$connect->close();

	echo json_encode($valid);

}

?>
