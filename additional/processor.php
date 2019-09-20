<?php
    session_start();

    require_once("../connection.php");
    $conn = mysqli_connect($host, $user_db, $password_db, $db_name);

    // DODAWANIE NOWEGO ZADANIA
    if(isset($_GET['the_job'])){

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        echo '
        NOWE ZADANIE<br>
        <form method="POST" action="additional/newjob.php">
            Tytuł:<br>
            <input type="text" name="new_title" style="width:400px;" required/><br>
            Dodatkowe informacje:<br>
            <textarea name="new_info" rows=4 cols=50/></textarea><br>
            Dla kogo:<br>
        <div id="new_job_forwho">';
        $sql = "SELECT ID, Login FROM users";
        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<input type="checkbox" name="new_forwho[]" value="'.$res["ID"].'" checked/> '.$res['Login'].' | ';
        }
        echo '
        </div><br>
            Deadline: <input type="date" name="new_deadline" required/><br>
            <input type="submit"/>
        </form>
        ';
        // DALSZA CZĘŚĆ W NEWJOB.PHP

        unset($_GET['the_job']);
    }

    // OKNO ZADANIA
    else if(isset($_GET['elem'])){

        $the_id_processor = $_GET['elem'];
        $user_id_processor = $_SESSION['id'];

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        $sql = "SELECT * FROM job WHERE The_ID=$the_id_processor LIMIT 1";
        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo "Deadline: ".$res["End"]."<br><br>";
            echo $res["Topic"]."<br><br>";
            echo "Dodatkowe informacje:<br>".$res["Info"]."<br><br>";

            $temp = $res["WhoAdd"];
            $temp_sql = "SELECT Login FROM users WHERE ID='$temp'";
            $temp_que = mysqli_query($conn, $temp_sql);
            $temp = mysqli_fetch_array($temp_que);
            echo "<br><br>";

            echo "Uczestniczący w tym zadaniu:<br>";
                $temp_sql = "SELECT ForWho FROM job WHERE The_ID='$the_id_processor'";
                $temp_que = mysqli_query($conn, $temp_sql);
                while($temp = mysqli_fetch_array($temp_que)){
                    $other_forwho = $temp["ForWho"];
                    $temp_sql = "SELECT Login FROM users WHERE ID='$other_forwho'";
                    $temp_temp_que = mysqli_query($conn, $temp_sql);
                    $temp_temp = mysqli_fetch_array($temp_temp_que);
                    echo " - ".$temp_temp["Login"]."<br>";
                }
            echo "<br><br>";

            echo "Dodano przez: ".$temp["Login"]."<br>";
            echo "Data: ".$res["Start"]."<br>";
            echo "ID:".$the_id_processor;
        }

        unset($_GET['elem']);
        mysqli_close($conn);
    }
?>

