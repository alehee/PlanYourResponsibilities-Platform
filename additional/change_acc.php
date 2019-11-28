<?php
session_start();
require_once("../connection.php");

$conn = @new mysqli($host, $user_db, $password_db, $db_name);

$id = $_SESSION["id"];

// ZMIEŃ IMIĘ
if(isset($_GET["imie"])){

    $imie = $_GET["imie"];
    $sql = "UPDATE users SET Imie='$imie' WHERE ID='$id'";
    $conn -> query($sql);

    unset($_GET["imie"]);
}

// ZMIEŃ NAZWISKO
else if(isset($_GET["nazwisko"])){

    $nazwisko = $_GET["nazwisko"];
    $sql = "UPDATE users SET Nazwisko='$nazwisko' WHERE ID='$id'";
    $conn -> query($sql);

    unset($_GET["nazwisko"]);
}

// ZMIEŃ LOGIN
else if(isset($_GET["login"])){

    $login = $_GET["login"];
    $sql = "UPDATE users SET Login='$login' WHERE ID='$id'";
    $conn -> query($sql);

    unset($_GET["login"]);
}

// ZMIEŃ HASŁO
else if(isset($_GET["haslo"])){

    $haslo = $_GET["haslo"];
    $sql = "UPDATE users SET Password='$haslo' WHERE ID='$id'";
    $conn -> query($sql);

    unset($_GET["haslo"]);
}

// ZMIEŃ EMAIL
else if(isset($_GET["email"])){

    $email = $_GET["email"];
    $sql = "UPDATE users SET Email='$email' WHERE ID='$id'";
    $conn -> query($sql);

    unset($_GET["email"]);
}

// ZMIEŃ ZDJĘCIE

$conn -> close();
echo ("<script>location.reload();</script>");
?>