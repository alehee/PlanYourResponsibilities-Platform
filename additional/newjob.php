<?php
session_start();

if(isset($_POST['new_title'])){

    $new_forwho_list = $_POST['new_forwho'];
    $the_id = 1;

    require_once("../connection.php");
    $conn = mysqli_connect($host, $user_db, $password_db, $db_name);

    mysqli_query($conn, "SET CHARSET utf8");
    mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

    $sql = "SELECT The_ID FROM job ORDER BY The_ID DESC";
    $que = mysqli_query($conn, $sql);
    $res = mysqli_fetch_array($que);
    $the_id = $the_id + $res['The_ID'];

    $new_title = $_POST['new_title'];
    $new_info = $_POST['new_info'];
    $new_deadline = $_POST['new_deadline'];
    $new_whoadd = $_SESSION['id'];
    $new_length = $_POST['new_length'];
    $mail_length = "";

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
    $subject = "[PLANDECA] Nowe zadanie!";
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

    foreach($new_forwho_list as $forwho_id){
        $sql = "INSERT INTO job(ID, The_ID, Topic, Info, WhoAdd, ForWho, Length, Start, End) VALUES (NULL, '$the_id', '$new_title', '$new_info', '$new_whoadd', '$forwho_id', '$new_length', CURRENT_TIMESTAMP, '$new_deadline')";

        mysqli_query($conn, $sql);

        $sql = "SELECT Email FROM users WHERE ID='$forwho_id' LIMIT 1";
        $que = mysqli_query($conn, $sql);

        while($res = mysqli_fetch_array($que)){
            $to = $res["Email"];

        mail($to, $subject, $message, $headers);
        }
    }
    unset($_POST["new_title"]);
    header("location:../user.php");
}

else
    header("location:../user.php");

?>