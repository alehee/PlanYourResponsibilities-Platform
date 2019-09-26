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
			$processor_forwho_array = array();
			
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
					array_push($processor_forwho_array, $other_forwho);
                }
			echo '<input type="button" id="'.$the_id_processor.'" value="Dodaj osobę" onclick="job_addperson(this.id)"/>';
			echo "<br><br>";

            echo "Dodano przez: ".$temp["Login"]."<br>";
            echo "Data: ".$res["Start"]."<br>";
            echo "ID:".$the_id_processor."<br><br>";
			
			$processor_forme=0;
			foreach($processor_forwho_array as $x){
				if($x == $_SESSION['id'])
					$processor_forme=1;
			}
			if($processor_forme==1){
			echo '<input type="button" id="'.$the_id_processor.'" value="Wykonano" onclick="job_done(this.id)"/>';
			}
        }
		echo "<br><br>";
		
		// CHAT
		
		$sql="SELECT * FROM chat WHERE The_ID=$the_id_processor ORDER BY Date ASC";
		$que = mysqli_query($conn, $sql);
		while($res = mysqli_fetch_array($que)){
		echo '<div id="okno_chat_message">';
		
		//echo $res['Message'].', '.$res['SentFrom'].', '.$res['Date'];
		
		// KONWERTER LINKÓW, DZIĘKI STACKOVERFLOW!
		$string = $res['Message'];
		$url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
		$string = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $string);
		echo $string.'<br>';
		
		$processor_chat_login=$res['SentFrom'];
		
			$temp_sql = "SELECT Login FROM users WHERE ID='$processor_chat_login'";
            $temp_que = mysqli_query($conn, $temp_sql);
            while($temp = mysqli_fetch_array($temp_que)){
                $processor_chat_login = $temp['Login'];
            }
		
		echo '<span style="float:right;">'.$processor_chat_login.', '.$res['Date'].'</span>';
		echo '<div style="clear:both;"></div>';
		echo '</div>';
		}
		echo '<textarea style="width:80%; height:40px;" id="okno_chat_chatbox"/><br>';
		echo '<input type="button" id="'.$the_id_processor.'" value="Wyślij wiadomość" onclick="okno_sentmessage(this.id)">';

        unset($_GET['elem']);
    }
	
	// OKNO DODANIA NOWEJ OSOBY DO ZADANIA
	else if(isset($_GET["addperson_id"])){
		$addperson_id = $_GET['addperson_id'];
		$_SESSION["the_job"]=$addperson_id;
		$addperson_user_id = $_SESSION["id"];
		$is_in = array();
		
		$sql="SELECT ForWho FROM job WHERE The_ID='$addperson_id'";
		$que= mysqli_query($conn, $sql);
		while($res=mysqli_fetch_array($que)){
			$temp=$res["ForWho"];
			$temp_sql="SELECT Login FROM users WHERE ID='$temp'";
			$temp_que=mysqli_query($conn, $temp_sql);
			while($temp=mysqli_fetch_array($temp_que)){
				array_push($is_in, $temp["Login"]);
			}
		}
		echo '<form action="additional/addperson.php" method="POST">';
		echo '<div id="new_job_forwho">';
		echo 'DODAJ NOWĄ OSOBĘ DO ZADANIA<br>';
        $sql = "SELECT users.ID, users.Login FROM users";
        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
			$is_out=1;
			
			foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}
			
			if($is_out==1){
				echo '<input type="radio" name="addperson_who" value="'.$res["ID"].'"/> '.$res["Login"].' | ';
				array_push($is_in, $res["Login"]);
			}
        }
		echo '<input type="submit" value="Dodaj osobę"/>';
        echo '</div>';
		echo '</form>';
		
		unset($_GET['addperson_id']);
	}
	
	mysqli_close($conn);
?>

