<?php
    session_start();

    if(isset($_GET["id"]) && isset($_SESSION["id"])){
        $done_job_id = $_GET["id"];
        $done_user_id = $_SESSION["id"];

        require_once("../connection.php");
        $conn = @new mysqli($host, $user_db, $password_db, $db_name);

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
		
		$topic;
        $info;
        $type;
        $whoadd;
        $length;
        $end;

        $sql="SELECT * FROM job WHERE The_ID=$done_job_id AND ForWho=$done_user_id";
		$que = $conn-> query($sql);
		while($res = mysqli_fetch_array($que)){
			$topic = $res["Topic"];
            $info = $res["Info"];
            $type = $res["Type"];
            $whoadd = $res["WhoAdd"];
            $length = $res["Length"];
			$end = $res["End"];
		}
		
		$sql = "INSERT INTO done(ID, The_ID, Topic, Info, Type, WhoAdd, ForWho, Length, Visited, Visited_Admin, End, Date) VALUES (NULL, '$done_job_id', '$topic', '$info', '$type', '$whoadd', '$done_user_id', '$length', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '$end', CURRENT_TIMESTAMP)";
		$conn -> query($sql);
		
		$sql = "DELETE FROM job WHERE The_ID=$done_job_id AND ForWho=$done_user_id";
		$conn -> query($sql);

        $conn -> close();
        echo "<script>location.reload()</script>";
    }
?>