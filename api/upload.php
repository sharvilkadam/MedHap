<?php

include 'db.php';
session_start();
$response = array();

  
if(isset($_SESSION['u_email']))
{
	$email=$_SESSION['u_email'];
	
	$ext = substr($_FILES['uploaded_file']['name'], strpos($_FILES['uploaded_file']['name'],'.'), strlen($_FILES['uploaded_file']['name'])-1);     
    $imageName = time().$ext;
    $file_path = "images/";
     
    $file_path = $file_path . time().$ext;
    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {
			$sql ="INSERT INTO image_master (u_email,image) VALUES ('$email','$file_path')";
			$result = mysqli_query($conn, $sql);
			$last_id = mysqli_insert_id($conn);
			//classify the images using watson
			
			$gurl="http://localhost/tanvas/getClass.php?i_id=".$last_id;
			$result2= file_get_contents($gurl);
			$ares2=json_decode($result2,true);
			if($ares2["success"]=="true")
			{
				$class=$ares2["class"];
				$score=$ares2["score"];
			}
			else{
				$class=null;
				$score=null;
			}
			$sql3 ="UPDATE image_master SET class1=$class and score1=$score where i_id=".$last_id;
			$result3 = mysqli_query($conn, $sql3);
			
			$response["type"] = "upload";
			$response["success"] = "true";
			$response["message"] = "Image upload Successfully.. Pls be patient..";
			echo json_encode($response);
    } else{
			$response["type"] = "upload";
			$response["success"] = "false";
			$response["message"] = "Image upload Failed";
			echo json_encode($response);
    }
}
else{
		$response["type"] = "upload";
		$response["success"] = "false";
		$response["message"] = "No login";
		echo json_encode($response);
}
			
?>


