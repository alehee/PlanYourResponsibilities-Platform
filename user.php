<?php
session_start();

require_once('additional/func.php');

if(!isset($_SESSION["log"]) || !isset($_SESSION["id"]))
{
    header("location:index.php");
    exit();
}

if(!isset($_SESSION["sort"]))
	$_SESSION["sort"]='Deadline';
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-2">
        <title>PYR - <?php echo name_by_id($_SESSION["id"]); ?></title>
        <link rel="stylesheet" href="style/main.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body onload="time()">
        <!-- Popup okienko zadań -->
        <div id="okno_background" onclick="job_popup()">
            <div id="okno_job" onclick="job_okno()">
            </div>
        </div>

        <header>
            <h1>.:Plan Your Responsibilities:.</h1><br>
        </header>

        <div id="div_panel">
        <p><a href="logout.php" id="logout">WYLOGUJ</a></p><br>
		<p onclick="new_job()" id="new_job">DODAJ ZADANIE</p><br>
		<p onclick="okno_sort()" id="sort">SORTOWANIE: <?php echo $_SESSION["sort"] ?></p>
        </div>

        <div id="div_panel">
            <?php echo proper_date(date("Y-m-d")); ?>
            <p id="p_timer"></p>
        </div>

        <!-- Zadania -->
        <div id="div_aktualne">
            <div><h2>Zadania</h2></div>
            <?php
                require_once("connection.php");
                $conn = mysqli_connect($host, $user_db, $password_db, $db_name);

                mysqli_query($conn, "SET CHARSET utf8");
                mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                $id = $_SESSION["id"];

                if(isset($conn))
                {
					$sort = $_SESSION["sort"];
					
					if($sort=="Deadline")
						$sql="SELECT * FROM job WHERE ForWho=$id ORDER BY End ASC";
					
					else
						$sql="SELECT * FROM job WHERE ForWho=$id ORDER BY The_ID ASC";
					
                    $que=mysqli_query($conn, $sql);

                    while($res=mysqli_fetch_array($que))
                    {
                        $days_left = how_many_days_left($res["End"]);
                        $div_job_top="";

                        if($days_left<=0){
                            $div_job_top='<div class="job" id="'.$res["The_ID"].'"
                            style="
                                border: solid 2px red;
                            "
                            >
                            <div class="job_topic" id="'.$res["The_ID"].'" onclick="job_popup(this.id)"
                            style="
                                background-color: lightcoral;
                            "
                            >';
                        }
                        else if($days_left<3){
                            $div_job_top='<div class="job" id="'.$res["The_ID"].'"
                            style="
                                border: solid 2px yellow;
                            "
                            >
                            <div class="job_topic" id="'.$res["The_ID"].'" onclick="job_popup(this.id)"
                            style="
                                background-color: beige;
                            "
                            >';
                        }
                        else{
                            $div_job_top='<div class="job" id="'.$res["The_ID"].'"><div class="job_topic" id="'.$res["The_ID"].'" onclick="job_popup(this.id)">';
                        }

                        $div_job_bottom='</div><input type="button" class="job_button" id="'.$res["The_ID"].'" value="Wykonano" onclick="job_done(this.id)"/></div>';

                        echo $div_job_top;
                        echo "Koniec: ".proper_date($res["End"])."<br><br>";

                        $topic = $res["Topic"];
                        $bufor = "";
                        if(strlen($topic)>100)
                        {
                            echo "<b>";
                            for($i=0; $i<100; $i++)
                            {
                                if($i>80 && $topic[$i]==" ")
                                {
                                    echo "...</b>";
                                    $i=99;
                                }
                                else 
                                    echo $topic[$i];
                            }
                        }
                        else
                            $bufor=$topic;

                        echo "<b>".$bufor."</b><br><br>";
                        echo "Dodano przez: ".name_by_id($res["WhoAdd"])."<br>";
                        echo "<span id='job_span_nonim'>ID:".$res["The_ID"]."</span><br>";
                        echo $div_job_bottom;
                    }
                }

                mysqli_close($conn);
            ?>
        </div>

        <!-- Zadania nadane -->
        <?php
            $my_id_nadane = $_SESSION["id"];
			// Czy istnieje takie zadanie
            $exist = 1;
			// Czy ten panel jest wymagany
            $already = 0;
            // Tablica istniejących zadań
            $nadane_tab = array();

            /*
                JAK DZIAŁA KOD?
                Sprawdza czy istnieje zadanie, które nadaliśmy my.
                Jeżeli tak to te zadania są filtrowane czy to my jesteśmy dodani jako wykonawcy. 
                Jeżeli jest przynajmniej jedno to wtedy ukazuje się sekcja.
            */

            require_once("connection.php");
            $conn = @new mysqli($host, $user_db, $password_db, $db_name);

            $conn->query("SET CHARSET utf8");
            $conn->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

			if($_SESSION["sort"]=="Deadline")
            $sql = "SELECT * FROM job WHERE WhoAdd='$my_id_nadane' AND ForWho!='$my_id_nadane' ORDER BY End ASC";
		
			else
				$sql = "SELECT * FROM job WHERE WhoAdd='$my_id_nadane' AND ForWho!='$my_id_nadane' ORDER BY The_ID ASC";
		
            $que = $conn -> query($sql);
            while($res = mysqli_fetch_array($que)){
                $the_id=$res["The_ID"];
                $temp_sql = "SELECT ID FROM job WHERE The_ID='$the_id' AND ForWho='$my_id_nadane'";
                $temp_que = $conn -> query($temp_sql);
                while($temp = mysqli_fetch_array($temp_que))
                    $exist=0;

                foreach($nadane_tab as $nadane_id){
                    if($nadane_id == $the_id)
                        $exist=0;
                }

                if($exist==1){
                    array_push($nadane_tab, $the_id);

					if($already==0){
						echo '<div id="div_nadane">';
						echo '<div><h2>Zadania nadane</h2></div>';
						$already=1;
                    }

                    $days_left = how_many_days_left($res["End"]);
                    $div_job_top="";

                    if($days_left<=0){
                        $div_job_top='<div class="job" id="'.$the_id.'"
                            style="
                            border: solid 2px red;
                        "
                        >
                        <div class="job_topic" id="'.$the_id.'" onclick="job_popup(this.id)"
                        style="
                            background-color: lightcoral;
                        "
                        >';
                    }
                    else if($days_left<3){
                        $div_job_top='<div class="job" id="'.$the_id.'"
                            style="
                                border: solid 2px yellow;
                            "
                            >
                            <div class="job_topic" id="'.$the_id.'" onclick="job_popup(this.id)"
                            style="
                                background-color: beige;
                            "
                            >';
                    }
                    else{
                        $div_job_top='<div class="job" id="'.$the_id.'"><div class="job_topic" id="'.$the_id.'" onclick="job_popup(this.id)">';
                    }

                    $div_job_bottom='</div></div>';

                    echo $div_job_top;
                    echo "Koniec: ".proper_date($res["End"])."<br><br>";

                    $temp = $res["WhoAdd"];
                    $temp_sql = "SELECT Login FROM users WHERE ID='$temp'";
                    $temp_que = mysqli_query($conn, $temp_sql);
                    $temp = mysqli_fetch_array($temp_que);

                    $topic = $res["Topic"];
                    $bufor = "";
                    if(strlen($topic)>100)
                    {
                            for($i=0; $i<100; $i++)
                            {
                                if($i>80 && $topic[$i]==" ")
                                {
                                    echo "...";
                                    $i=99;
                                }
                                else 
                                    echo $topic[$i];
                            }
                    }
                    else
                        $bufor=$topic;

                    echo "<b>".$bufor."</b><br><br>";
                    echo "Dodano przez: ".$temp["Login"]."<br>";
                    echo "<span id='job_span_nonim'>ID:".$res["The_ID"]."</span><br>";
                    echo $div_job_bottom;
                }

                $exist=1;
            }
			
			if($already==1)
				echo '</div>';

            $conn -> close();
        ?>

        <!-- Zadania ukończone -->
        <div id="div_done"><p>Pokaż wypełnione zadania</p></div>
        <div id="done">
                <?php
                    $my_id=$_SESSION["id"];

                    require_once("connection.php");
                    $conn = @new mysqli($host, $user_db, $password_db, $db_name);

                    $conn -> query("SET CHARSET utf8");
                    $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                    $sql="SELECT * FROM done WHERE ForWho=$my_id";
                    $que = $conn -> query($sql);
                    while($res = mysqli_fetch_array($que)){
                        echo "<b>".$res["Topic"]."</b><br>";

                        $temp = $res["WhoAdd"];
                        $temp_sql = "SELECT Login FROM users WHERE ID='$temp'";
                        $temp_que = mysqli_query($conn, $temp_sql);
                        $temp = mysqli_fetch_array($temp_que);

                        echo "Dodano przez: ".$temp["Login"]."<br>";
                        echo "Planowany koniec: ".$res["End"]."<br>";
                        echo "ID:".$res["The_ID"]." <input type='button' id='".$res["The_ID"]."' value='Przywróć zadanie' onclick='job_undone(this.id)'><br><br>";
                    }

                    $conn -> close();
                ?>
        </div>

        <div id="thrash"></div>

    </body>

    <script>
        // Musi tu być bo nie działa skrypt
        document.getElementById("okno_background").style.display="none";

        // Skrypty timera

        function time(){
            var timer = document.getElementById("p_timer");
            var data = new Date();
            var full_day = data.getDay();
            switch(full_day){
                case 0:{
                    full_day = "Niedziela";
                } break;
                case 1:{
                    full_day = "Poniedziałek";
                } break;
                case 2:{
                    full_day = "Wtorek";
                } break;
                case 3:{
                    full_day = "Środa";
                } break;    
                case 4:{
                    full_day = "Czwartek";
                } break;    
                case 5:{
                    full_day = "Piątek";
                } break;  
                case 6:{
                    full_day = "Sobota";
                } break;      
            }

            setInterval(function(){
                var data = new Date();

                var time_h = data.getHours();
                if(time_h<10)
                    time_h="0"+time_h;
                var time_m = data.getMinutes();
                if(time_m<10)
                    time_m="0"+time_m;
                var time_s = data.getSeconds();
                if(time_s<10)
                    time_s="0"+time_s;

                var full_time=time_h+":"+time_m+":"+time_s;

                timer.innerHTML=full_day+"<br>"+full_time;
            }, 1000, 1000)
        }

        // -----

        // Skrypty dla wypełnionych zadań
        $(document).ready(function(){
            $("#div_done").click(function(){
                $("#done").slideToggle("slow");
            });
        });

        // -----
        // Skrypty dla aktywnych zadań

        var okno=0;
        function job_okno(){
            okno=1;
        };

        function job_popup(elem){
            var popup = document.getElementById("okno_job");

            if(document.getElementById("okno_background").style.display=="none"){
                document.getElementById("okno_background").style.display="inline";
                $.get("additional/processor.php", {elem: elem}, function(data){
                    $('#okno_job').html(data);
                });
            }
            else if(okno==0)
                document.getElementById("okno_background").style.display="none";
            
            okno=0;
        }
		
		//Funkcja dodaje osoby do zadania
		function job_addperson(addperson_id){
			$.get("additional/processor.php", {addperson_id: addperson_id}, function(data){
                    $('#okno_job').html(data);
            });
		}

        //Funkcja dodaje osoby do zadania
		function job_delperson(delperson_id){
			$.get("additional/processor.php", {delperson_id: delperson_id}, function(data){
                    $('#okno_job').html(data);
            });
		}
		
		//Funkcja wysyła wiadomość na chat
		function okno_sentmessage(id){
			var message = id+"~"+document.getElementById("okno_chat_chatbox").value;
			
			//$.get("additional/chat.php", {message: message}, function(data){
			//	$("#thrash").html(data);
			//});
			
			$.get("additional/chat.php", {message: message}, function(data){
                    $('#thrash').html(data);
            });
		}

        //Funkcja usuwa wiadomość z chatu
        function job_chatmsqdelete(msg_id){
            var can_delete = confirm("Czy na pewno chcesz usunąć tę wiadomość z chatu?");
            
            if(can_delete==true){
                $.get("additional/deletemsg.php", {msg_id:msg_id}, function(data){
                    $('#thrash').html(data);
                });
            }
        }

        //Funkcja edytuje zadanie
        function job_edit(edit_id){
            $.get("additional/processor.php", {edit_id:edit_id}, function(data){
                $('#okno_job').html(data);
            });
        }

        // -----
        // Skrypty zakończonych zadań

        function job_done(id){
            $.get("additional/done.php", {id: id}, function(data){
                $("#thrash").html(data);
            });
        }

        function job_undone(id){
            $.get("additional/undone.php", {id: id}, function(data){
                $("#thrash").html(data);
            })
        }

        // -----
        // Skrypty dodawania zadania

        function new_job(){
            var the_job = "";

            if(document.getElementById("okno_background").style.display=="none"){
                document.getElementById("okno_background").style.display="inline";

                $.get("additional/processor.php", {the_job: the_job}, function(data){
                    $('#okno_job').html(data);
                });
            }
            else if(okno==0){
                document.getElementById("okno_background").style.display="none";
                document.getElementById("new_job_div_1").style.display="none";
            }
            
            okno=0;
        }

        // Funkcja szybko zaznacza wiele osób do nowego zadania
        function new_job_toggle(sklad){
            var counter=0;

            if(sklad=="Wszyscy"){
                var tab = document.getElementsByName("new_forwho[]");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        counter++;
                    }
                }
            }
            else if(sklad=="Niski Skład"){
                var tab = document.getElementsByClassName("nskl");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        counter++;
                    }
                }
            }
            else if(sklad=="Wysoki Skład"){
                var tab = document.getElementsByClassName("wskl");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        counter++;
                    }
                }
            }
            else if(sklad=="E-commerce"){
                var tab = document.getElementsByClassName("ecom");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        counter++;
                    }
                }
            }
            else if(sklad=="Rampa"){
                var tab = document.getElementsByClassName("ramp");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        counter++;
                    }
                }
            }
            else if(sklad=="Reszta"){
                var tab = document.getElementsByClassName("resz");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        counter++;
                    }
                }
            }
        }

        // -----
		// Skrypty sortowania
		
		function okno_sort(){
        var sort = "";
			$.get("additional/sort.php", {sort: sort}, function(data){
				$('#thrash').html(data);
			});
        }
		
		// -----

    </script>
</html>

<!-- Funkcje, które są potrzebne tylko tutaj --->
<?php

    // FUNKCJA WYŚWIETLAJĄCA ILE DNI ZOSTAŁO DO WYKONANIA ZADANIA
    function how_many_days_left($date_job){

        $date_curr = date("Y-m-d");
        $job_important=0;

        $date_curr_buf="";
        $date_job_buf="";

        for($i=0; $i<4; $i++){
            $date_curr_buf = $date_curr_buf.$date_curr[$i];
            $date_job_buf = $date_job_buf.$date_job[$i];
        }

        // YEAR
        $date_curr_var=(intval($date_curr_buf)-1970)*365;
        $date_job_var=(intval($date_job_buf)-1970)*365;

        $date_curr_buf="";
        $date_job_buf="";

        for($i=5; $i<7; $i++){
            $date_curr_buf = $date_curr_buf.$date_curr[$i];
            $date_job_buf = $date_job_buf.$date_job[$i];
        }

        // MONTH
        $date_curr_var=$date_curr_var+(intval($date_curr_buf))*30;
        $date_job_var=$date_job_var+(intval($date_job_buf))*30;

        $date_curr_buf="";
        $date_job_buf="";

        for($i=8; $i<10; $i++){
            $date_curr_buf = $date_curr_buf.$date_curr[$i];
            $date_job_buf = $date_job_buf.$date_job[$i];
        }

        // DAY
        $date_curr_var=$date_curr_var+(intval($date_curr_buf));
        $date_job_var=$date_job_var+(intval($date_job_buf));

        return $date_job_var-$date_curr_var;
    }

?>