<?php
session_start();
require_once("../connection.php");

$conn = @new mysqli($host, $user_db, $password_db, $db_name);

$id = $_SESSION["id"];

// ZMIEŃ DANE NA PROFILU
if(isset($_POST["option"])){

    $option = $_POST["option"];
    $new_info = $_POST["new_info"];
    $new_password = $_POST["new_pwd"];
    $password = $_POST["pwd"];

    $passwordIsGood = false;

    $sql = "SELECT ID FROM users WHERE ID='$id' AND Password='$password'";
    $que = $conn -> query($sql);
    while($res = mysqli_fetch_array($que)){
        $passwordIsGood = true;
    }

    if($passwordIsGood == true){
        $error = false;

        switch($option){
            case "login":
                $sql = "SELECT ID FROM users WHERE Login='$new_info'";
                $que = $conn -> query($sql);
                while($res = mysqli_fetch_array($que)){
                    $error = true;
                }
                if($error == false){
                    $sql = "UPDATE users SET Login='$new_info' WHERE ID='$id'";
                    $conn -> query($sql);
                    $_SESSION["error"] = "Zaktualizowano login poprawnie!";
                }
                else{
                    $_SESSION["error"] = "Taki login już istnieje! Wybierz inny.";
                }
            break;
            case "password":
                $sql = "UPDATE users SET Password='$new_password' WHERE ID='$id'";
                $conn -> query($sql);
                $_SESSION["error"] = "Zaktualizowano hasło poprawnie!";
            break;
            case "email":
                $sql = "UPDATE users SET Email='$new_info' WHERE ID='$id'";
                $conn -> query($sql);
                $_SESSION["error"] = "Zaktualizowano e-mail poprawnie!";
            break;
        }
    }
    else{
        $_SESSION["error"] = "Podano błędne hasło!";
    }

    unset($_POST["option"]);
    unset($_POST["new_info"]);
    unset($_POST["new_pwd"]);
    unset($_POST["pwd"]);
    header("location:../profile.php");
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