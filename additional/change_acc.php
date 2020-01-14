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
    echo ("<script>window.location = 'profile.php'</script>");
}

// ZMIEŃ NAZWISKO
else if(isset($_GET["nazwisko"])){

    $nazwisko = $_GET["nazwisko"];
    $sql = "UPDATE users SET Nazwisko='$nazwisko' WHERE ID='$id'";
    $conn -> query($sql);

    unset($_GET["nazwisko"]);
    echo ("<script>window.location = 'profile.php'</script>");
}

// ZMIEŃ LOGIN
else if(isset($_GET["login"])){

    $login = $_GET["login"];
    $sql = "UPDATE users SET Login='$login' WHERE ID='$id'";
    $conn -> query($sql);

    unset($_GET["login"]);
    echo ("<script>window.location = 'profile.php'</script>");
}

// ZMIEŃ HASŁO
else if(isset($_GET["haslo"])){

    $haslo = $_GET["haslo"];
    $sql = "UPDATE users SET Password='$haslo' WHERE ID='$id'";
    $conn -> query($sql);

    unset($_GET["haslo"]);
    echo ("<script>window.location = 'profile.php'</script>");
}

// ZMIEŃ EMAIL
else if(isset($_GET["email"])){

    $email = $_GET["email"];
    $sql = "UPDATE users SET Email='$email' WHERE ID='$id'";
    $conn -> query($sql);

    unset($_GET["email"]);
    echo ("<script>window.location = 'profile.php'</script>");
}

// ZMIEŃ ZDJĘCIE
else if(isset($_FILES['photo'])){
    $plik_tmp = $_FILES['photo']['tmp_name'];
    if(is_uploaded_file($plik_tmp)){
        //move_uploaded_file($plik_tmp, "../photo/".$id.".png");
        imagepng(imagecreatefromstring(file_get_contents($plik_tmp)), "../photo/".$id.".png");
    }

    unset($_FILES['photo']);
    echo ("<script>window.location = '../profile.php'</script>");
}

$conn -> close();
?>