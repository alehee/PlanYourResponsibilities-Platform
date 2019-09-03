<?php
    session_start();

    if(isset($_GET['elem'])){

        $the_id_processor = $_GET['elem'];
        $user_id_processor = $_SESSION['id'];

        require_once("../connection.php");
        $conn = mysqli_connect($host, $user_db, $password_db, $db_name);

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        $sql = "SELECT * FROM job WHERE The_ID=$the_id_processor AND ForWho=$user_id_processor";
        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo "Deadline: ".$res["End"]."<br><br>";
            echo $res["Topic"]."<br><br>";
            echo "Dodatkowe informacje:<br>".$res["Info"]."<br><br>";
            echo "Dodano przez: ".$res["WhoAdd"]." | Data: ".$res["Start"]."<br>";
            echo "ID:".$the_id_processor;
        }

        mysqli_close($conn);
    }
?>

