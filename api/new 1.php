<?php
include 'db.php';
session_id($_POST['session_id']);
session_start();
$response = array();
//if($_SERVER['REQUEST_METHOD']=='POST'){
 $email=$_SESSION['u_email'];
 $image = $_POST['image'];

 
 $sql ="SELECT i_id FROM image_master ORDER BY i_id ASC";
 
 $res = mysqli_query($conn,$sql);
 
 $id = 0;
 
 while($row = mysqli_fetch_array($res)){
		$id = $row['i_id'];
 }
 
 $path = "images/$id.jpg";
 
 $actualpath = "http://tanvas.000webhostapp.com/$path";
 
 $sql = "INSERT INTO image_master (u_email,image) VALUES ('$email','$actualpath')";
 $response["error"] = "ppopp";
 if(mysqli_query($conn,$sql)){
		file_put_contents($path,base64_decode($image));
		$response["type"] = "upload";
		$response["success"] = "true";
		$response["message"] = "Uploaded";
		echo json_encode($response);
 }
 

 else{
		$response["type"] = "upload";
		$response["success"] = "false";
		$response["message"] = "Some Error Occured wwwwwww";
		echo json_encode($response);
 }
/*}
else
{
$response["type"] = "upload";
					$response["success"] = "false";
					$response["message"] = "Some Error Occured";
echo json_encode($response);
}*/
?>