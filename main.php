<?php
session_start();

require_once('additional/func.php');
require_once('additional/navbar.php');
require_once('additional/footer.php');

if(!isset($_SESSION["log"]) || !isset($_SESSION["id"]))
{
    header("location:index.php");
    exit();
}

if(!isset($_SESSION["sort"]))
    $_SESSION["sort"]='Deadline';
    
if(isset($_SESSION["error"])){
    echo '<script>alert("'.$_SESSION["error"].'")</script>';
    unset($_SESSION["error"]);
}
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-2">
        <title>Panel Główny</title>
        <link rel="stylesheet" href="style/main.css?version=0.2.0"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body onload="time()" class='normal'>
    <div class="content">

        <!-- Pasek z linkami --->
        <?php echo $navbar ?>

        <header>
            <div id="nav_handle"><img src='icons/menu-3-white.png' onclick="nav_open()"/></div>
            <h1 style="width:60%; float:left;">PlanDeca</h1><br>
            <div style="clear:both;"></div>
            <p id="p_timer"><br></p>
        </header>

        <!-- Panel z zadaniami wszystkimi --->
        <div class="main">

            <!-- Panel z zadaniami ogólnymi --->
            <div id="div_aktualne">
                <div><h2>ZADANIA OGÓLNE</h2></div>
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
                            $sql="SELECT * FROM job WHERE ForWho=$id AND Type='def' ORDER BY End ASC LIMIT 5";
                        
                        else
                            $sql="SELECT * FROM job WHERE ForWho=$id AND Type='def' ORDER BY Length DESC, End ASC LIMIT 5";
                        
                        $que=mysqli_query($conn, $sql);

                        while($res=mysqli_fetch_array($que))
                        {
                            $days_left = how_many_days_left($res["End"]);
                            $div_job_top="";

                            // NIEODCZYTANE WIADOMOŚCI I CZY JEST NOWE
                            $the_id_chat_msg = $res["The_ID"];
                            if($id != $res["WhoAdd"])
                                $chat_msg_visited = $res["Visited"];
                            else
                                $chat_msg_visited = $res["Visited_Admin"];
                            $unread_msg_exist = 0;
                            $temp_sql="SELECT ID FROM chat WHERE The_ID='$the_id_chat_msg' AND SentFrom!=$id AND Date > '$chat_msg_visited' ORDER BY Date DESC";
                            $temp_que = mysqli_query($conn, $temp_sql);
                            while($temp_res = mysqli_fetch_array($temp_que))
                                $unread_msg_exist = 1;

                            $temp_sql = "SELECT ID FROM job WHERE The_ID='$the_id_chat_msg' AND WhoAdd!=$id AND Start >= '$chat_msg_visited'";
                            $temp_que = mysqli_query($conn, $temp_sql);
                            while($temp_res = mysqli_fetch_array($temp_que))
                                $unread_msg_exist = 1;

                            if($days_left<=0){
                                $div_job_top='<div class="job_red job" id="'.$res["The_ID"].'">';
                                if($unread_msg_exist == 1)
                                    $div_job_top=$div_job_top."<img class='job_unread_msg' src='icons/pin-red.png'/>";
                                $div_job_topic_top = '<div class="job_topic_justbg"><div class="job_topic" id="'.$res["The_ID"].'" onclick="job_popup(this.id)">';
                                $div_job_topic_bottom = '</div></div><input type="button" class="job_button" id="'.$res["The_ID"].'" value="Zakończ" onclick="job_done(this.id)"/>';
                            }
                            else if($days_left<3){
                                $div_job_top='<div class="job_yellow job" id="'.$res["The_ID"].'">';
                                if($unread_msg_exist == 1)
                                    $div_job_top=$div_job_top."<img class='job_unread_msg' src='icons/pin-red.png'/>";
                                $div_job_topic_top = '<div class="job_topic_justbg"><div class="job_topic" id="'.$res["The_ID"].'" onclick="job_popup(this.id)">';
                                $div_job_topic_bottom = '</div></div><input type="button" class="job_button" id="'.$res["The_ID"].'" value="Zakończ" onclick="job_done(this.id)"/>';
                            }
                            else{
                                $div_job_top='<div class="job" id="'.$res["The_ID"].'">';
                                if($unread_msg_exist == 1)
                                    $div_job_top=$div_job_top."<img class='job_unread_msg' src='icons/pin-red.png'/>";
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
                                        $bufor=$bufor."...</b>";
                                        $i=99;
                                    }
                                    else 
                                        $bufor=$bufor.$topic[$i];
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

                            // ILOŚĆ OSÓB W ZADANIU
                            $the_id = $res["The_ID"];
                            $how_many_per=0;
                            $temp_sql="SELECT ForWho FROM job WHERE The_ID=$the_id";
                            $temp_que=mysqli_query($conn, $temp_sql);
                            while($temp_res = mysqli_fetch_array($temp_que)){
                                $how_many_per++;
                            }
                            $temp_sql="SELECT ForWho FROM done WHERE The_ID=$the_id";
                            $temp_que=mysqli_query($conn, $temp_sql);
                            while($temp_res = mysqli_fetch_array($temp_que)){
                                $how_many_per++;
                            }
                            echo "<div class='job_small_info'/><img src='icons/users.png'/>".$how_many_per."</div>";
                            // -----

                            // DŁUGOŚĆ ZADANIA
                            if($res["Length"]==1){
                                echo "<div class='job_small_info'><img src='icons/speed-1.png' style='padding:0; padding-left:12px;'/></div>";
                            }
                            else if($res["Length"]==2){
                                echo "<div class='job_small_info'><img src='icons/speed-2.png' style='padding:0; padding-left:12px;'/></div>";
                            }
                            else{
                                echo "<div class='job_small_info'><img src='icons/speed-3.png' style='padding:0; padding-left:12px;'/></div>";
                            }
                            // -----

                            // INFORMACJE DODATKOWE W ZADANIU
                            $job_info = $res["Info"];
                            $bufor = "";

                            if(strlen($job_info)>200)
                            {
                                    for($i=0; $i<200; $i++)
                                    {
                                        if($i>180 && $job_info[$i]==" ")
                                        {
                                            $bufor=$bufor."...";
                                            $i=199;
                                        }
                                        else 
                                            $bufor=$bufor.$job_info[$i];
                                    }
                            }
                            else
                                $bufor=$job_info;

                            echo "<div class='job_info'>".$bufor."</div>";
                            echo "<div style='clear:both;'></div>";
                            // -----

                            // KTO DODAŁ ZADANIE
                            echo "<div style='clear:both;'><div class='job_small_info_plus' style='width:75%;'><img src='icons/user.png'/>".name_by_id($res["WhoAdd"])."</div>";
                            echo "</div>";
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
                            echo "<div class='job_small_info' style='width:20%'><img src='icons/attachment.png'/>".$how_many_atta."</div>";
                            // -----

                            echo "<div style='clear:both;'></div>";

                            echo $div_job_topic_bottom;
                            echo $div_job_bottom;
                        }
                    }

                    mysqli_close($conn);
                ?>
            </div>

            <!-- Panel z zadaniami kadrowymi --->
            <div id="div_aktualne">
                <div><h2>ZADANIA KADROWE</h2></div>
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
                            $sql="SELECT * FROM job WHERE ForWho=$id AND Type='sta' ORDER BY End ASC LIMIT 5";
                        
                        else
                            $sql="SELECT * FROM job WHERE ForWho=$id AND Type='sta' ORDER BY Length DESC, End ASC LIMIT 5";
                        
                        $que=mysqli_query($conn, $sql);

                        while($res=mysqli_fetch_array($que))
                        {
                            $days_left = how_many_days_left($res["End"]);
                            $div_job_top="";

                            // NIEODCZYTANE WIADOMOŚCI I CZY JEST NOWE
                            $the_id_chat_msg = $res["The_ID"];
                            if($id != $res["WhoAdd"])
                                $chat_msg_visited = $res["Visited"];
                            else
                                $chat_msg_visited = $res["Visited_Admin"];
                            $unread_msg_exist = 0;
                            $temp_sql="SELECT ID FROM chat WHERE The_ID='$the_id_chat_msg' AND SentFrom!=$id AND Date > '$chat_msg_visited' ORDER BY Date DESC";
                            $temp_que = mysqli_query($conn, $temp_sql);
                            while($temp_res = mysqli_fetch_array($temp_que))
                                $unread_msg_exist = 1;

                            $temp_sql = "SELECT ID FROM job WHERE The_ID='$the_id_chat_msg' AND WhoAdd!=$id AND Start >= '$chat_msg_visited'";
                            $temp_que = mysqli_query($conn, $temp_sql);
                            while($temp_res = mysqli_fetch_array($temp_que))
                                $unread_msg_exist = 1;

                            if($days_left<=0){
                                $div_job_top='<div class="job_red job" id="'.$res["The_ID"].'">';
                                if($unread_msg_exist == 1)
                                    $div_job_top=$div_job_top."<img class='job_unread_msg' src='icons/pin-red.png'/>";
                                $div_job_topic_top = '<div class="job_topic_justbg"><div class="job_topic" id="'.$res["The_ID"].'" onclick="job_popup(this.id)">';
                                $div_job_topic_bottom = '</div></div><input type="button" class="job_button" id="'.$res["The_ID"].'" value="Zakończ" onclick="job_done(this.id)"/>';
                            }
                            else if($days_left<3){
                                $div_job_top='<div class="job_yellow job" id="'.$res["The_ID"].'">';
                                if($unread_msg_exist == 1)
                                    $div_job_top=$div_job_top."<img class='job_unread_msg' src='icons/pin-red.png'/>";
                                $div_job_topic_top = '<div class="job_topic_justbg"><div class="job_topic" id="'.$res["The_ID"].'" onclick="job_popup(this.id)">';
                                $div_job_topic_bottom = '</div></div><input type="button" class="job_button" id="'.$res["The_ID"].'" value="Zakończ" onclick="job_done(this.id)"/>';
                            }
                            else{
                                $div_job_top='<div class="job" id="'.$res["The_ID"].'">';
                                if($unread_msg_exist == 1)
                                    $div_job_top=$div_job_top."<img class='job_unread_msg' src='icons/pin-red.png'/>";
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
                                        $bufor=$bufor."...</b>";
                                        $i=99;
                                    }
                                    else 
                                        $bufor=$bufor.$topic[$i];
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

                            // ILOŚĆ OSÓB W ZADANIU
                            $the_id = $res["The_ID"];
                            $how_many_per=0;
                            $temp_sql="SELECT ForWho FROM job WHERE The_ID=$the_id";
                            $temp_que=mysqli_query($conn, $temp_sql);
                            while($temp_res = mysqli_fetch_array($temp_que)){
                                $how_many_per++;
                            }
                            $temp_sql="SELECT ForWho FROM done WHERE The_ID=$the_id";
                            $temp_que=mysqli_query($conn, $temp_sql);
                            while($temp_res = mysqli_fetch_array($temp_que)){
                                $how_many_per++;
                            }
                            echo "<div class='job_small_info'/><img src='icons/users.png'/>".$how_many_per."</div>";
                            // -----

                            // DŁUGOŚĆ ZADANIA
                            if($res["Length"]==1){
                                echo "<div class='job_small_info'><img src='icons/speed-1.png' style='padding:0; padding-left:12px;'/></div>";
                            }
                            else if($res["Length"]==2){
                                echo "<div class='job_small_info'><img src='icons/speed-2.png' style='padding:0; padding-left:12px;'/></div>";
                            }
                            else{
                                echo "<div class='job_small_info'><img src='icons/speed-3.png' style='padding:0; padding-left:12px;'/></div>";
                            }
                            // -----

                            // INFORMACJE DODATKOWE W ZADANIU
                            $job_info = $res["Info"];
                            $bufor = "";

                            if(strlen($job_info)>200)
                            {
                                    for($i=0; $i<200; $i++)
                                    {
                                        if($i>180 && $job_info[$i]==" ")
                                        {
                                            $bufor=$bufor."...";
                                            $i=199;
                                        }
                                        else 
                                            $bufor=$bufor.$job_info[$i];
                                    }
                            }
                            else
                                $bufor=$job_info;

                            echo "<div class='job_info'>".$bufor."</div>";
                            echo "<div style='clear:both;'></div>";
                            // -----

                            // KTO DODAŁ ZADANIE
                            echo "<div style='clear:both;'><div class='job_small_info_plus' style='width:75%;'><img src='icons/user.png'/>".name_by_id($res["WhoAdd"])."</div>";
                            echo "</div>";
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
                            echo "<div class='job_small_info' style='width:20%'><img src='icons/attachment.png'/>".$how_many_atta."</div>";
                            // -----

                            echo "<div style='clear:both;'></div>";

                            echo $div_job_topic_bottom;
                            echo $div_job_bottom;
                        }
                    }

                    mysqli_close($conn);
                ?>
            </div>

        </div>

    </div>

        <?php
            echo "<div style='clear:both;'></div>";
            echo $footer;
        ?>
        

    <!-- Div który zbiera śmieci przy jQuery -->
    <div id="thrash"></div>

    </body>

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
                document.body.style.overflowY="auto";
            }
            okno=0;
        }

        function nav_open(){
            var navback = document.getElementById("nav_background");
            navback.style.display="inline";
            document.body.style.overflowY="hidden";
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
    </script>
</html>