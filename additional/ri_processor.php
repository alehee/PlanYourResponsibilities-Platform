<?php
session_start();
require_once("func.php");

$conn = connect();

$conn -> query("SET CHARSET utf8");
$conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

$ri_processor_id = $_SESSION["id"];
$ri_processor_forwho = $_SESSION["ri_forwho"];

// OTWIERA STRONĘ RI
if(isset($_GET["ri_id"])){
	$_SESSION["ri_forwho"] = $_GET["ri_id"];
	unset($_GET["ri_id"]);

	echo "<script>nav_classic_link('user_ri_jobs.php')</script>";
}

// DODAJE OSOBĘ DO RI
if(isset($_GET["ri_create"])){
	$ri_forwho_id = $_GET["ri_create"];
	unset($_GET["ri_create"]);

	$sql = "UPDATE users SET RI='$ri_processor_id' WHERE ID='$ri_forwho_id'";
	$conn -> query($sql);

	$_SESSION["error"] = "Dodano poprawnie! Możesz zaczynać RI!";
	echo "<script>nav_classic_link('user_ri.php')</script>";
}

// DODAJ LUB ZMIEŃ TREŚĆ ZADANIA RI
if(isset($_GET["update"])){
	$ri_task_number = $_GET["ri_job"];
	$ri_task_info = $_GET["new"];
	$ri_task_month = $_GET["month"];
	$ri_task_id = "";
	$ri_task_exist = false;

	$sql = "SELECT ID FROM job_ri WHERE Identificator='$ri_task_number'";
	$que = $conn -> query($sql);
	while($res = mysqli_fetch_array($que)){
		$ri_task_exist = true;
		$ri_task_id = $res["ID"];
	}

	if($ri_task_exist == true)
		$sql = "UPDATE job_ri SET Info='$ri_task_info', EditTimestamp=CURRENT_TIMESTAMP WHERE ID='$ri_task_id' AND ForWho='$ri_processor_forwho'";
	else
		$sql = "INSERT INTO job_ri(ID, ForWho, Identificator, Info, Completed, Month, EditTimestamp) VALUES(NULL, '$ri_processor_forwho', '$ri_task_number', '$ri_task_info', 'false', '$ri_task_month', CURRENT_TIMESTAMP)";

	$conn -> query($sql);

	unset($_GET["ri_job"]);
	unset($_GET["update"]);
	unset($_GET["new"]);
	unset($_GET["month"]);
}

// USUNIĘCIE ZADANIA RI
if(isset($_GET["delete"])){
	$ri_task_number = $_GET["ri_job"];

	$sql = "DELETE FROM job_ri WHERE Identificator='$ri_task_number' AND ForWho='$ri_processor_forwho'";
	$conn -> query($sql);

	unset($_GET["ri_job"]);
	unset($_GET["delete"]);
}

// OZNACZ LUB ODZNACZ WYKONANIE ZADANIA RI
if(isset($_GET["check"])){
	$ri_task_number = $_GET["ri_job"];
	$ri_task_completed = false;

	$sql = "SELECT ID FROM job_ri WHERE Identificator='$ri_task_number' AND ForWho='$ri_processor_forwho' AND Completed='true'";
	$que = $conn -> query($sql);
	while($res = mysqli_fetch_array($que)){
		$ri_task_completed = true;
	}

	if($ri_task_completed == true)
		$sql = "UPDATE job_ri SET Completed='false' WHERE Identificator='$ri_task_number' AND ForWho='$ri_processor_forwho'";
	else
		$sql = "UPDATE job_ri SET Completed='true' WHERE Identificator='$ri_task_number' AND ForWho='$ri_processor_forwho'";

	$conn -> query($sql);

	unset($_GET["ri_job"]);
	unset($_GET["check"]);
}

$conn -> close();
?>