<?php
    session_start();

    require_once("../connection.php");
    $conn = mysqli_connect($host, $user_db, $password_db, $db_name);

    if(isset($_GET['the_job'])){

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        echo '
        NOWE ZADANIE<br>
        <form method="POST" action="additional/newjob.php">
            Tytuł:<br>
            <input type="text" name="new_title" style="width:400px;"/><br>
            Dodatkowe informacje:<br>
            <textarea name="new_info" rows=4 cols=50/></textarea><br>
            Dla kogo:<br>
        <div id="new_job_forwho">';
        $sql = "SELECT ID, Login FROM users";
        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo $res['Login'].' <input type="checkbox" name="new_forwho" value="'.$res["ID"].'" checked/>     ';
        }
        echo '
        </div><br>
            Deadline: <input type="date" name="new_deadline"/><br>
            <input type="submit"/>
        </form>
        ';

        unset($_GET['the_job']);
    }

    else if(isset($_GET['elem'])){

        $the_id_processor = $_GET['elem'];
        $user_id_processor = $_SESSION['id'];

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

        unset($_GET['elem']);
        mysqli_close($conn);
    }
?>

