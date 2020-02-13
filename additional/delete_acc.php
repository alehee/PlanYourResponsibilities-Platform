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
    $delete_id = "";

	// USTAWIENIE DANYCH DLA IMIENIA I NAZWISKA
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
            $sql = "SELECT ID FROM users WHERE Login='$reset_password_text'";
        break;
        case 2:
            $sql = "SELECT ID FROM users WHERE Imie='$reset_password_text_1' AND Nazwisko='$reset_password_text_2'";
        break;
        case 3:
            $sql = "SELECT ID FROM users WHERE Email='$reset_password_text'";
        break;
        default:
            $_SESSION["error"]="Niepoprawne dane dla usunięcia konta!";
        break;
    }

    $que = $conn -> query($sql);
    while($res = mysqli_fetch_array($que)){
        $delete_id = $res["ID"];
    }

	// USUWANIE Z TABEL
    $sql = "DELETE FROM chat WHERE SentFrom='$delete_id'";
    $conn -> query($sql);
    $sql = "DELETE FROM done WHERE ForWho='$delete_id'";
    $conn -> query($sql);
    $sql = "DELETE FROM done WHERE WhoAdd='$delete_id'";
    $conn -> query($sql);
    $sql = "DELETE FROM job WHERE ForWho='$delete_id'";
    $conn -> query($sql);
    $sql = "DELETE FROM job WHERE WhoAdd='$delete_id'";
    $conn -> query($sql);
    $sql = "DELETE FROM job_red WHERE ForWho='$delete_id'";
    $conn -> query($sql);
    $sql = "DELETE FROM users WHERE ID='$delete_id'";
    $conn -> query($sql);

	// SPRAWDZENIE CZY POPRAWNIE USUNIĘTO
    $reset_status = 0;

    $sql = "SELECT ID FROM users WHERE ID='$delete_id'";
	$que = $conn -> query($sql);
    while($res = mysqli_fetch_array($que))
        $reset_status = 1;

    if($reset_status == 0)
        $_SESSION["error"]="Poprawnie usunięto użytkownika";

    if($reset_status == 1)
        $_SESSION["error"]="Wystąpił błąd";
}

header("location:../delete-account.php");

$conn -> close();
?>