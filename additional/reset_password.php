<?php
session_start();
require_once("../connection.php");

$conn = @new mysqli($host, $user_db, $password_db, $db_name);

$id = $_SESSION["id"];

$conn -> query("SET CHARSET utf8");
$conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

if(isset($_POST["reset_option"])){

    $reset_option = $_POST["reset_option"];

    $reset_password_text = $_POST["reset_text"];
    $reset_password_text_1 = "";
    $reset_password_text_2 = "";
    $reset_password_text_break = false;
    $reset_login = "";

    for($i=0; $i<strlen($reset_password_text); $i++){
        if($reset_password_text[$i] != " " && $reset_password_text_break == false)
            $reset_password_text_1=$reset_password_text_1.$reset_password_text[$i];
        else if($reset_password_text[$i] != " " && $reset_password_text_break == true)
            $reset_password_text_2=$reset_password_text_2.$reset_password_text[$i];
        else
            $reset_password_text_break = true;
    }

    $sql = "";

    switch($reset_option){
        case 1:
            $sql = "SELECT Login FROM users WHERE Login='$reset_password_text'";
        break;
        case 2:
            $sql = "SELECT Login FROM users WHERE Imie='$reset_password_text_1' AND Nazwisko='$reset_password_text_2'";
        break;
        case 3:
            $sql = "SELECT Login FROM users WHERE Email='$reset_password_text'";
        break;
        default:
            $_SESSION["error"]="Niepoprawne dane dla zmiany hasła!";
        break;
    }

    $que = $conn -> query($sql);
    while($res = mysqli_fetch_array($que)){
        $reset_login = $res["Login"];
    }

    $sql = "UPDATE users SET Password='$reset_login' WHERE Login = '$reset_login'";
    $conn -> query($sql);

    $reset_status = 0;

    $sql = "SELECT ID FROM users WHERE Login='$reset_login' AND Password='$reset_login'";
    $que = $conn -> query($sql);
    while($res = mysqli_fetch_array($que))
        $reset_status = 1;

    if($reset_status == 1)
        $_SESSION["error"]="Zresetowano hasło poprawnie";

    if($reset_status == 0)
        $_SESSION["error"]="Wystąpił błąd";
}

header("location:../reset-password.php");

$conn -> close();
?>