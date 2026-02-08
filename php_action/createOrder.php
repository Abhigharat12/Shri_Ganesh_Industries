<?php 	
//ALTER TABLE `orders` ADD `payment_place` INT NOT NULL AFTER `payment_status`;
//TER TABLE `orders` ADD `gstn` VARCHAR(255) NOT NULL AFTER `payment_place`;
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'order_id' => '');
// print_r($valid);
if($_POST) {	

	$orderDate 						= date('Y-m-d', strtotime($_POST['orderDate']));	
  $clientName 					= $_POST['clientName'];
  $clientContact 				= $_POST['clientContact'];
//$productname 				= $_POST['product'];
  /*$delivery_staff  				= $_POST['delivery_staff'];
  $delivery_status 				= $_POST['delivery_status'];*/
  $subTotalValue 				= $_POST['subTotalValue'];
  $vatValue 						=	$_POST['vatValue'];
  $totalAmountValue     = $_POST['totalAmountValue'];
  $discount 						= $_POST['discount'];
  $grandTotalValue 			= $_POST['grandTotalValue'];
  $paid 								= $_POST['paid'];
  $dueValue 						= $_POST['dueValue'];
  $paymentType 					= $_POST['paymentType'];
  $paymentStatus 				= $_POST['paymentStatus'];
  $paymentPlace 				= $_POST['paymentPlace'];
  $gstn 				= $_POST['gstn'];
  $userid 				= $_SESSION['userId'];

				
	$sql = "INSERT INTO orders(order_date,client_name, client_contact, sub_total, vat, total_amount, discount, grand_total, paid, due, payment_type, payment_status,payment_place, gstn,order_status,user_id) VALUES ('$orderDate','$clientName', '$clientContact', '$subTotalValue', '$vatValue', '$totalAmountValue', '$discount', '$grandTotalValue', '$paid', '$dueValue', '$paymentType', '$paymentStatus','$paymentPlace','$gstn', 1,'$userid')";
	
	$order_id;
	$orderStatus = false;
	if($connect->query($sql) === true) {
		$order_id = $connect->insert_id;
		$valid['order_id'] = $order_id;	

		$orderStatus = true;

       $orderItemStatus = false;

	for($x = 0; $x < count($_POST['productName']); $x++) {			
		$updateProductQuantitySql = "SELECT * FROM product WHERE product.product_id = ".$_POST['productName'][$x]."";
		$updateProductQuantityData = $connect->query($updateProductQuantitySql);
		
		
		while ($updateProductQuantityResult = $updateProductQuantityData->fetch_array()) {
			$productname[$x] = $updateProductQuantityResult['product_id'] ;							
				
				// add into order_item
				$orderItemSql = "INSERT INTO order_item (order_id, productName,  rate, total, added_date) 
				VALUES ('$order_id', '".$_POST['productName'][$x]."', '".$_POST['rateValue'][$x]."', '".$grandTotalValue."', '$orderDate' )";

				$connect->query($orderItemSql);		

				if($x == count($_POST['productName'])) {
					$orderItemStatus = true;
				}		
		} // while	
	} // /for quantity

	$valid['success'] = true;
	$valid['messages'] = "Successfully Added";	
	echo json_encode($valid);
	header('location:fetchOrder.php');		
	
//	$connect->close();

	}else{
		echo $connect->error.'\n';
		echo $sql;
		//exit;
	}


		
	// echo $_POST['productName'];
	
// add into order_item
				
				

 
} // /if $_POST
// echo json_encode($valid);
