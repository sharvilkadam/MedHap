<?php			//add a post by the user 
	
session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']) && isset($_GET["response"]))
{
	$d_email=$_SESSION['u_email'];
	$u_email=$_GET["u_email"];
	$res=$_GET["response"];
	$i_id=$_GET["i_id"];
	$conn = dbConnect();
	//$u_email=$_GET['u_email'];
	
	
	 // insert into the post_master table
	$sql ="INSERT INTO response (i_id,d_email,u_email,res) VALUES('$i_id','$d_email','$u_name', '$res)";
	$result = mysqli_query($conn,$sql);
	$id = mysqli_insert_id($conn);
	if ($result) {
			
			// successfully add post
			$response["type"] = "addPost";
			$response["success"] = "true";
			$response["message"] = "Post Added";
			$response["d_email"]=$d_email;
			$response["u_email"]=$u_email;
			$response["i_id"]=$i_id;
			$response["res"]=$res;
			
			echo json_encode($response);
		} else {
			// failed to add task
			$response["type"] = "addPost";
			$response["success"] = "false";
			$response["message"] = "Oops!Falied to add Post";
	 
			// echoing JSON response
			echo json_encode($response);
		} 
}
else {
			// failed to add task
			$response["type"] = "addPost";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
} 
?>
