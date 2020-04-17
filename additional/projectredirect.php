<?php
session_start();
require_once("../connection.php");

$conn = @new mysqli($host, $user_db, $password_db, $db_name);

if(isset($_GET["projname"])){
    $buffer = $_GET["projname"];
    $_SESSION["project_name"] = $buffer;
    unset($_GET["projname"]);
}

$conn -> close();
?>