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
        <title>PYR - Wypełnione zadania</title>
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

        <header>
            <div id="nav_handle"><img src='icons/menu-3-white.png' onclick="nav_open()"/></div>
            <h1 style="width:60%; float:left;">. : Plan Your Responsibilities : .</h1><br>
            <div style="clear:both;"></div>
            <p id="p_timer"></p>
        </header>

        <!-- Panel akcji (wyloguj etc.) --->
        <div id="div_panel">
            <div onclick="nav_classic_link('user.php')" class="done_job_backbutton">PANEL GŁÓWNY</div>
            <div style="clear:both;"></div>
        </div>

        <!-- Zadania -->
        <div id="div_aktualne">
            <div><h2>Zadania ukończone</h2></div>
            <?php
                $my_id=$_SESSION["id"];

                require_once("connection.php");
                $conn = @new mysqli($host, $user_db, $password_db, $db_name);

                $conn -> query("SET CHARSET utf8");
                $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                $sql="SELECT * FROM done WHERE ForWho=$my_id ORDER BY End ASC";

                if(isset($conn))
                {
                    $que= $conn->query($sql);

                    while($res=mysqli_fetch_array($que))
                    {
                        $div_job_top='<div class="done_job_job job" id="'.$res["The_ID"].'" style="background-color:rebeccapurple; border:2px solid rebeccapurple">';
                        $div_job_topic_top = '<div class="done_job_job job_topic_justbg" style="background-color:rebeccapurple; border:2px solid rebeccapurple"><div class="done_job_job job_topic" id="'.$res["The_ID"].'" style="border:2px solid rebeccapurple">';
                        $div_job_topic_bottom = '</div><input type="button" class="job_button" id="'.$res["The_ID"].'" value="Przywróć" onclick="job_undone(this.id)" style="background-color:rebeccapurple; border:2px solid rebeccapurple"/></div>';

                        $div_job_title_top = '<div class="done_job_job job_title" id="'.$res["The_ID"].'" style="background-color:rebeccapurple; border:2px solid rebeccapurple">';
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

        <!-- Div który zbiera śmieci przy jQuery -->
        <div id="thrash"></div>

    <script>

        // Musi tu być bo nie działa skrypt
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
        // Skrypty zakończonych zadań

        function job_undone(id){
            $.get("additional/undone.php", {id: id}, function(data){
                $("#thrash").html(data);
            })
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