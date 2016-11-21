<?php			//get all the posts associated with the constituency
				//here the posts' feed algorithm is applied and accordingdly the posts are sent to the client
	
session_start();
include('db.php');

$response2 = array();
//if(isset($_SESSION['u_email']))
{
	$email=$_SESSION['u_email'];
	$conn = dbConnect();
	
	$sql ="Select * from image_master";
	$result = mysqli_query($conn,$sql);
	$iid=array();
	$url=array();
	$u_email=array();
	$class=array();
	$i=0;
	if (mysqli_num_rows($result) > 0) 
	{
		while ($row=mysqli_fetch_array($result)) {
					
					// successfully GET all task
					$iid[$i]=$row['i_id'];
					$url[$i]=$row['image'];
					$u_email[$i]=$row['u_email'];
					$class[$i]=$row['class1'];
					$i++;
					
					
		}
		//var_dump($postsT);
		
		
		
		$response2["type"] = "getAllImages";
		$response2["success"] = "true";
		$response2["message"] = "Getting all Images..";
		$response2["iid"] = array();
		$response2["url"] = array();
		$response2["u_email"] = array();
		$response2["class"] = array();
		
		array_push($response2["iid"],$iid);
		array_push($response2["url"],$url);
		array_push($response2["u_email"],$u_email);
		array_push($response2["class"],$class);
		
		
		
		echo json_encode($response2);
		
	}
	else
	{
		// no task found
			$response2["type"] = "getAllImages";
			$response2["success"] = "false";
			$response2["message"] = "Sorry no Images Found";
	 
			// echoing JSON response
			echo json_encode($response2);
	}
	
	
}
/*else {
			// no login
			$response2["type"] = "getAllImages";
			$response2["success"] = "false";
			$response2["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response2);
} 
*/

?>