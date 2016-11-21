<?php			//get all the posts associated with the constituency
				//here the posts' feed algorithm is applied and accordingdly the posts are sent to the client
	
session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']))
{
	$email=$_SESSION['u_email'];
	$conn = dbConnect();
	
	$sql ="Select * from response where u_email='$email'";
	$result = mysqli_query($conn,$sql);
	$posts1=array();
	
	if (mysqli_num_rows($result) > 0) 
	{
		$response["type"] = "getAllPosts";
		$response["success"] = "true";
		$response["message"] = "Getting all POsts..";
		$response["posts"]=array();
		while ($row=mysqli_fetch_array($result)) {
					
					$posts1["r_id"] = $row['r_id'];
					$posts1["i_id"] = $row['i_id'];
					$posts1["d_email"] = $row['d_email'];
					$posts1["u_email"] = $row['u_email'];
					$posts1["res"] = $row['res'];
					
					// adding all to the response object
					array_push($response["posts"], $posts1);
					
		}
		
		echo json_encode($response);
		
	}
	else
	{
		// no task found
			$response["type"] = "getAllImages";
			$response["success"] = "false";
			$response["message"] = "Sorry no posts from Doctors Found";
	 
			// echoing JSON response
			echo json_encode($response);
	}
	
	
}


?>