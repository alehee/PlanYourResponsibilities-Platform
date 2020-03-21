<?php
session_start();

require_once('additional/func.php');
require_once('additional/navbar.php');
require_once('additional/taskbar.php');
require_once('additional/footer.php');

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
        <title>Wypełnione Zadania</title>
        <link rel="stylesheet" href="style/main.css?version=0.4.0"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body onload="time()">
    <div class="content">

        <!-- Pasek z linkami --->
        <?php echo $navbar ?>
        <?php echo $taskbar ?>

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
            <p id="p_timer"></p>
        </header>

        <!-- Panel akcji (wyloguj etc.) --->
        <div id="div_panel">
            <div onclick="nav_classic_link('user.php')" class="done_job_backbutton">PANEL GŁÓWNY</div>
            <div style="clear:both;"></div>
        </div>

        <!-- Zadania -->
        <div id="div_aktualne">
            <div><h2>ZADANIA UKOŃCZONE</h2></div>
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

                        // ILOŚĆ OSÓB W ZADANIU
                        $the_id = $res["The_ID"];
                        $how_many_per=0;
                        $temp_sql="SELECT ForWho FROM job WHERE The_ID=$the_id";
                        $temp_que=mysqli_query($conn, $temp_sql);
                        while($temp_res = mysqli_fetch_array($temp_que)){
                            $how_many_per++;
                        }
                        echo "<div class='job_small_info'/><img src='icons/users.png'/>".$how_many_per."</div>";
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

                        echo "</div>";
                        echo "<div style='clear:both;'></div>";

                        echo $div_job_topic_bottom;
                        echo $div_job_bottom;
                    }
                }

                mysqli_close($conn);
            ?>
        </div>

    </div>

        <?php
            echo "<div style='clear:both;'></div>";
            echo $footer;
        ?>

        <!-- Div który zbiera śmieci przy jQuery -->
        <div id="thrash"></div>

    <script>

        // Musi tu być bo nie działa skrypt
        document.getElementById("nav_background").style.display="none";
        document.getElementById("task_background").style.display="none";
        var task_old_info = "";

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
        // Skrypty zakończonych zadań

        function job_undone(id){
            $.get("additional/undone.php", {id: id}, function(data){
                $("#thrash").html(data);
            })
        }

        // -----

    </script>
</html>