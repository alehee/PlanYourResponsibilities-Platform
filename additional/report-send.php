<?php
session_start();
require_once("../connection.php");

if(isset($_POST['report_info'])){
	$conn = @new mysqli($host, $user_db, $password_db, $db_name);

	mysqli_query($conn, "SET CHARSET utf8");
	mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
	
	$report_mail = "";
	$report_info = $_POST['report_info'];
	$id = $_SESSION["id"];

	$sql="SELECT Email FROM users WHERE ID='$id' LIMIT 1";
	$que = $conn -> query($sql);
	while($res = mysqli_fetch_array($que)){
		$report_mail=$res["Email"];
	}

    $from = "PYR@riverlakestudios.pl";
    $subject = "Nowe zgłoszenie na platformie PYR!";
$message = "
Zgłoszenie od: ".$report_mail."

".$report_info."

riverlakestudios.pl/pyr
Wygenerowano: ".date("Y-m-d G:i:s");
    $headers = "From: ".$from;

    mail("aleksander.heese@decathlon.com", $subject, $message, $headers);
	
	$_SESSION["error"]="Dziękujemy za zgłoszenie!";

	$conn -> close();
    unset($_POST["report_info"]);
    header("location:../user.php");
}

else{
	$_SESSION["error"]="Wystąpił błąd podczas wysyłania maila!";

	header("location:../user.php");
}

?>