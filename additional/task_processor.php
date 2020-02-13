<?php
session_start();
require_once("func.php");

$id = $_SESSION["id"];
$conn = connect();

$conn -> query("SET CHARSET utf8");
$conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

if(isset($_GET["update"])){
	$old_task = $_GET["old"];
	$new_task = $_GET["new"];
	$sql = "";

	if($old_task == ""){
		$sql = "INSERT INTO task (ID, WhoAdd, Info) VALUES (NULL, '$id', '$new_task')";
	}

	else{
		$sql = "UPDATE task SET Info='$new_task' WHERE Info='$old_task' AND WhoAdd='$id'";
	}

	$conn -> query($sql);

	unset($_GET["update"]);
}

else if(isset($_GET["complete"])){
	$the_task = $_GET["the_task"];

	$sql = "DELETE FROM task WHERE WhoAdd='$id' AND Info='$the_task'";
	$conn -> query($sql);

	unset($_GET["complete"]);
}

?>