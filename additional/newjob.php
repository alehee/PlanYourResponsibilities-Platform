<?php
session_start();

if(isset($_POST['new_title'])){

    $new_forwho_list = implode(',', $_POST['new_forwho']);
    $the_id = 1;

    require_once("../connection.php");
    $conn = mysqli_connect($host, $user_db, $password_db, $db_name);

    mysqli_query($conn, "SET CHARSET utf8");
    mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

    $sql = "SELECT The_ID FROM job ORDER BY The_ID DESC";
    $que = mysqli_query($conn, $sql);
    $res = mysqli_fetch_array($que);
    $the_id = $the_id + $res['The_ID'];

    /*
    echo $_POST['new_title']."<br>";
    echo $_POST['new_info']."<br>";
    echo $_POST['new_forwho']."<br>";
    echo $_POST['new_deadline']."<br>";
    echo "Twoje nowe zadanie będzie miało numer ".$the_id."<br>";
    */

    $new_title = $_POST['new_title'];
    $new_info = $_POST['new_info'];
    $new_forwho = $_POST['new_forwho'];
    $new_deadline = $_POST['new_deadline'];
    $new_whoadd = $_SESSION['id'];

    $sql="SELECT Imie, Nazwisko FROM users WHERE ID=$new_whoadd";
    $que = mysqli_query($conn, $sql);
    $res = mysqli_fetch_array($que);

    $mail_whoadd = $res["Imie"]." ".$res["Nazwisko"];

    $from = "PYR@riverlakestudios.pl";
    $subject = "Nowe zadanie na platformie PYR!";
$message = "
Zadanie od: ".$mail_whoadd."

Tytuł: ".$new_title."

Informacje: ".$new_info."

Zaloguj się na riverlakestudios.pl/pyr i sprawdź szczegóły!
Wygenerowano: ".date("Y-m-d G:i:s");
    $headers = "From: ".$from;

    $forwho_id="";
    for($i=0; $i<strlen($new_forwho_list); $i=$i+2){
        if($new_forwho_list[$i]==','){
            $sql = "INSERT INTO job(ID, The_ID, Topic, Info, WhoAdd, ForWho, Start, End) VALUES (NULL, '$the_id', '$new_title', '$new_info', '$new_whoadd', '$forwho_id', CURRENT_TIMESTAMP, '$new_deadline')";

            mysqli_query($conn, $sql);

            $sql = "SELECT Email FROM users WHERE ID='$forwho_id' LIMIT 1";
            $que = mysqli_query($conn, $sql);

            while($res = mysqli_fetch_array($que)){
                $to = $res["Email"];

            mail($to, $subject, $message, $headers);
            }
            $forwho_id="";
        }
        else{
            $forwho_id=$forwho_id.$new_forwho_list[$i];
        }
    }
    unset($_POST["new_title"]);
    header("location:../user.php");
}

else
    header("location:../user.php");

?>