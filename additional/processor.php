<?php
    session_start();

    require_once('func.php');

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
        $sql = "SELECT ID, Imie, Nazwisko, Dzial FROM users";
        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<input type="checkbox" class="'.$res["Dzial"].'" name="new_forwho[]" value="'.$res["ID"].'" checked/> '.$res['Imie']." ".$res["Nazwisko"].' | ';
        }
        echo '
            <div><br>
            <input type="button" value="Wszyscy" onclick="new_job_toggle(this.value)"/>
            <input type="button" value="Niski Skład" onclick="new_job_toggle(this.value)"/>
            <input type="button" value="Wysoki Skład" onclick="new_job_toggle(this.value)"/>
            <input type="button" value="E-commerce" onclick="new_job_toggle(this.value)"/>
            <input type="button" value="Rampa" onclick="new_job_toggle(this.value)"/>
            <input type="button" value="Reszta" onclick="new_job_toggle(this.value)"/>
            </div>
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
			
            echo "<b>Koniec:</b> ".proper_date($res["End"])."<br>";
            // LICZNIK ZAŁĄCZNIKÓW
            $how_many_atta=0;
            $string = $res["Info"];
            $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
            $string = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $string);
            $bufor = $string;
            while($pos = strpos($bufor, "a href=")){
                $bufor[$pos]="x";
                $how_many_atta++;
            }
            $temp_sql="SELECT Message FROM chat WHERE The_ID='$the_id_processor'";
            $temp_que=mysqli_query($conn, $temp_sql);
            while($temp_res = mysqli_fetch_array($temp_que)){
                $string=$temp_res["Message"];
                $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
                $string = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $string);
                $bufor = $string;
                while($pos = strpos($bufor, "a href=")){
                    $bufor[$pos]="x";
                    $how_many_atta++;
                }
            }
            echo "Załączniki: ".$how_many_atta."<br><br>"; 

            echo $res["Topic"]."<br><br>";

            echo "<b>Dodatkowe informacje:</b><br>";

            $string = $res["Info"];
            $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
            $string = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $string);
            echo $string.'<br>';

            echo "<br><br>";

            echo "<b>Uczestniczący w tym zadaniu:</b><br>";
                $temp_sql = "SELECT ForWho FROM job WHERE The_ID='$the_id_processor'";
                $temp_que = mysqli_query($conn, $temp_sql);
                while($temp = mysqli_fetch_array($temp_que)){
                    $other_forwho = $temp["ForWho"];
                    echo " - ".name_by_id($other_forwho)."<br>";
					array_push($processor_forwho_array, $other_forwho);
                }
            echo '<input type="button" id="'.$the_id_processor.'" value="Dodaj osobę" onclick="job_addperson(this.id)"/>';

            $temp_sql="SELECT ID FROM job WHERE The_ID=$the_id_processor AND WhoAdd=$user_id_processor LIMIT 1";
            $temp_que = mysqli_query($conn, $temp_sql);
            while($temp = mysqli_fetch_array($temp_que)){
                echo '<br><input type="button" id="'.$the_id_processor.'" value="Edytuj zadanie" onclick="job_edit(this.id)"/>';
                echo '<br><input type="button" id="'.$the_id_processor.'" value="Usuń osobę" onclick="job_delperson(this.id)"/>';
            }
			echo "<br><br>";

            echo "<b>Dodano przez:</b> ".name_by_id($res["WhoAdd"])."<br>";
            echo "<b>Data:</b> ".proper_date($res["Start"])."<br>";
            echo "<b>ID:</b>".$the_id_processor."<br><br>";
			
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
        echo '<div id="okno_chat_message"><br>';
        if($res["SentFrom"]==$_SESSION["id"]){
            echo '<input type="button" value="x" id="'.$res["ID"].'" onclick="job_chatmsqdelete(this.id)" style="float:right; width:15px; margin-top:-15px;" title="Usuń wiadomość"/>';
            echo '<div style="clear:both;"></div>';
        }
		
		//echo $res['Message'].', '.$res['SentFrom'].', '.$res['Date'];
		
		// KONWERTER LINKÓW, DZIĘKI STACKOVERFLOW!
		$string = $res['Message'];
		$url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
		$string = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $string);
		echo $string.'<br>';
		
		$processor_chat_id=$res['SentFrom'];
		$processor_chat_realname = name_by_id($processor_chat_id);
		
		echo '<span style="float:right;">'.$processor_chat_realname.', '.$res['Date'].'</span>';
		echo '<div style="clear:both;"></div>';
		echo '</div>';
		}
		echo '<textarea style="width:80%; height:40px;" id="okno_chat_chatbox" class="okno_chat_style"/><br>';
        echo '<input type="button" id="'.$the_id_processor.'" class="okno_chat_style" value="Wyślij wiadomość" onclick="okno_sentmessage(this.id)">';
        
        // SKRYPT ZMIANY ROGÓW OKNA JEŻELI JEST WIĘKSZE
        echo '<script>
        var job_height = document.getElementById("okno_job");
        var height = parseInt(job_height.clientHeight)/parseInt(window.screen.availHeight);

        if(height>0.72){
            job_height.style.borderTopRightRadius = "0px";
            job_height.style.borderBottomRightRadius = "0px";
        }
        else{
            job_height.style.borderTopRightRadius = "20px";
            job_height.style.borderBottomRightRadius = "20px";
        }</script>';

        unset($_GET['elem']);
    }
	
	// OKNO DODANIA NOWEJ OSOBY DO ZADANIA
	else if(isset($_GET["addperson_id"])){

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

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
        echo '<b>DODAJ NOWĄ OSOBĘ DO ZADANIA</b><br>';
        echo '<div id="new_job_forwho">';
        $sql = "SELECT ID, Login, Imie, Nazwisko FROM users";
        $que = mysqli_query($conn, $sql);
        $how_many_is_in=0;
        while($res = mysqli_fetch_array($que)){
			$is_out=1;
			
			foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}
			
			if($is_out==1){
                if($how_many_is_in>0)
                    echo " | ";
                echo '<input type="radio" name="addperson_who" value="'.$res["ID"].'"/> '.$res["Imie"]." ".$res["Nazwisko"];
                
                array_push($is_in, $res["Login"]);
                $how_many_is_in++;
			}
        }
        echo '</div>';
		echo '<input type="submit" value="Dodaj osobę"/>';
		echo '</form>';
		
		unset($_GET['addperson_id']);
    }

    // OKNO USUWANIA OSOBY Z ZADANIA
    else if(isset($_GET["delperson_id"])){

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        $delperson_id = $_GET['delperson_id'];
		$_SESSION["the_job"]=$delperson_id;
		$delperson_user_id = $_SESSION["id"];
		$is_in = array();
		
		$sql="SELECT ForWho FROM job WHERE The_ID='$delperson_id'";
		$que= mysqli_query($conn, $sql);
		while($res=mysqli_fetch_array($que)){
			$temp=$res["ForWho"];
			$temp_sql="SELECT Login FROM users WHERE ID='$temp'";
			$temp_que=mysqli_query($conn, $temp_sql);
			while($temp=mysqli_fetch_array($temp_que)){
				array_push($is_in, $temp["Login"]);
			}
		}
		echo '<form action="additional/delperson.php" method="POST">';
        echo '<b>USUŃ OSOBĘ Z ZADANIA</b><br>';
        echo '<div id="new_job_forwho">';
        $sql = "SELECT ID, Login, Imie, Nazwisko FROM users";
        $que = mysqli_query($conn, $sql);
        $how_many_is_in=0;
        while($res = mysqli_fetch_array($que)){
			$is_out=1;
			
			foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
		    }
			
			if($is_out==0){
                if($how_many_is_in>0)
                    echo " | ";
                echo '<input type="radio" name="delperson_who" value="'.$res["ID"].'"/> '.$res["Imie"]." ".$res["Nazwisko"];
                
                array_push($is_in, $res["Login"]);
                $how_many_is_in++;
			}
        }
        echo '</div>';
		echo '<input type="submit" value="Usuń osobę"/>';
		echo '</form>';

        unset($_GET["delperson_id"]);
    }

    // EDYTOWANIE ZADANIA
    else if(isset($_GET["edit_id"])){
        
        $edit_id = $_GET["edit_id"];
        $_SESSION["The_ID"] = $edit_id;

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        echo '<b>EDYTUJ ZADANIE</b><br>';
        echo '<form action="additional/edit.php" method="POST">';

        $sql="SELECT Topic, Info, End FROM job WHERE The_ID=$edit_id LIMIT 1";
        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo 'Tytuł:<br>
                <input type="text" name="edit_title" style="width:400px;" value="'.$res["Topic"].'" required/><br>
                Dodatkowe informacje:<br>
                <textarea name="edit_info" rows=4 cols=50>'.$res["Info"].'</textarea><br>
                Deadline: <input type="date" name="edit_deadline" value="'.$res["End"].'" required/><br>
                <input type="submit"/>
            ';
        }

        echo '</form>';

        unset($_GET['edit_id']);
    }
	
	mysqli_close($conn);
?>

