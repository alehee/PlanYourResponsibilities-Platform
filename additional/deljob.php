<?php
session_start();
require_once("../connection.php");

if(isset($_GET['deljob_id'])){
	$deljob = $_GET['deljob_id'];
	$conn = @new mysqli($host, $user_db, $password_db, $db_name);

	mysqli_query($conn, "SET CHARSET utf8");
    mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
	
	$sql = "DELETE FROM job WHERE The_ID='$deljob'";
	$conn -> query($sql);

	$sql = "DELETE FROM job_red WHERE The_ID='$deljob'";
	$conn -> query($sql);

	$sql = "DELETE FROM done WHERE The_ID='$deljob'";
	$conn -> query($sql);

	$sql = "DELETE FROM chat WHERE The_ID='$deljob'";
	$conn -> query($sql);

	$conn -> close();
    unset($_GET["deljob_id"]);
    echo '<script>window.location.reload()</script>';
}

else
	echo '<script>window.location.reload()</script>';

?>