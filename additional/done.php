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
        $whoadd;
        $end;

        $sql="SELECT * FROM job WHERE The_ID=$done_job_id AND ForWho=$done_user_id";
		$que = $conn-> query($sql);
		while($res = mysqli_fetch_array($que)){
			$topic = $res["Topic"];
			$info = $res["Info"];
			$whoadd = $res["WhoAdd"];
			$end = $res["End"];
		}
		
		$sql = "INSERT INTO done(ID, The_ID, Topic, Info, WhoAdd, ForWho, End, Date) VALUES (NULL, '$done_job_id', '$topic', '$info', '$whoadd', '$done_user_id', '$end', CURRENT_TIMESTAMP)";
		$conn -> query($sql);
		
		$sql = "DELETE FROM job WHERE The_ID=$done_job_id AND ForWho=$done_user_id";
		$conn -> query($sql);

        $conn -> close();
        echo "<script>location.reload()</script>";
    }
?>