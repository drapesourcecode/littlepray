<?php
$host='localhost';
$user='littlepr_donate';
$pwd='!)DnW1^QvKNt';
$db='littlepr_newlittlepray';
$conn=mysqli_connect($host,$user,$pwd);
if (!$conn)
 	{
	die('Couldnot connect');
	}
	else
	{
		//echo "connected successfully";
	}
mysqli_select_db($conn,$db);
?>