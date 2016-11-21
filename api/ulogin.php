<?php 		//this is the API no use of starting the session here,. try to Start the session at a new php (process.php)
			//for login

session_start();
include('db.php');

$response = array();

if (isset($_GET['u_email']) && isset($_GET['u_pass'])){

	$conn = dbConnect();
	
	$email=mysqli_real_escape_string($conn,$_GET['u_email']);
	$pass=mysqli_real_escape_string($conn,$_GET['u_pass']);
	 // insert into the user_master table
	$fetch=mysqli_query($conn,"SELECT u_email,u_name,u_phone,u_id,u_type FROM user_master WHERE u_email='$email' and u_pass='$pass'");
	//$count=mysql_num_rows($fetch);
	if($row = mysqli_fetch_array($fetch))
		{
			$_SESSION['u_email']=$row["u_email"];
			$_SESSION['u_name']=$row["u_name"];
			$_SESSION['u_id']=$row["u_id"];
			// successfully login
			$uname2=$row["u_email"];
			
			$response["type"] = "ulogin";
			$response["success"] = "true";
			$response["message"] = "You are logged in.";
			$response["u_email"]=$uname2;
			$response["u_id"]=$_SESSION['u_id'];
			$response["u_name"]=$row["u_name"];
			$response["u_type"]=$row["u_type"];
			$response["u_phone"]=$row["u_phone"];

			// echoing JSON response
			echo json_encode($response);
		} else {
			// failed to login
			$response["type"] = "ulogin";
			$response["success"] = "false";
			$response["message"] = "Oops!Please enter the right Contact and password";
	 
			// echoing JSON response
			echo json_encode($response);
		} 
	}	
	else {
			// required field is missing
			$response["type"] = "ulogin";
			$response["success"] = "false";
			$response["message"] = "Required field(s) is missing";
		 
			// echoing JSON response
			echo json_encode($response);
	}

?>