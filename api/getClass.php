<?php			//get all the keyword from the p_title and p_desc from alchemy
//session_start();		//noneed of session here
include('db.php');

$response = array();
if(isset($_GET['i_id']))
{
	$conn = dbConnect();
	
	$iid=$_GET['i_id'];
	$fetch=mysqli_query($conn,"SELECT u_email,image FROM image_master WHERE i_id='$iid'");
	
	if($row = mysqli_fetch_array($fetch))
	{
		$uemail=$row["u_email"];
		$iurl=$row["image"];
		$base="http://localhost/tanvas/api/";
		//$img_url=$base.$iurl;
		$img_url=$iurl;
	
	
		$classifier="derma_37854720";
		$url="https://gateway-a.watsonplatform.net/visual-recognition/api/v3/classify?api_key=$watson_api_key&version=2016-05-20&threshold=0.0&classifier_ids=$classifier&url=$img_url";

		$watson = file_get_contents($url);
		//echo $watson;
		$wres = array();
		$wres = json_decode($watson,true);
		//print_r($wres);
		$warray=$wres["images"][0]["classifiers"][0]["classes"];
		$max=-1;
		foreach($warray as $w)
		{
			//echo $w["class"]." ";
			//echo $w["score"]."<br>";
			if($max<$w["score"]){
				$max=$w["score"];
				$class1=$w["class"];
				$score1=$w["score"];
			}
		}
		//echo $class1." ".$score1;
		$response["type"] = "getClass";
		$response["success"] = "true";
		$response["message"] = "Got the best class";
		$response["class"] = $class1;
		$response["score"] = $score1;
		echo json_encode($response);
		
	}
	else{
		$response["type"] = "getClass";
		$response["success"] = "false";
		$response["message"] = "No Class found..Error from Watson";

		echo json_encode($response);
		
	}
	
}
else{
	$response["type"] = "getClass";
		$response["success"] = "false";
		$response["message"] = "No such Image";
	
		echo json_encode($response);
}
?>