<?php
    session_start();

    if(isset($_GET["id"]) && isset($_SESSION["id"])){
        $done_job_id = $_GET["id"];
        $done_user_id = $_SESSION["id"];

        require_once("../connection.php");
        $conn = @new mysqli($host, $user_db, $password_db, $db_name);

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        $sql="SELECT * FROM job WHERE The_ID=$done_job_id AND ForWho=$done_user_id";

        $conn -> close();
        echo "<script>location.reload()</script>";
    }
?>