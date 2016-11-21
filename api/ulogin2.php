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
			$response["d_email"]=$uname2;
			$response["u_id"]=$_SESSION['u_id'];
			$response["u_name"]=$row["u_name"];
			$response["u_type"]=$row["u_type"];
			$response["u_phone"]=$row["u_phone"];
			$response["images"]=array();
			
			if($response["u_type"]==1)		//doctor
			{
				
				$response2=array();
					$response2["type"] = "getAllImages";
					$response2["success"] = "true";
					$response2["message"] = "Getting all Images..";
				$sql ="Select * from image_master";
				$result = mysqli_query($conn,$sql);
				$iid=array();
				$url=array();
				$u_email=array();
				$class=array();
				$i=0;
				$response2["iid"] = array();
					$response2["url"] = array();
					$response2["u_email"] = array();
					$response2["class"] = array();
				if (mysqli_num_rows($result) > 0) 
				{
					while ($row=mysqli_fetch_array($result)) {
								
								// successfully GET all task
								array_push($response2["iid"],$row['i_id']);
								array_push($response2["url"],$row['image']);
								array_push($response2["u_email"],$row['u_email']);
								array_push($response2["class"],$row['class1']);
								$i++;
								
								
					}
					//var_dump($postsT);
					
					
					
				
					
					
				
					
					
					//echo json_encode($response2);
			}
			
			array_push($response["images"],$response2);
			// echoing JSON response
			$v1=json_encode($response);
			$v2=str_replace("[","{",$v1);
			$v3=str_replace("]","}",$v2);
			echo $v3;
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
}
?>