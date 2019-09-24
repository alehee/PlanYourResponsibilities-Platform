<?php
session_start();
require_once("../connection.php");

if(isset($_POST['addperson_who']) && isset($_SESSION["the_job"])){
	$addperson_who = $_POST['addperson_who'];
	$addperson_the_job = $_SESSION["the_job"];
	$conn = @new mysqli($host, $user_db, $password_db, $db_name);
	
	$addperson_title;
	$addperson_info;
	$addperson_whoadd;
	$addperson_forwho=$addperson_who;
	$addperson_deadline;

	$sql="SELECT * FROM job WHERE The_ID='$addperson_the_job' LIMIT 1";
	$que = $conn -> query($sql);
	while($res = mysqli_fetch_array($que)){
		$addperson_title=$res["Topic"];
		$addperson_info=$res["Info"];
		$addperson_whoadd=$res["WhoAdd"];
		$addperson_deadline=$res["End"];
	}
	
	$sql = "INSERT INTO job(ID, The_ID, Topic, Info, WhoAdd, ForWho, Start, End) VALUES (NULL, '$addperson_the_job', '$addperson_title', '$addperson_info', '$addperson_whoadd', '$addperson_forwho', CURRENT_TIMESTAMP, '$addperson_deadline')";
	$conn -> query($sql);

	$conn -> close();
	unset($_SESSION["the_job"]);
    unset($_POST["addperson_who"]);
    header("location:../user.php");
}

else
    header("location:../user.php");

?>