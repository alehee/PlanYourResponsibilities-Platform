<?php
session_start();
require_once("func.php");

$id = $_SESSION["id"];
$rola = $_SESSION["rola"];

$conn = connect();
$conn -> query("SET CHARSET utf8");
$conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

if($id != 6 && $rola != "kadr"){
	header("location:../main.php");
}

// DODAJ ZADANIE
if(isset($_POST["add_info"])){
	$add_info = $_POST["add_info"];
	$add_date = $_POST["add_date"];

	$sql = "INSERT INTO `hr_tasks`(`ID`, `WhoAdd`, `AddDate`, `Deadline`, `Info`, `Completed`, `WhoCompleted`, `CompletedDate`) VALUES (NULL,$id,CURRENT_TIMESTAMP,'$add_date','$add_info','false',0,CURRENT_TIMESTAMP)";
	$conn -> query($sql);

	unset($_POST["add_info"]);
	unset($_POST["add_date"]);
}

// ZAZNACZ / ODZNACZ ZADANIE
if(isset($_GET["check"])){
	$task_number = $_GET["task_number"];
	$task_completed = false;

	$sql = "SELECT ID FROM hr_tasks WHERE ID='$task_number' AND Completed='true'";
	$que = $conn -> query($sql);
	while($res = mysqli_fetch_array($que)){
		$task_completed = true;
	}

	if($task_completed == true)
		$sql = "UPDATE hr_tasks SET Completed='false' WHERE ID='$task_number'";
	else
		$sql = "UPDATE hr_tasks SET Completed='true', WhoCompleted='$id', CompletedDate=CURRENT_TIMESTAMP WHERE ID='$task_number'";

	$conn -> query($sql);

	unset($_GET["task_number"]);
	unset($_GET["check"]);
}

// USUNIĘCIE ZADANIA
if(isset($_GET["delete"])){
	$task_number = $_GET["task_number"];

	$sql = "DELETE FROM hr_tasks WHERE ID='$task_number'";
	$conn -> query($sql);

	unset($_GET["task_number"]);
	unset($_GET["delete"]);
}

// ZMIEŃ TREŚĆ NOTATKI DNIA
if(isset($_GET["update"])){

	$sql = "";
	$task_number = $_GET["task_number"];
	$info = $_GET["new"];

	// NOTATKA RANO
	if($_GET["update"] == 1){
		$sql = "UPDATE hr_tasks SET Info='$info', WhoAdd='$id' WHERE ID='$task_number'";
	}

	// NOTATKA POPO
	else if($_GET["update"] == 2){
		$sql = "UPDATE hr_tasks SET InfoAdd='$info', WhoCompleted='$id' WHERE ID='$task_number'";
	}

	// NOTATKA ZADANIE
	else if($_GET["update"] == 3){
		$sql = "UPDATE hr_tasks SET InfoAdd='$info' WHERE ID='$task_number'";
	}
	
	$conn -> query($sql);

	unset($_GET["task_number"]);
	unset($_GET["new"]);
	unset($_GET["update"]);
}

$_SESSION["hr_reload"] = true;
header("location:../hr_tasks.php");
$conn -> close();
?>