<?php
session_start();
require_once("func.php");

$conn = connect();

$conn -> query("SET CHARSET utf8");
$conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

$ri_processor_id = $_SESSION["id"];

// OTWIERA STRONĘ RI
if(isset($_GET["ri_id"])){
	echo "<script>nav_classic_link('user_ri.php')</script>";
}

// DODAJE OSOBĘ DO RI
if(isset($_GET["ri_create"])){
	$ri_forwho_id = $_GET["ri_create"];
	$sql = "UPDATE users SET RI='$ri_processor_id' WHERE ID='$ri_forwho_id'";
	$conn -> query($sql);

	$_SESSION["error"] = "Dodano poprawnie! Możesz zaczynać RI!";
	echo "<script>nav_classic_link('user_ri.php')</script>";
}

$conn -> close();
?>