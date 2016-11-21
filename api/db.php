<?php // db.php
 
date_default_timezone_set("America/Los_Angeles");

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$db = "tanvas";

/*
$dbhost = "mysql.hostinger.in";
$dbuser = "u862135098_user";
$dbpass = "Unlock@123";
$db = "u862135098_db";
*/

$watson_api_key="10f9ee0aceb813f30c1da1fbfc637c64f18d86b3";		//for the IBM watson api


function dbConnect() {
global $dbhost, $dbuser, $dbpass, $db;
 
$dbcnx = mysqli_connect($dbhost, $dbuser, $dbpass, $db)
or die("The site database appears to be down.");
 
//if ($db!="" and !mysql_select_db($db))
//die("The site database is unavailable.");
 
return $dbcnx;
}
?>