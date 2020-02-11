<?php
session_start();
require_once("../connection.php");

$conn = @new mysqli($host, $user_db, $password_db, $db_name);

$id = $_SESSION["id"];

if(isset($_POST["reset_option"])){

    $reset_option = $_POST["reset_option"];

    $reset_password_text = $_POST["reset_text"];

    $sql = "";

    switch($reset_option){
        case 1:

        break;
        case 2:

        break;
        case 3:

        break;
        default:
            $_SESSION["error"]="Niepoprawne dane dla zmiany hasła!";
        break;
    }
}

header("location:../reset-password.php");

$conn -> close();
?>