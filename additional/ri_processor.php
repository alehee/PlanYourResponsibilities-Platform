<?php
session_start();
require_once("func.php");

$conn = connect();

$conn -> query("SET CHARSET utf8");
$conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

if(isset($_POST["ri_id"])){
	echo "<script>nav_classic_link('main.php')</script>";
}

?>