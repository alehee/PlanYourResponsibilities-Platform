<?php
session_start();
require_once("../connection.php");

if(isset($_POST['addperson_who']) && isset($_SESSION["the_job"])){
	$addperson_who = $_POST['addperson_who'];
	$addperson_the_job = $_SESSION["the_job"];
	$conn = @new mysqli($host, $user_db, $password_db, $db_name);

	mysqli_query($conn, "SET CHARSET utf8");
    mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
	
	$addperson_title;
	$addperson_info;
	$addperson_type;
	$addperson_whoadd;
	$addperson_forwho=$addperson_who;
	$addperson_deadline;
	$addperson_length;

	$sql="SELECT * FROM job WHERE The_ID='$addperson_the_job' LIMIT 1";
	$que = $conn -> query($sql);
	while($res = mysqli_fetch_array($que)){
		$addperson_title=$res["Topic"];
		$addperson_info=$res["Info"];
		$addperson_type=$res["Type"];
		$addperson_whoadd=$res["WhoAdd"];
		$addperson_deadline=$res["End"];
		$addperson_length = $res["Length"];
	}

	if($addperson_length == 1)
        $mail_length = "Krótkie";
    else if($addperson_length == 2)
        $mail_length = "Średnie";
    else
        $mail_length = "Długie";

	$sql="SELECT Imie, Nazwisko FROM users WHERE ID=$addperson_whoadd";
    $que = mysqli_query($conn, $sql);
    $res = mysqli_fetch_array($que);

    $mail_whoadd = $res["Imie"]." ".$res["Nazwisko"];

    $from = "PlanDeca@riverlakestudios.pl";
    $subject = "[PLANDECA] Nowe zadanie!";
$message = "
Od: ".$mail_whoadd."
Długość: ".$mail_length."

Tytuł: 
".$addperson_title."

Informacje: 
".$addperson_info."

Zaloguj się na plandeca.pl i sprawdź szczegóły!
Wygenerowano: ".date("Y-m-d G:i:s");
    $headers = "From: ".$from;
	
	$sql = "INSERT INTO job(ID, The_ID, Topic, Info, Type, WhoAdd, ForWho, Length, Start, Visited, Visited_Admin, End) VALUES (NULL, '$addperson_the_job', '$addperson_title', '$addperson_info', '$addperson_type', '$addperson_whoadd', '$addperson_forwho', '$addperson_length', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '$addperson_deadline')";
	$conn -> query($sql);

	$sql = "DELETE FROM done WHERE ForWho='$addperson_forwho' AND The_ID='$addperson_the_job'";
	mysqli_query($conn, $sql);

	$sql = "SELECT Email FROM users WHERE ID='$addperson_forwho' LIMIT 1";
    $que = mysqli_query($conn, $sql);
    while($res = mysqli_fetch_array($que)){
        $to = $res["Email"];
        mail($to, $subject, $message, $headers);
    }

	$conn -> close();
	unset($_SESSION["the_job"]);
	unset($_POST["addperson_who"]);
	if($addperson_type=="def")
		header("location:../user.php");
	else
		header("location:../user_staff.php");
}

else
    header("location:../main.php");

?>