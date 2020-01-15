<?php
session_start();
require_once("../connection.php");

if(isset($_POST['delperson_who']) && isset($_SESSION["the_job"])){
	$delperson_who = $_POST['delperson_who'];
	$delperson_the_job = $_SESSION["the_job"];
	$conn = @new mysqli($host, $user_db, $password_db, $db_name);

	mysqli_query($conn, "SET CHARSET utf8");
    mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
	
	// WRZUCENIE ZADANIA DO WYKONANYCH
	$topic;
    $info;
	$whoadd;
	$length;
    $end;
	$sql="SELECT * FROM job WHERE The_ID=$delperson_the_job AND ForWho=$delperson_who";
	$que = $conn-> query($sql);
	while($res = mysqli_fetch_array($que)){
		$topic = $res["Topic"];
		$info = $res["Info"];
		$whoadd = $res["WhoAdd"];
		$length = $res["Length"];
		$end = $res["End"];
	}
	
	$sql = "DELETE FROM job WHERE The_ID='$delperson_the_job' AND ForWho='$delperson_who'";
	$conn -> query($sql);

	$conn -> close();
	unset($_SESSION["the_job"]);
    unset($_POST["addperson_who"]);
    header("location:../user.php");
}

else
    header("location:../user.php");

?>