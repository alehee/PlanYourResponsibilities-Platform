<?php
session_start();
require_once("../connection.php");

$conn = @new mysqli($host, $user_db, $password_db, $db_name);

if(isset($_GET["msg_id"])){
    $msg_id = $_GET["msg_id"];

    $sql = "DELETE FROM chat WHERE ID=$msg_id";
    $conn -> query($sql);

    unset($_GET["msg_id"]);
}

$conn -> close();
echo "<script>window.location.reload()</script>";
//header("location:../user.php");
?>