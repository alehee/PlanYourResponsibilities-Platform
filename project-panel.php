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

// SPRAWDZENIE CZY PROJEKT ZOSTAŁ ZAŁADOWANY
if(isset($_SESSION["project_name"])){
    $project_name = $_SESSION["project_name"];
}
else{
    header("location:project.php");
}

if(isset($_SESSION["id"])){
    // UAKTUALNIENIE AKTYWNOŚCI NA PROJEKCIE
    $conn = connect();
    $activity_id = $_SESSION["id"];
    
    $sql = "UPDATE project SET Date=CURRENT_TIMESTAMP WHERE User_ID='$activity_id' AND Name='$project_name'";
    $conn -> query($sql);
    
    $conn -> close();
}
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-2">
        <title>Projekt <?php echo $project_name; ?></title>
        <link rel="stylesheet" href="style/main.css?version=0.4.2"/>
        <link rel="icon" type="image/x-icon" href="icons/favicon.ico">
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

        <div class="project">
            <h1><?php echo $project_name ?></h1>
            <!-- OSOBY UCZESTNICZĄCE W PROJEKCIE --->
            <div class="project_container">
                <div class="project_container_header" style="padding-left:40px;">OSOBY:<span class="project_add_plus" onclick="project_addperson()">+</span><span class="project_add_minus" onclick="project_delperson()">-</span></div>
                <?php
                    $conn = connect();

                    $sql = "SELECT User_ID FROM project WHERE Name='$project_name'";
                    $que = $conn -> query($sql);
                    while($res = mysqli_fetch_array($que)){
                        echo "<div class='project_container_line'>".name_by_id($res["User_ID"])."</div>";
                    }

                    $conn -> close();
                ?>
            </div>
            <!-- PANEL Z ZADANIAMI --->
            <div class="project_container" style="width:48%;">
                <div class="project_container_header">ZADANIA:<span class="project_add_plus" onclick="project_addjob()">+</span></div>
                <?php
                    $conn = connect();

                    $sql = "SELECT * FROM project_jobs WHERE Project_Name='$project_name'";
                    $que = $conn -> query($sql);
                    while($res = mysqli_fetch_array($que)){
                        echo "<div class='project_container_line'>".name_by_id($res["ForWho"])."</div>";
                    }

                    $conn -> close();
                ?>
            </div>
            <!--  --->
            <div class="project_container">
                <div class="project_container_header">NIE WIEM CO:</div>
                <div class="project_container_line">RANDOM SHIET BRUH</div>
                <div class="project_container_line">RANDOM SHIET BRUH</div>
                <div class="project_container_line">RANDOM SHIET BRUH</div>
                <div class="project_container_line">RANDOM SHIET BRUH</div>
            </div>
            <div style="clear:both;"></div>

            <!-- DODAWANIE NOWEGO POSTU --->
            <div class="project_addpost">
                <div class="project_container_header">DODAJ POST:</div>
                
            </div>
            <div style="clear:both;"></div>

            <!-- POSTY --->

        </div>

        <div style="clear:both;"></div>

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

        // Skrypty projektowe

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

        function project_addperson(){
            var addperson_project = <?php echo '"'.$project_name.'"'; ?>;
            if(document.getElementById("okno_background").style.display=="none"){
                document.getElementById("okno_background").style.display="inline";

                $.get("additional/processor.php", {addperson_project: addperson_project}, function(data){
                    $('#okno_job').html(data);
                });
            }
            else if(okno==0){
                document.getElementById("okno_background").style.display="none";
                document.getElementById("new_job_div_1").style.display="none";
            }
            
            okno=0;
        }

        function project_delperson(){

        }

        function project_addjob(){

        }

        function project_deljob(){

        }

        function project_completejob(){
            
        }

        // -----

    </script>
</html>