<?php
session_start();

require_once('additional/func.php');
require_once('additional/navbar.php');
require_once('additional/taskbar.php');
require_once('additional/footer.php');

$conn = connect();

if(!isset($_SESSION["log"]) || !isset($_SESSION["id"]))
{
    header("location:index.php");
    exit();
}

else{
    $id = $_SESSION['id'];

    $is_su=0;
	$sql = "SELECT ID FROM susers WHERE User_ID='$id'";
	$que = $conn -> query($sql);
	while($res = mysqli_fetch_array($que))
		$is_su=1;

    if($is_su == 0){
        header("location:user.php");
    }
}

if(!isset($_SESSION["sort"]))
	$_SESSION["sort"]='Deadline';
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-2">
        <title>Panel Statystyk</title>
        <link rel="stylesheet" href="style/main.css?version=0.3.0"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body onload="time()" class='normal'>
    <div class="content">

        <!-- Pasek z linkami --->
        <?php echo $navbar ?>
        <?php echo $taskbar ?>

        <header>
            <div id="nav_handle"><img src='icons/menu-3-white.png' onclick="nav_open()"/></div>
            <h1 style="width:60%; float:left;">PlanDeca</h1><br>
            <div id="task_handle"><img src='icons/briefcase.png' onclick="task_open()"/></div>
            <div style="clear:both;"></div>
            <p id="p_timer"><br></p>
        </header>

        <div class="stats_panel">
            <div style="text-align:center; font-size:200%; padding:20px;"><b>STATYSTYKI</b></div>
            <table class="stats_table">
            <tr style="color:#0082C3"><td><b>IMIĘ</b></td><td><b>NAZWISKO</b></td><td><b>GRUPA</b></td><td><b style='color:green'>ILOŚĆ ZADAŃ AKTYWNYCH</b></td><td><b style='color:red'>ILOŚĆ SPÓŹNIEŃ</b></td><td><b style='color:rebeccapurple'>OSTATNIA AKTYWNOŚĆ</b></td></tr>
                <?php 
                require_once("connection.php");

                $city = $_SESSION["city"];

                $conn -> query("SET CHARSET utf8");
	            $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                $sql = "SELECT * FROM users WHERE Jednostka='$city' ORDER BY Dzial ASC";
                $que = $conn -> query($sql);
                while($res = mysqli_fetch_array($que)){
                    // ID
                    $stat_id = $res["ID"];
                    // IMIE
                    $stat_imie = $res["Imie"];
                    // NAZWISKO
                    $stat_nazwisko = $res["Nazwisko"];
                    // DZIAL
                    $stat_dzial = $res["Dzial"];
                    switch($stat_dzial){
                        case 'nskl':
                            $stat_dzial = "Niski Skład";
                        break;
                        case 'wskl':
                            $stat_dzial = "Wysoki Skład";
                        break;
                        case 'ecom':
                            $stat_dzial = "E-commerce";
                        break;
                        case 'ramp':
                            $stat_dzial = "Rampa";
                        break;
                        case 'resz':
                            $stat_dzial = "Reszta";
                        break;
                    }
                    // OSTATNIA AKTYWNOŚĆ
                    $stat_activity = $res["Activity"];
                    // ILOŚĆ ZADAŃ
                    $stat_ilo_zad=0;
                    $temp_sql = "SELECT COUNT(ForWho) as ilo_zad FROM job WHERE ForWho='$stat_id' GROUP BY ForWho";
                    $temp_que = $conn -> query($temp_sql);
                    while($temp_res = mysqli_fetch_array($temp_que)){
                        $stat_ilo_zad = $temp_res["ilo_zad"];
                    }
                    // ILOŚĆ ZADAŃ PO CZASIE
                    $stat_spoznien = $res["Spoznien"];

                    echo "<tr>";

                    echo "<td>$stat_imie</td><td>$stat_nazwisko</td><td>$stat_dzial</td><td><b style='color:green'>$stat_ilo_zad</b></td><td><b style='color:red'>$stat_spoznien</b></td><td><b style='color:rebeccapurple'>$stat_activity</b></td>";

                    echo "</tr>";
                }

                $conn -> close();
                ?>
            </table>
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
    </script>
</html>