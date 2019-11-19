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
        <title>PYR - Panel Główny</title>
        <link rel="stylesheet" href="style/main.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body onload="time()">

        <!-- Pasek z linkami --->
        <div id="nav_background" onclick="nav_hide()">
            <div id="nav" onclick="nav_hidenot()">
                <div id="nav_profile">
                    <img src="<?php echo "photo/".$_SESSION["id"].".png" ?>"/>
                    <p style="color:white; padding: 5px;"><?php echo name_by_id($_SESSION["id"]) ?></p>
                </div>
                <div id="nav_link" onclick='nav_classic_link("user.php")'>PANEL GŁÓWNY</div>
                <div id="nav_link" onclick='nav_link("http:\/\/mail.oxylane.com")'>MAIL</div>
                <div id="nav_link" onclick='nav_link("http:\/\/riverlakestudios.pl")'>LINK 1</div>
                <div id="nav_link" onclick='nav_link("http:\/\/wp.pl")'>LINK 2</div>
                <div id="nav_link" onclick='nav_link("http:\/\/lowcygier.pl")'>LINK 3</div>
                <div id="nav_link" onclick='nav_link("http:\/\/drive.google.com")'>LINK 4</div>
                <div id="nav_link" onclick='nav_classic_link("http:\/\/riverlakestudios.pl/pyr/logout.php")'><span style="color:red;">WYLOGUJ</span></div>
                <div id="nav_link" onclick='nav_classic_link("http:\/\/riverlakestudios.pl/pyr/report.php")'><span style="color:#ffbf00;">ZGŁOŚ USTERKĘ</span></div>
            </div>
        </div>

        <!-- Popup okienko zadań -->
        <div id="okno_background" onclick="job_popup()">
            <div id="okno_job" onclick="job_okno()">
            </div>
        </div>

        <header>
            <div id="nav_handle"><img src='icons/menu-3-white.png' onclick="nav_open()"/></div>
            <h1 style="width:60%; float:left;">. : Plan Your Responsibilities : .</h1><br>
            <div style="clear:both;"></div>
            <p id="p_timer"></p>
        </header>

        <!-- Panel akcji (wyloguj etc.) --->
        <div id="div_panel">
            <div onclick="new_job()" id="new_job">DODAJ ZADANIE</div>
            <div onclick="okno_sort()" id="sort">SORTOWANIE: <?php echo $_SESSION["sort"] ?></div>
            <div onclick="nav_classic_link('user_done.php')" id="done_job">WYPEŁNIONE ZADANIA</div>
            <div style="clear:both;"></div>
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
                            $div_job_top='<div class="job_red job" id="'.$res["The_ID"].'">';
                            $div_job_topic_top = '<div class="job_topic_justbg"><div class="job_topic" id="'.$res["The_ID"].'" onclick="job_popup(this.id)">';
                            $div_job_topic_bottom = '</div></div><input type="button" class="job_button" id="'.$res["The_ID"].'" value="Zakończ" onclick="job_done(this.id)" onmouseover="job_topic_radius(this.id)" onmouseout="job_topic_radius_fix(this.id)"/>';
                        }
                        else if($days_left<3){
                            $div_job_top='<div class="job_yellow job" id="'.$res["The_ID"].'">';
                            $div_job_topic_top = '<div class="job_topic_justbg"><div class="job_topic" id="'.$res["The_ID"].'" onclick="job_popup(this.id)">';
                            $div_job_topic_bottom = '</div></div><input type="button" class="job_button" id="'.$res["The_ID"].'" value="Zakończ" onclick="job_done(this.id)" onmouseover="job_topic_radius(this.id)" onmouseout="job_topic_radius_fix(this.id)"/>';
                        }
                        else{
                            $div_job_top='<div class="job" id="'.$res["The_ID"].'">';
                            $div_job_topic_top = '<div class="job_topic_justbg"><div class="job_topic" id="'.$res["The_ID"].'" onclick="job_popup(this.id)">';
                            $div_job_topic_bottom = '</div><input type="button" class="job_button" id="'.$res["The_ID"].'" value="Zakończ" onclick="job_done(this.id)"/></div>';
                        }

                        $div_job_title_top = '<div class="job_title" id="'.$res["The_ID"].'" onclick="job_popup(this.id)">';
                        $div_job_title_bottom = '</div>';
                        $div_job_bottom = '</div>';

                        echo $div_job_top;
                        echo $div_job_title_top;

                        // TYTUŁ
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
                        // -----

                        echo $div_job_title_bottom;
                        echo $div_job_topic_top;

                        // DATA KOŃCA ZADANIA
                        echo "<div class='job_small_info_plus'><img src='icons/hourglass.png'/><span>".proper_date($res["End"])."</span></div>";
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
                        echo "<div class='job_small_info'><img src='icons/attachment.png'/>".$how_many_atta."</div>";
                        echo "<div style='clear:both;'></div>";
                        // -----

                        // INFORMACJE DODATKOWE W ZADANIU
                        $job_info = $res["Info"];
                        echo "<div class='job_info'>".$job_info."</div>";
                        echo "<div style='clear:both;'></div>";
                        // -----

                        // KTO DODAŁ ZADANIE
                        echo "<div style='clear:both;'><div class='job_small_info_plus'><img src='icons/user.png'/>".name_by_id($res["WhoAdd"])."</div>";
                        // -----

                        // ILOŚĆ OSÓB W ZADANIU
                        $how_many_per=0;
                        $temp_sql="SELECT ForWho FROM job WHERE The_ID=$the_id";
                        $temp_que=mysqli_query($conn, $temp_sql);
                        while($temp_res = mysqli_fetch_array($temp_que)){
                            $how_many_per++;
                        }
                        echo "<div class='job_small_info'/><img src='icons/users.png'/>".$how_many_per."</div></div>";
                        echo "<div style='clear:both;'></div>";
                        // -----

                        echo $div_job_topic_bottom;
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
                        $div_job_top='<div class="job_red job" id="'.$res["The_ID"].'">';
                        $div_job_topic_top = '<div class="job_topic_justbg"><div class="job_topic" id="'.$res["The_ID"].'" onclick="job_popup(this.id)">';
                        $div_job_topic_bottom = '</div></div>';
                    }
                    else if($days_left<3){
                        $div_job_top='<div class="job_yellow job" id="'.$res["The_ID"].'">';
                        $div_job_topic_top = '<div class="job_topic_justbg"><div class="job_topic" id="'.$res["The_ID"].'" onclick="job_popup(this.id)">';
                        $div_job_topic_bottom = '</div></div>';
                    }
                    else{
                        $div_job_top='<div class="job" id="'.$res["The_ID"].'">';
                        $div_job_topic_top = '<div class="job_topic_justbg"><div class="job_topic" id="'.$res["The_ID"].'" onclick="job_popup(this.id)">';
                        $div_job_topic_bottom = '</div></div>';
                    }

                    $div_job_title_top = '<div class="job_title" id="'.$res["The_ID"].'" onclick="job_popup(this.id)">';
                    $div_job_title_bottom = '</div>';
                    $div_job_bottom = '</div>';

                    echo $div_job_top;
                    echo $div_job_title_top;

                    // TYTUŁ
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
                    // -----

                    echo $div_job_title_bottom;
                    echo $div_job_topic_top;

                    // DATA KOŃCA ZADANIA
                    echo "<div class='job_small_info_plus'><img src='icons/hourglass.png'/><span>".proper_date($res["End"])."</span></div>";
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
                    echo "<div class='job_small_info'><img src='icons/attachment.png'/>".$how_many_atta."</div>";
                    echo "<div style='clear:both;'></div>";
                    // -----

                    // INFORMACJE DODATKOWE W ZADANIU
                    $job_info = $res["Info"];
                    echo "<div class='job_info'>".$job_info."</div>";
                    echo "<div style='clear:both;'></div>";
                    // -----

                    // KTO DODAŁ ZADANIE
                    echo "<div style='clear:both;'><div class='job_small_info_plus'><img src='icons/user.png'/>".name_by_id($res["WhoAdd"])."</div>";
                    // -----

                    // ILOŚĆ OSÓB W ZADANIU
                    $how_many_per=0;
                    $temp_sql="SELECT ForWho FROM job WHERE The_ID=$the_id";
                    $temp_que=mysqli_query($conn, $temp_sql);
                    while($temp_res = mysqli_fetch_array($temp_que)){
                        $how_many_per++;
                    }
                    echo "<div class='job_small_info'/><img src='icons/users.png'/>".$how_many_per."</div></div>";
                    echo "<div style='clear:both;'></div>";
                    // -----

                    echo $div_job_topic_bottom;
                    echo $div_job_bottom;
                }

                $exist=1;
            }
			
			if($already==1)
				echo '</div>';

            $conn -> close();
        ?>

    <!-- Div który zbiera śmieci przy jQuery -->
    <div id="thrash"></div>

    </body>

    <script>
        // Musi tu być bo nie działa skrypt
        document.getElementById("okno_background").style.display="none";
        document.getElementById("nav_background").style.display="none";

        // Skrypty nav

        var okno=0;
        function nav_hidenot(){
            okno=1;
        }

        function nav_hide(){
            if(okno==0){
                var navback = document.getElementById("nav_background");
                navback.style.display="none";
            }
            okno=0;
        }

        function nav_open(){
            var navback = document.getElementById("nav_background");
            navback.style.display="inline";
        }

        function nav_link(link){
            var win = window.open(link, '_blank');
            win.focus;
        }

        function nav_classic_link(link){
            window.location.href = link;
        }

        // -----
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

                timer.innerHTML=<?php echo '"'.proper_date(date("Y-m-d")).' - "+'; ?>full_day+" - "+full_time;
            }, 1000, 1000)
        }

        // -----
        // Skrypty dla aktywnych zadań

        //Funkcje obsługi okienka z zadaniami
        var okno=0;
        function job_okno(){
            okno=1;
        };

        function job_popup(elem){
            if(document.getElementById("okno_background").style.display=="none"){
                document.getElementById("okno_background").style.display="inline";
                $.get("additional/processor.php", {elem: elem}, function(data){
                    $('#okno_job').html(data);
                });
            }
            else if(okno==0){
                document.getElementById("okno_background").style.display="none";
            }
            
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