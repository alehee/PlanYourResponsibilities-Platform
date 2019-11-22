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
        <b>NOWE ZADANIE</b><br><br>
        <form method="POST" action="additional/newjob.php">
        <div class="okno" style="padding:10px;">
            <div style="font-size:100%; text-align:center;"><b>Tytuł:</b> <input type="text" name="new_title" style="font-size:100%; width:400px;" required/> <span style="padding-left:10px;"></span> <b>Deadline:</b> <input type="date" style="font-size:100%;" name="new_deadline" required/></div>

            <div style="font-size:100%; text-align:center;"><b>Dodatkowe informacje:</b><br>
            <textarea name="new_info" style="font-size:100%; min-height:200px; width:80%; padding:5px;" /></textarea></div>

            <div style="font-size:100%; text-align:center;"><b>Dla kogo:</b></div>
        <div id="new_job_forwho" style="width:98%; min-height:50px; background-color:#e6e6e6; border-radius:20px; margin:1%; text-align:center; padding-top:10px; padding-bottom:10px;">';
        $sql = "SELECT ID, Imie, Nazwisko, Dzial FROM users";
        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="float:left;"><input type="checkbox" style="margin-left:30px;" class="'.$res["Dzial"].'" name="new_forwho[]" value="'.$res["ID"].'"';
                if($res["ID"]==$_SESSION["id"])
                    echo "checked";
            echo '/> '.$res['Imie']." ".$res["Nazwisko"]."</div>";
        }
        echo '
            <div style="clear:both;"></div>
            <div style="margin:10px;">
                <input type="button" class="new_job_dzial_butt" value="Wszyscy" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Niski Skład" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Wysoki Skład" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="E-commerce" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Rampa" onclick="new_job_toggle(this.value)"/>
                <input type="button" class="new_job_dzial_butt" value="Reszta" onclick="new_job_toggle(this.value)"/>
            </div>
        </div>
            <input class="new_job_butt" type="submit" value="UTWÓRZ ZADANIE"/>
        </form>
        </div>
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

            // ZMIENIA STYL OKNA
            $days_left = how_many_days_left($res["End"]);
            if($days_left<=0){
                echo '
                <script>
                    document.getElementById("okno_job").style.backgroundColor="red";
                    document.getElementById("okno_job").style.border="5px solid red";
                </script>
                ';
            }
            else if($days_left<3){
                echo '
                <script>
                    document.getElementById("okno_job").style.backgroundColor="#ffbf00";
                    document.getElementById("okno_job").style.border="5px solid #ffbf00";
                </script>
                ';
            }
            else{
                echo '
                <script>
                    document.getElementById("okno_job").style.backgroundColor="#0f70b7";
                    document.getElementById("okno_job").style.border="5px solid #0f70b7";
                </script>
                ';
            }

            // TYTUŁ
            $topic = $res["Topic"];
            $bufor = "";
            if(strlen($topic)>200)
            {
                    for($i=0; $i<200; $i++)
                    {
                        if($i>180 && $topic[$i]==" ")
                        {
                            echo "...";
                            $i=199;
                        }
                        else 
                            echo $topic[$i];
                    }
            }
            else
                $bufor=$topic;

            echo "<b>".$bufor."</b><br><br>";
            // -----

            echo "<div class='okno'>";
            
            // DATA KOŃCA ZADANIA
            echo "<div class='okno_element'><img src='icons/hourglass.png'/><span>".proper_date($res["End"])."</span></div>";
            // -----

            // ILOŚĆ OSÓB W ZADANIU
            $the_id = $res["The_ID"];
            $how_many_per=0;
            $temp_sql="SELECT ForWho FROM job WHERE The_ID=$the_id";
            $temp_que=mysqli_query($conn, $temp_sql);
            while($temp_res = mysqli_fetch_array($temp_que)){
                $how_many_per++;
            }
            echo "<div class='okno_element'><img src='icons/users.png'/>".$how_many_per."</div>";
            // -----

            // LICZNIK ZAŁĄCZNIKÓW
            $how_many_atta=0;
            $the_id = $res["The_ID"];
            $string = $res["Info"];
            $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
            $string = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $string);
            $bufor = $string;
            while($pos = strpos($bufor, "a href=")){
                $bufor[$pos]="x";
                $how_many_atta++;
            }
            $temp_sql="SELECT Message FROM chat WHERE The_ID='$the_id'";
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
            echo "<div class='okno_element'><img src='icons/attachment.png'/>".$how_many_atta."</div>";
            // -----

            // KTO DODAŁ ZADANIE
            echo "<div class='okno_element'><img src='icons/user.png'/>".name_by_id($res["WhoAdd"])."</div>";
            // -----
            echo "<div style='clear:both;'></div>";

            // INFORMACJE DODATKOWE W ZADANIU
                $string = $res["Info"];
                $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
                $string = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $string);

                echo '<div style="padding:30px;"><b>'.$string.'</b></div>';
                echo '<div style="clear:both; text-align:right; font-size:60%; color:gray; margin-right:30px;">ID: '.$the_id_processor.'</div>';
            //

            // OSOBY BIORĄCE UDZIAŁ
            echo "<div style='background-color:#e6e6e6; border-radius:20px; width:98%; min-height:50px; margin:1%; padding:1%; padding-top:0.5%; font-size:80%;'>";
            echo "<ul>";
            $temp_sql = "SELECT ForWho FROM job WHERE The_ID='$the_id_processor'";
            $temp_que = mysqli_query($conn, $temp_sql);
            while($temp = mysqli_fetch_array($temp_que)){
                $other_forwho = $temp["ForWho"];
                echo "<li>".name_by_id($other_forwho)."</li>";
				array_push($processor_forwho_array, $other_forwho);
            }
            echo "</ul>";
            echo "<div style='clear:both;'/></div>";
            // -----

            // PANEL PRZYCISKÓW
            echo '<div id="div_panel">';
                echo '<div id="'.$the_id_processor.'" class="okno_addperson" onclick="job_addperson(this.id)">DODAJ OSOBĘ</div>';
                $temp_sql="SELECT ID FROM job WHERE The_ID=$the_id_processor AND WhoAdd=$user_id_processor LIMIT 1";
                $temp_que = mysqli_query($conn, $temp_sql);
                while($temp = mysqli_fetch_array($temp_que)){
                    echo '<div id="'.$the_id_processor.'" class="okno_edit" onclick="job_edit(this.id)">EDYTUJ ZADANIE</div>';
                    echo '<div id="'.$the_id_processor.'" class="okno_delperson" onclick="job_delperson(this.id)">USUŃ OSOBĘ</div>';
                }

                $processor_forme=0;
                foreach($processor_forwho_array as $x){
                    if($x == $_SESSION['id'])
                        $processor_forme=1;
                }
                if($processor_forme==1){
                echo '<div id="'.$the_id_processor.'" class="okno_done" onclick="job_done(this.id)">ZAKOŃCZ</div>';
                }
            echo '</div>';
            echo '<div style="clear:both;"></div>';
            // -----
        }
		
        // CHAT
        echo "<div style='padding-top:30px;'>";
		$sql="SELECT * FROM chat WHERE The_ID=$the_id_processor ORDER BY Date ASC";
		$que = mysqli_query($conn, $sql);
		while($res = mysqli_fetch_array($que)){
            echo '<div id="okno_chat_message"><br>';
            if($res["SentFrom"]==$_SESSION["id"]){
                echo '<input type="button" value="x" id="'.$res["ID"].'" onclick="job_chatmsqdelete(this.id)" style="float:right; width:15px; margin-top:-15px;" title="Usuń wiadomość"/>';
                echo '<div style="clear:both;"></div>';
            }
            
            // KONWERTER LINKÓW, DZIĘKI STACKOVERFLOW!
            $string = $res['Message'];
            $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
            $string = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $string);
            echo $string;
            
            $processor_chat_id=$res['SentFrom'];
            $processor_chat_realname = name_by_id($processor_chat_id);
            
            echo '<span style="float:right; text-align:right; font-size:60%; color:gray; margin-right:30px;">'.$processor_chat_realname.', '.proper_date($res['Date']).'</span>';
            echo '<div style="clear:both;"></div>';
            echo '</div>';
        }
        
        //echo '<div id="'.$the_id_processor.'" class="okno_chat_butt" onclick="okno_sentmessage(this.id)">WYŚLIJ WIADOMOŚĆ</div>';
        echo '<textarea style="width:90%; min-height:60px; margin:10px 5%; padding:5px; font-size:100%;" id="okno_chat_chatbox" class="okno_chat_style"/>';
        echo '<div id="'.$the_id_processor.'" class="okno_chat_butt" onclick="okno_sentmessage(this.id)">WYŚLIJ WIADOMOŚĆ</div>';
        echo "</div>";
        // -----

        echo "</div>";

        unset($_GET['elem']);
    }
	
	// OKNO DODANIA NOWEJ OSOBY DO ZADANIA
	else if(isset($_GET["addperson_id"])){

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

		$addperson_id = $_GET['addperson_id'];
		$_SESSION["the_job"]=$addperson_id;
        $addperson_user_id = $_SESSION["id"];
        $addperson_topic;
		$is_in = array();
		
		$sql="SELECT ForWho, Topic FROM job WHERE The_ID='$addperson_id'";
		$que= mysqli_query($conn, $sql);
		while($res=mysqli_fetch_array($que)){
            $addperson_topic = $res["Topic"];
			$temp=$res["ForWho"];
			$temp_sql="SELECT Login FROM users WHERE ID='$temp'";
			$temp_que=mysqli_query($conn, $temp_sql);
			while($temp=mysqli_fetch_array($temp_que)){
				array_push($is_in, $temp["Login"]);
			}
        }
        echo "<b>$addperson_topic</b><br><br>";
        echo '<div class="okno">';
		echo '<form action="additional/addperson.php" method="POST">';
        echo '<div style="width:98%; min-height:50px; background-color:#e6e6e6; border-radius:20px; margin:1%; text-align:center; font-weight:800; padding-top:10px; font-size:150%;">DODAJ NOWĄ OSOBĘ DO ZADANIA</div>';
        echo '<div id="new_job_forwho">';
        $sql = "SELECT ID, Login, Imie, Nazwisko FROM users";
        $que = mysqli_query($conn, $sql);
        $anyone=0;
        while($res = mysqli_fetch_array($que)){
			$is_out=1;
			
			foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
			}
			
			if($is_out==1){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" name="addperson_who" value="'.$res["ID"].'"/> '.$res["Imie"]." ".$res["Nazwisko"]."</div>";
                $anyone=1;
                array_push($is_in, $res["Login"]);
			}
        }
        echo '<div style="clear:both;"></div>';
        if($anyone==0){
            echo '<div style="text-align:center; font-size:100%; font-weight:800; width:100%; height:20px;">WSZYSCY UCZESTNICZĄ W ZADANIU!</div>';
            echo '</div>';
        }
        else{
            echo '</div>';
            echo '<input type="submit" class="okno_addperson_butt" value="DODAJ OSOBĘ"/>';
        }
        echo '</form>';
        //KONIEC DIVA OKNO
        echo '</div>';
		
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
        $delperson_topic;
		
		$sql="SELECT ForWho, Topic FROM job WHERE The_ID='$delperson_id'";
		$que= mysqli_query($conn, $sql);
		while($res=mysqli_fetch_array($que)){
            $delperson_topic = $res["Topic"];
			$temp=$res["ForWho"];
			$temp_sql="SELECT Login FROM users WHERE ID='$temp'";
			$temp_que=mysqli_query($conn, $temp_sql);
			while($temp=mysqli_fetch_array($temp_que)){
				array_push($is_in, $temp["Login"]);
			}
        }
        echo "<b>$delperson_topic</b><br><br>";
        echo '<div class="okno">';
		echo '<form action="additional/delperson.php" method="POST">';
        echo '<div style="width:98%; min-height:50px; background-color:#e6e6e6; border-radius:20px; margin:1%; text-align:center; font-weight:800; padding-top:10px; font-size:150%;">USUŃ OSOBĘ Z ZADANIA</div>';
        echo '<div id="new_job_forwho">';
        $sql = "SELECT ID, Login, Imie, Nazwisko FROM users";
        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
			$is_out=1;
			
			foreach($is_in as $user){
				if($user == $res["Login"]){
					$is_out=0;
				}
		    }
			
			if($is_out==0){
                echo '<div style="float:left;"><input type="radio" style="margin-left:30px;" name="delperson_who" value="'.$res["ID"].'"/> '.$res["Imie"]." ".$res["Nazwisko"]."</div>";
                
                array_push($is_in, $res["Login"]);
			}
        }
        echo '<div style="clear:both;"></div>';
        echo '</div>';
		echo '<input type="submit" class="okno_delperson_butt" value="USUŃ OSOBĘ"/>';
        echo '</form>';
        echo '</div>';

        unset($_GET["delperson_id"]);
    }

    // EDYTOWANIE ZADANIA
    else if(isset($_GET["edit_id"])){
        
        $edit_id = $_GET["edit_id"];
        $_SESSION["The_ID"] = $edit_id;

        mysqli_query($conn, "SET CHARSET utf8");
        mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        echo '<b>EDYTUJ ZADANIE</b><br><br>';

        echo '<div class="okno" style="padding:10px;">';
        echo '<form action="additional/edit.php" method="POST">';

        $sql="SELECT Topic, Info, End FROM job WHERE The_ID=$edit_id LIMIT 1";
        $que = mysqli_query($conn, $sql);
        while($res = mysqli_fetch_array($que)){
            echo '<div style="font-size:100%; text-align:center;"><b>Tytuł:</b> <input type="text" name="edit_title" style="width:400px; max-width:70%; font-size:100%;" value="'.$res["Topic"].'" required/> <span style="padding-left:10px;"></span> <b>Deadline:</b> <input type="date" style="font-size:100%;" name="edit_deadline" value="'.$res["End"].'" required/></div>

            <div style="font-size:100%; text-align:center; margin:20px width:80%"><b>Dodatkowe informacje:</b><br>
            <textarea name="edit_info" style="font-size:100%; min-height:200px; width:80%; padding:5px;">'.$res["Info"].'</textarea></div>

            <input class="okno_edit_butt" type="submit" value="ZAKOŃCZ EDYCJĘ"/>
            ';
        }

        echo '</form>';
        echo '</div>';

        unset($_GET['edit_id']);
    }
	
	mysqli_close($conn);
?>

