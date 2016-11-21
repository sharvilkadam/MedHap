<?php
session_start();

$response = array();
//echo $_SESSION['email'];
if(isset($_SESSION['u_email'])){
	
	if(session_destroy())
	{
		$response["type"] = "ulogout";
		$response["success"] = "true";
		$response["message"] = "You are logged OUT.";
		
		// echoing JSON response
		echo json_encode($response);
		
	}
}
else{
	$response["type"] = "ulogout";
	$response["success"] = "false";
	$response["message"] = "Please Login First to logout..";
	
	// echoing JSON response
	echo json_encode($response);
}
?>