<?php
session_start();

if(isset($_POST['new_title'])){

    $new_forwho_list = $_POST['new_forwho'];

    require_once("../connection.php");
    $conn = mysqli_connect($host, $user_db, $password_db, $db_name);

    mysqli_query($conn, "SET CHARSET utf8");
    mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

    // POBIERA OSTATNI NUMER ZADANIA JEDNOSTKI I WSTAWIA KOLEJNY NUMER DO BAZY
    $new_jednostka = $_SESSION["city"];
    $sql = "SELECT The_ID FROM job_index WHERE Jednostka='$new_jednostka'";
    $que = mysqli_query($conn, $sql);
    $res = mysqli_fetch_array($que);
    $the_id = 1 + $res['The_ID'];
    $sql = "UPDATE job_index SET The_ID=$the_id WHERE Jednostka='$new_jednostka'";
    mysqli_query($conn, $sql);

    $new_title = $_POST['new_title'];
    $new_info = $_POST['new_info'];
    $new_type = "sta";
    $new_deadline = $_POST['new_deadline'];
    $new_whoadd = $_SESSION['id'];
    $new_length = $_POST['new_length'];
    $mail_length = "";

    // SPRAWDŹ CZY TAKIE ZADANIE JUŻ NIE ISTNIEJE
    $already_exist = 0;
    $sql = "SELECT ID FROM job WHERE Topic='$new_title'";
    $que = mysqli_query($conn, $sql);
    while($res = mysqli_fetch_array($que)){
        $already_exist = 1;
    }

    if($new_length == 3)
        $mail_length = "Krótkie";
    else if($new_length == 2)
        $mail_length = "Średnie";
    else
        $mail_length = "Długie";

    $sql="SELECT Imie, Nazwisko FROM users WHERE ID=$new_whoadd";
    $que = mysqli_query($conn, $sql);
    $res = mysqli_fetch_array($que);

    $mail_whoadd = $res["Imie"]." ".$res["Nazwisko"];

    $from = "PlanDeca@riverlakestudios.pl";
    $subject = "[PLANDECA] Nowe zadanie kadrowe!";
$message = "
Od: ".$mail_whoadd."
Długość: ".$mail_length."

Tytuł: 
".$new_title."

Informacje: 
".$new_info."

Zaloguj się na plandeca.pl i sprawdź szczegóły!
Wygenerowano: ".date("Y-m-d G:i:s");
    $headers = "From: ".$from;

    // JEŻELI ISTNIEJE JUŻ TAKIE ZADANIE TO OMIJA
    if($already_exist == 0){
        foreach($new_forwho_list as $forwho_id){
            $forwho_error = 0;

            $sql = "SELECT ID FROM job WHERE The_ID='$the_id' AND ForWho='$forwho_id'";
            $que = mysqli_query($conn, $sql);
            while($res = mysqli_fetch_array($que)){
                $forwho_error = 1;
            }

            if($forwho_error == 0){
                $sql = "INSERT INTO job(ID, The_ID, Topic, Info, Type, WhoAdd, ForWho, Length, Start, Visited, Visited_Admin, End) VALUES (NULL, '$the_id', '$new_title', '$new_info', '$new_type', '$new_whoadd', '$forwho_id', '$new_length', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '$new_deadline')";

                mysqli_query($conn, $sql);

                $sql = "SELECT Email FROM users WHERE ID='$forwho_id' LIMIT 1";
                $que = mysqli_query($conn, $sql);

                while($res = mysqli_fetch_array($que)){
                    $to = $res["Email"];

                mail($to, $subject, $message, $headers);
                }
            }
        }
    }

    else{
        $_SESSION["error"] = "Takie zadanie już istnieje... Zmień temat lub dodaj osoby do już istniejącego zadania!";
        unset($_POST["new_title"]);
        header("location:../main.php");
    }

    unset($_POST["new_title"]);
    header("location:../main.php");
}

else
    header("location:../main.php");

?>