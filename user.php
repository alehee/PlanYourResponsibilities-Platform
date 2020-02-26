<?php
session_start();

require_once('additional/func.php');
require_once('additional/navbar.php');
require_once('additional/taskbar.php');
require_once('additional/footer.php');
require_once('connection.php');

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

if(isset($_SESSION["id"])){
// UAKTUALNIENIE AKTYWNOŚCI NA PROFILU
$conn = connect();
$activity_id = $_SESSION["id"];

$sql = "UPDATE users SET Activity=CURRENT_TIMESTAMP WHERE ID='$activity_id'";
$conn -> query($sql);

$conn -> close();
}
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-2">
        <title>Zadania Ogólne</title>
        <link rel="stylesheet" href="style/main.css?version=0.3.0"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body onload="time()" class='normal'>
    <div class="content">

        <!-- Pasek z linkami --->
        <?php echo $navbar ?>
        <?php echo $taskbar ?>

        <!-- Popup okienko zadań -->
        <div id="okno_background" onclick="job_popup()">
            <div class="okno_radius">
            <div id="okno_job" class='okno_job_web' onclick="job_okno()">
            </div>
            </div>
        </div>

        <header>
            <div id="nav_handle"><img src='icons/menu-3-white.png' onclick="nav_open()"/></div>
            <h1 style="width:60%; float:left;">PlanDeca</h1><br>
            <?php
                $task_id = $_SESSION["id"];
                $conn = connect();
                $sql = "SELECT ID FROM task WHERE WhoAdd='$task_id'";
                $que = $conn -> query($sql);
                $num_rows = mysqli_num_rows($que);
                if($num_rows != 0){
                    echo '<div id="task_handle"><img src="icons/briefcase-red.png" onclick="task_open()"/><p id="task_handle_p">'.$num_rows.'</p></div>';
                }
                else{
                    echo '<div id="task_handle"><img src="icons/briefcase-green.png" onclick="task_open()"/></div>';
                }
                
                if($num_rows>9){
                    echo '<script>document.getElementById("task_handle_p").style.marginRight="-35px"</script>';
                }
            ?>
            <div style="clear:both;"></div>
            <p id="p_timer"><br></p>
        </header>

        <!-- Panel akcji --->
        <div id="div_panel">
            <div onclick="new_job()" id="new_job">DODAJ ZADANIE</div>
            <div onclick="okno_sort()" id="sort">SORTOWANIE: <?php echo $_SESSION["sort"] ?></div>
            <div onclick="nav_classic_link('user_done.php')" id="done_job">WYPEŁNIONE ZADANIA</div>
            <div style="clear:both;"></div>
        </div>

        <!-- Zadania -->
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
						$sql="SELECT * FROM job WHERE ForWho=$id AND Type='def' ORDER BY End ASC";
					
					else
						$sql="SELECT * FROM job WHERE ForWho=$id AND Type='def' ORDER BY Length DESC, End ASC";
					
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
            $sql = "SELECT * FROM job WHERE WhoAdd='$my_id_nadane' AND ForWho!='$my_id_nadane' AND Type='def' ORDER BY End ASC";
		
			else
				$sql = "SELECT * FROM job WHERE WhoAdd='$my_id_nadane' AND ForWho!='$my_id_nadane' AND Type='def' ORDER BY Length DESC, End ASC";
		
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
						echo '<div><h2>ZADANIA OGÓLNE - NADANE</h2></div>';
						$already=1;
                    }

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
                        $div_job_topic_bottom = '</div></div>';
                    }
                    else if($days_left<3){
                        $div_job_top='<div class="job_yellow job" id="'.$res["The_ID"].'">';
                        if($unread_msg_exist == 1)
                                $div_job_top=$div_job_top."<img class='job_unread_msg' src='icons/pin-red.png'/>";
                        $div_job_topic_top = '<div class="job_topic_justbg"><div class="job_topic" id="'.$res["The_ID"].'" onclick="job_popup(this.id)">';
                        $div_job_topic_bottom = '</div></div>';
                    }
                    else{
                        $div_job_top='<div class="job" id="'.$res["The_ID"].'">';
                        if($unread_msg_exist == 1)
                                $div_job_top=$div_job_top."<img class='job_unread_msg' src='icons/pin-red.png'/>";
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

                    if(strlen($job_info)>100)
                    {
                            for($i=0; $i<100; $i++)
                            {
                                if($i>80 && $job_info[$i]==" ")
                                {
                                    $bufor=$bufor."...";
                                    $i=99;
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

                    echo "</div>";
                    echo "<div style='clear:both;'></div>";

                    echo $div_job_topic_bottom;
                    echo $div_job_bottom;
                }

                $exist=1;
            }
			
			if($already==1)
				echo '</div>';

            $conn -> close();
        ?>

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
        document.getElementById("okno_background").style.display="none";
        document.getElementById("nav_background").style.display="none";
        document.getElementById("task_background").style.display="none";
        var task_old_info = "";
        var new_job_forwho_toggle_variable = 0;
        var new_job_forwho_close_open_variable = 0;
        var new_job_forwho_peoplenumber = 1;
        
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
        // Skrypty task

        var okno=0;
        function task_hidenot(){
            okno=1;
        }

        function task_hide(){
            if(okno==0){
                var taskback = document.getElementById("task_background");
                taskback.style.display="none";
                document.body.style.overflowY="auto";
            }
            okno=0;
        }

        function task_open(){
            var taskback = document.getElementById("task_background");
            taskback.style.display="inline";
            document.body.style.overflowY="hidden";

            $('textarea').blur();
        }

        function task_add(){
            var newtask = document.createElement("div");
            var task_num = $("#task .task_job_textarea").length;
            task_num++;
            newtask.innerHTML = "<textarea data-autoresize class='task_job_textarea' id='task_"+task_num+"' style='width:90%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false'></textarea><button class='task_job_end_button' id='task_butt_"+task_num+"' onclick='task_done("+task_num+")'>x</button>";
            newtask.className = "task_job";
            newtask.id = "task_job_"+task_num;
            newtask.onclick = function(){
                task_getinfo(task_num);
            };
            newtask.onchange = function(){
                task_change(task_num);
            };
            //document.getElementById("task").appendChild(newtask);

            var lasttask = document.getElementById("task_job_"+(parseInt(task_num)-1));
            document.getElementById("task").insertBefore(newtask, lasttask);

            document.getElementById("task_"+task_num).focus();
            task_old_info = "";

            jQuery.each(jQuery('textarea[data-autoresize]'), function() {
            var offset = this.offsetHeight - this.clientHeight;
            
            var resizeTextarea = function(el) {
                jQuery(el).css('height', 'auto').css('height', el.scrollHeight + offset);
            };
            jQuery(this).on('keyup input', function() { resizeTextarea(this); }).removeAttr('data-autoresize');
            });
        }

        function task_done(task_number){
            var the_task = document.getElementById("task_"+task_number);
            var the_task_info = the_task.value;
            the_task_info = the_task_info.replace(/\n\r?/g, '\\n');

            $.ajax({
                url: "additional/task_processor.php?complete=1&the_task="+the_task_info
            }).done(function(data) { // data what is sent back by the php page
                $('#thrash').html(data); // display data
            });

            document.getElementById("task_job_"+task_number).style.display = "none";
        }

        function task_change(task_number){
            var old_task = task_old_info;
            var new_task = document.getElementById("task_"+task_number).value;
            new_task = new_task.replace(/\n\r?/g, '\\n');

            $.ajax({
                url: "additional/task_processor.php?update=1&old="+old_task+"&new="+new_task
            }).done(function(data) { // data what is sent back by the php page
                $('#thrash').html(data); // display data
            });
        }

        function task_getinfo(task_number){
            var the_task = "task_"+task_number;
            val = document.getElementById(the_task).value;
            val = val.replace(/\n\r?/g, '\\n');
            task_old_info = val;
        }

        jQuery.each(jQuery('textarea[data-autoresize]'), function() {
            var offset = this.offsetHeight - this.clientHeight;
            
            var resizeTextarea = function(el) {
                jQuery(el).css('height', 'auto').css('height', el.scrollHeight + offset);
            };

            jQuery(this).on('blur', function() { resizeTextarea(this); }).removeAttr('data-autoresize');
        });

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
                document.body.style.overflowY="hidden";
                new_job_forwho_toggle_variable = 0;
                new_job_forwho_close_open_variable = 0;
                new_job_forwho_peoplenumber = 1;
                $.get("additional/processor.php", {elem: elem}, function(data){
                    $('#okno_job').html(data);
                });
            }
            else if(okno==0){
                document.body.style.overflowY="auto";
                document.getElementById("okno_background").style.display="none";
                document.getElementById("okno_job").style.backgroundColor="#0082C3";
                document.getElementById("okno_job").style.border="5px solid #0082C3";
            }
            new_job_forwho_toggle_option = 0;
            okno=0;
        }
		
		//Funkcja dodaje osoby do zadania
		function job_addperson(addperson_id){
			$.get("additional/processor.php", {addperson_id: addperson_id}, function(data){
                    $('#okno_job').html(data);
            });
		}

        //Funkcja usuwa osoby z zadania
		function job_delperson(delperson_id){
			$.get("additional/processor.php", {delperson_id: delperson_id}, function(data){
                    $('#okno_job').html(data);
            });
		}

        //Funkcja usuwa całe zadanie
		function job_deljob(deljob_id){
            var can_delete = confirm("Czy na pewno chcesz trwale usunąć te zadanie (nie trafi ono do wykonanych zadań)?");
            
            if(can_delete==true){
                $.get("additional/deljob.php", {deljob_id: deljob_id}, function(data){
                        $('#thrash').html(data);
                });
            }
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

                new_job_forwho_peoplenumber = 1;

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
                var is_empty = 0;
                if(tab[counter].checked == true){
                    for(i=0; i<tab.length; i++){
                        tab[counter].checked=false;
                        counter++;
                    }
                    is_empty = 1;
                }
                else{
                    for(i=0; i<tab.length; i++){
                        tab[counter].checked=true;
                        counter++;
                    }
                }

                var check = $(document).find('input[type=checkbox]:checked').length;
                new_job_forwho_peoplenumber = check / 2;
                document.getElementById("new_job_forwho_peoplenumber_text").innerHTML = new_job_forwho_peoplenumber;
            }
            else if(sklad=="Inesis"){
                var tab = document.getElementsByClassName("ines");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
            else if(sklad=="Wysoki"){
                var tab = document.getElementsByClassName("wskl");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
            else if(sklad=="Domyos"){
                var tab = document.getElementsByClassName("domy");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
            else if(sklad=="B'Twin"){
                var tab = document.getElementsByClassName("btwn");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
            else if(sklad=="Quechua"){
                var tab = document.getElementsByClassName("quec");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
            else if(sklad=="E-commerce"){
                var tab = document.getElementsByClassName("ecom");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
            else if(sklad=="Kalenji"){
                var tab = document.getElementsByClassName("kale");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
            else if(sklad=="Rampa"){
                var tab = document.getElementsByClassName("ramp");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
            else if(sklad=="Subea"){
                var tab = document.getElementsByClassName("sube");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
            else if(sklad=="Geologic"){
                var tab = document.getElementsByClassName("geol");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
            else if(sklad=="Kadry"){
                var tab = document.getElementsByClassName("kadr");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
            else if(sklad=="Kierownicy"){
                var tab = document.getElementsByClassName("kier");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
            else if(sklad=="Szkoleniowcy"){
                var tab = document.getElementsByClassName("szko");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
            else if(sklad=="Stażyści"){
                var tab = document.getElementsByClassName("staz");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
            else if(sklad=="Pracownicy"){
                var tab = document.getElementsByClassName("prac");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
            else if(sklad=="Inna"){
                var tab = document.getElementsByClassName("inna");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
            else if(sklad=="Administracja i Liderzy"){
                var tab = document.getElementsByClassName("admi");
                if(tab[counter].checked == true){
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=false;
                        new_job_forwho_check(tab[counter].value, false);
                        counter++;
                    }
                }
                else{
                    while(tab[counter].checked!="undefined"){
                        tab[counter].checked=true;
                        new_job_forwho_check(tab[counter].value, true);
                        counter++;
                    }
                }
            }
        }

        // Funkcja przełącza widok zaznaczania osób
        function new_job_forwho_toggle(){

            var role = document.getElementsByClassName("new_job_forwho_rola");
            var role_list = document.getElementsByClassName("new_job_forwho_rola_list");
            var dzialy = document.getElementsByClassName("new_job_forwho_dzial");
            var dzialy_list = document.getElementsByClassName("new_job_forwho_dzial_list");
            var butt_role = document.getElementsByClassName("new_job_rola_butt");
            var butt_dzialy = document.getElementsByClassName("new_job_dzial_butt");

            var inesis = document.getElementsByClassName("ines");
            var domyos = document.getElementsByClassName("domy");
            var quechua = document.getElementsByClassName("quec");
            var kalenji = document.getElementsByClassName("kale");
            var subea = document.getElementsByClassName("sube");
            var wysoki = document.getElementsByClassName("rpal");
            var btwin = document.getElementsByClassName("btwn");
            var ecommerce = document.getElementsByClassName("ecom");
            var rampa = document.getElementsByClassName("ramp");
            var geologic = document.getElementsByClassName("geol");
            var kadry = document.getElementsByClassName("kadr");
            var kierownicy = document.getElementsByClassName("kier");
            var stazysci = document.getElementsByClassName("staz");
            var inna = document.getElementsByClassName("inna");
            var szkoleniowcy = document.getElementsByClassName("szko");
            var pracownicy = document.getElementsByClassName("prac");

            if(new_job_forwho_toggle_variable == 0){
                for(i=0; i<role.length; i++){
                    role[i].style.display = "block";
                }
                for(i=0; i<dzialy.length; i++){
                    dzialy[i].style.display = "none";
                    dzialy_list[i].style.display = "none";
                }
                new_job_forwho_toggle_variable = 1;
                new_job_forwho_close_open_variable = 0;

                for(i=0; i<butt_dzialy.length; i++){
                    butt_dzialy[i].style.display = "none";
                }
                for(i=0; i<butt_role.length; i++){
                    butt_role[i].style.display = "inline";
                }
            }
            else{
                for(i=0; i<role.length; i++){
                    role[i].style.display = "none";
                    role_list[i].style.display = "none";
                }
                for(i=0; i<dzialy.length; i++){
                    dzialy[i].style.display = "block";
                }
                new_job_forwho_toggle_variable = 0;
                new_job_forwho_close_open_variable = 0;

                for(i=0; i<butt_dzialy.length; i++){
                    butt_dzialy[i].style.display = "inline";
                }
                for(i=0; i<butt_role.length; i++){
                    butt_role[i].style.display = "none";
                }
            }
        }

        // Funkcja otwiera wszystkie zakładki osób
        function new_job_forwho_open_close(){
            var role_list = document.getElementsByClassName("new_job_forwho_rola_list");
            var dzialy_list = document.getElementsByClassName("new_job_forwho_dzial_list");

            if(new_job_forwho_close_open_variable == 0 && new_job_forwho_toggle_variable == 0){
                for(i=0; i<dzialy_list.length; i++){
                    dzialy_list[i].style.display = "block";
                }
                new_job_forwho_close_open_variable = 1;
            }
            else if(new_job_forwho_close_open_variable == 1 && new_job_forwho_toggle_variable == 0){
                for(i=0; i<dzialy_list.length; i++){
                    dzialy_list[i].style.display = "none";
                }
                new_job_forwho_close_open_variable = 0;
            }
            else if(new_job_forwho_close_open_variable == 0 && new_job_forwho_toggle_variable == 1){
                for(i=0; i<role_list.length; i++){
                    role_list[i].style.display = "block";
                }
                new_job_forwho_close_open_variable = 1;
            }
            else if(new_job_forwho_close_open_variable == 1 && new_job_forwho_toggle_variable == 1){
                for(i=0; i<role_list.length; i++){
                    role_list[i].style.display = "none";
                }
                new_job_forwho_close_open_variable = 0;
            }
        }

        // Funkcja zaznacza osoby w obu trybach
        function new_job_forwho_check(passed_id, passed_check){
            var changed_id = document.getElementsByClassName(passed_id);

                if(passed_check){
                    for(i=0; i<changed_id.length; i++){
                        changed_id[i].checked = true;
                    }

                    new_job_forwho_peoplenumber++;
                }

                else{
                    for(i=0; i<changed_id.length; i++){
                            changed_id[i].checked = false;
                        }

                    new_job_forwho_peoplenumber--;
                }

            var check = $(document).find('input[type=checkbox]:checked').length;
            new_job_forwho_peoplenumber = check / 2;
            document.getElementById("new_job_forwho_peoplenumber_text").innerHTML = new_job_forwho_peoplenumber;

            //document.getElementById("new_job_forwho_peoplenumber_text").innerHTML = new_job_forwho_peoplenumber;
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