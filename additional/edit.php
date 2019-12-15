<?php
session_start();
require_once("../connection.php");

$conn = @new mysqli($host, $user_db, $password_db, $db_name);

if(isset($_POST["edit_title"]) && isset($_POST["edit_info"]) && isset($_POST["edit_deadline"]) && isset($_POST["edit_length"]) && isset($_SESSION["The_ID"])){
    $edit_title = $_POST["edit_title"];
    $edit_info = $_POST["edit_info"];
    $edit_deadline = $_POST["edit_deadline"];
    $edit_length = $_POST["edit_length"];
    $edit_the_id = $_SESSION["The_ID"];

    $conn -> query("SET CHARSET utf8");
    $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

    $sql = "UPDATE job SET Topic='$edit_title', Info='$edit_info', Length='$edit_length', End='$edit_deadline' WHERE The_ID='$edit_the_id'";
    $conn -> query($sql);

    unset($_POST["edit_title"]);
    unset($_POST["edit_title"]);
    unset($_POST["edit_deadline"]);
    unset($_SESSION["The_ID"]);
}

$conn -> close();
header("location:../user.php");
?>