<?php
    session_start();

    if(isset($_GET["id"]) && isset($_SESSION["id"])){
        $undone_job_id = $_GET["id"];
        $undone_user_id = $_SESSION["id"];

        $undone_job_exist = 0;

        require_once("../connection.php");
        $conn = @new mysqli($host, $user_db, $password_db, $db_name);

        $conn -> query("SET CHARSET utf8");
        $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        $sql="SELECT * FROM job WHERE The_ID=$undone_job_id AND ForWho=$undone_user_id";
        $que = $conn -> query($sql);
        while($res = mysqli_fetch_array($que)){
            $undone_job_exist=1;
        }

        if($undone_job_exist==0){

            $topic;
            $info;
            $type;
            $whoadd;
            $length;
            $end;

            $sql="SELECT * FROM done WHERE The_ID=$undone_job_id AND ForWho=$undone_user_id";
            $que = $conn -> query($sql);
            while($res = mysqli_fetch_array($que)){
                $topic = $res["Topic"];
                $info = $res["Info"];
                $type = $res["Type"];
                $whoadd = $res["WhoAdd"];
                $length = $res["Length"];
                $end = $res["End"];
            }

            $sql="INSERT INTO job(ID, The_ID, Topic, Info, Type, WhoAdd, ForWho, Length, Start, Visited, Visited_Admin, End) VALUES (NULL, '$undone_job_id', '$topic', '$info', '$type', '$whoadd', '$undone_user_id', '$length', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '$end')";
            $conn -> query($sql);
        }

        $sql="DELETE FROM done WHERE The_ID=$undone_job_id AND ForWho=$undone_user_id";
        $conn -> query($sql);

        $conn -> close();
        // NIE DZIAŁA HEADER
        echo "<script>nav_classic_link('main.php')</script>";
    }
?>