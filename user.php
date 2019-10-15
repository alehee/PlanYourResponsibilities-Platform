<?php
session_start();

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
        <title>PlanYourResponsibilities - Platform</title>
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
            <h2>Konto: <?php echo $_SESSION["log"]." ID: ".$_SESSION["id"]; ?></h2>
        </header>

        <div id="div_panel">
        <p><a href="logout.php" id="logout">WYLOGUJ</a></p><br>
		<p onclick="new_job()" id="new_job">DODAJ ZADANIE</p><br>
		<p onclick="okno_sort()" id="sort">SORTOWANIE: <?php echo $_SESSION["sort"] ?></p>
        </div>

        <div id="div_panel">
            <p id="p_timer"></p>
        </div>

        <!-- Wszystkie zadania wyświetlane -->
        <form id="div_jobs" method="GET" action="user.php">
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
                        $div_job_top='<div class="job" id="'.$res["The_ID"].'"><div class="job_topic" id="'.$res["The_ID"].'" onclick="job_popup(this.id)">';
                        $div_job_bottom='</div><input type="button" class="job_button" id="'.$res["The_ID"].'" value="Wykonano" onclick="job_done(this.id)"/></div>';

                        echo $div_job_top;
                        echo "Deadline: ".$res["End"]."<br><br>";
                        
                        $temp = $res["WhoAdd"];
                        $temp_sql = "SELECT Login FROM users WHERE ID='$temp'";
                        $temp_que = mysqli_query($conn, $temp_sql);
                        $temp = mysqli_fetch_array($temp_que);

                        $topic = $res["Topic"];
                        $bufor = "";
                        if(strlen($topic)>150)
                        {
                            for($i=0; $i<150; $i++)
                            {
                                if($i>130 && $topic[$i]==" ")
                                {
                                    echo "...";
                                    $i=149;
                                }
                                else 
                                    echo $topic[$i];
                            }
                        }
                        else
                            $bufor=$topic;

                        echo $bufor."<br><br>";
                        echo "Dodano przez: ".$temp["Login"]."<br>";
                        echo "<span id='job_span_nonim'>ID:".$res["The_ID"]."</span><br>";
                        echo $div_job_bottom;

                        // Przetwarzanie zadania
                        $date_curr = date("Y-m-d");
                        $date_job = $res["End"];
                        $job_important=0;

                        $date_curr_buf="";
                        $date_job_buf="";

                        for($i=0; $i<4; $i++){
                            $date_curr_buf = $date_curr_buf.$date_curr[$i];
                            $date_job_buf = $date_job_buf.$date_job[$i];
                        }

                        $date_curr_var_y=intval($date_curr_buf);
                        $date_job_var_y=intval($date_job_buf);

                        $date_curr_buf="";
                        $date_job_buf="";

                        for($i=5; $i<7; $i++){
                            $date_curr_buf = $date_curr_buf.$date_curr[$i];
                            $date_job_buf = $date_job_buf.$date_job[$i];
                        }

                        $date_curr_var_m=intval($date_curr_buf);
                        $date_job_var_m=intval($date_job_buf);

                        $date_curr_buf="";
                        $date_job_buf="";

                        for($i=8; $i<10; $i++){
                            $date_curr_buf = $date_curr_buf.$date_curr[$i];
                            $date_job_buf = $date_job_buf.$date_job[$i];
                        }

                        $date_curr_var_d=intval($date_curr_buf);
                        $date_job_var_d=intval($date_job_buf);

                        // PRZEBUDOWAĆ MIESIĄCE NA DNI *30!
                        if($date_curr_var_y!=$date_job_var_y)
                            $job_important=2;
                        else if($date_job_var_m>$date_curr_var_m)
                            $job_important=2;
                        else if($date_job_var_d<=$date_curr_var_d+1)
                            $job_important=2;
                        else if($date_job_var_d<=$date_curr_var_d+6)
                            $job_important=1;
                    }
                }

                mysqli_close($conn);
            ?>
        </form>  

        <!-- Zadania nadane -->
        <?php
            $my_id_nadane = $_SESSION["id"];
			// Czy istnieje takie zadanie
            $exist = 1;
			// Czy ten panel jest wymagany
            $already = 0;

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

                if($exist==1){
					if($already==0){
						echo '<div id="div_nadane">';
						echo '<div><h2>Zadania nadane</h2></div>';
						$already=1;
					}
                    $div_job_top='<div class="job" id="'.$the_id.'"><div class="job_topic" id="'.$the_id.'" onclick="job_popup(this.id)">';

                    $div_job_bottom='</div></div>';

                    echo $div_job_top;
                    echo $the_id."<br>";
                    echo "Deadline: ".$res["End"]."<br>";

                    $temp = $res["WhoAdd"];
                    $temp_sql = "SELECT Login FROM users WHERE ID='$temp'";
                    $temp_que = mysqli_query($conn, $temp_sql);
                    $temp = mysqli_fetch_array($temp_que);

                    echo "Dodano przez: ".$temp["Login"]."<br><br>";
                    $topic = $res["Topic"];
                    $bufor = "";
                    if(strlen($topic)>150)
                    {
                            for($i=0; $i<150; $i++)
                            {
                                if($i>130 && $topic[$i]==" ")
                                {
                                    echo "...";
                                    $i=149;
                                }
                                else 
                                    echo $topic[$i];
                            }
                    }
                    else
                        $bufor=$topic;

                    echo $bufor."<br>";
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

            var full_date = data.getFullYear()+"-"+(data.getMonth()+1)+"-"+data.getDate();

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

                timer.innerHTML="<span id='time_day'>"+full_day+"</span><br>"+full_date+"<br><br><span id='time_time'>"+full_time+"</span>";
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