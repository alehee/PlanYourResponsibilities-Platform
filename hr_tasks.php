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
    
if(isset($_SESSION["error"])){
    echo '<script>alert("'.$_SESSION["error"].'")</script>';
    unset($_SESSION["error"]);
}

if($_SESSION["rola"]!="kadr" && $_SESSION["id"]!="6"){
    header("location:main.php");
}

if(isset($_SESSION["hr_reload"])){
    echo '<script>location.reload(true);</script>';
    unset($_SESSION["hr_reload"]);
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
        <title>Panel Kadr</title>
        <link rel="stylesheet" href="style/main.css?version=0.4.4"/>
        <link rel="icon" type="image/x-icon" href="icons/favicon.ico">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body onload="time()" class='normal'>
    <div class="content">

        <!-- Pasek z linkami --->
        <?php echo $navbar ?>
        <?php echo $taskbar ?>

        <!-- Popup dodaj zadanie -->
        <div id="okno_background" onclick="hr_tasks_hide()">
            <div id="hr_task_form" onclick="hr_tasks_hidenot()">
                <form action="additional/hr_task_processor.php" method="POST">
                <div>
                    <label>Informacje:</label><br>
                    <input type="text" placeholder="Informacje do zadania" name="add_info">
                </div>
                <div>
                    <label>Deadline:</label><br>
                    <input type="date" name="add_date">
                </div>
                <button type="submit">DODAJ ZADANIE</button>
                </form>
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

        <div class="hr_tasks">
            <h2 style="font-size:250%;">PANEL KADR</h2>

            <div id="div_panel" style="margin-top:-20px; margin-bottom:10px;">
                <div onclick="hr_tasks_open()" id="new_job">DODAJ ZADANIE</div>
                <div onclick="hr_tasks_weekFocus()" id="sort">AKTUALNY TYDZIEŃ</div>
                <div onclick="hr_tasks_reload()" id="done_job">ODŚWIEŻ LISTĘ</div>
                <div style="clear:both;"></div>
            </div>
            <div style="clear:both;"></div>

            <div id="hr_list_hide" onclick="hideHrList('2021')" style="">2021</div>
            <div id="hr_list" class="hr_list_2021">
                <?php
                    $weekStart = "2020-12-21";
                    $weekEnd = "2020-12-27";
                    $weekNumber = -1;

                    /// POKAŻ WSZYSTKIE TYGODNIE
                    $conn = connect();
                    $conn -> query("SET CHARSET utf8");
                    $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
                    for($i=$weekNumber; $i<52; $i++){
                        $weekStart = date("Y-m-d" ,strtotime("+1 week",  strtotime($weekStart)));
                        $weekEnd = date("Y-m-d" ,strtotime("+1 week",  strtotime($weekEnd)));
                        $weekNumber++;

                        echo '  <div class="hr_week" id="week_'.$weekNumber.'">
                                    <h2>T'.$weekNumber.' <br><span style="font-size:40%">'.proper_date($weekStart).' - '.proper_date($weekEnd).'</span></h2>
                                    <div class="hr_list" id="2003">';

                        for($j=0; $j<7; $j++){
                            $weekDay = date("Y-m-d" ,strtotime("+".$j." day",  strtotime($weekStart)));
                            $weekDayProper = proper_date($weekDay);

                            if($weekDay[0].$weekDay[1].$weekDay[2].$weekDay[3]=="2021"){
                                echo '  <div class="hr_week_note"><h3>'.$weekDayProper.'</h3><br>';

                                /// SPRAWDZENIE NOTATKI DNIA
                                $noteWeekDay = "2031-".$weekDay[5].$weekDay[6].$weekDay[7].$weekDay[8].$weekDay[9];
                                $noteWeekDayProper = proper_date($noteWeekDay);
                                $noteWeekDayDisplay = substr($noteWeekDayProper, 0, 7);
                                $sql = "SELECT * FROM hr_tasks WHERE Deadline='$noteWeekDay' LIMIT 1";
                                $que = $conn -> query($sql);
                                while($res = mysqli_fetch_array($que)){
                                    $hr_task_id = $res["ID"];
                                    $hr_task_whoadd = $res["WhoAdd"];
                                    $hr_task_adddate = $res["AddDate"];
                                    $hr_task_deadline = $res["Deadline"];
                                    $hr_task_info = $res["Info"];
                                    $hr_task_infoadd = $res["InfoAdd"];
                                    $hr_task_completed = $res["Completed"];
                                    $hr_task_whocompleted = $res["WhoCompleted"];
                                    $hr_task_completeddate = $res["CompletedDate"];
                                    
                                    echo '      <div style="color: #006699; font-style: italic; margin-left: 20px;">RANO <span style="color:black; font-size:60%; font-style: italic; margin-left: 10px;">Ostatnia edycja: '.name_by_id($hr_task_whoadd).'</span></div>';
                                    echo "      <div class='task_job' id='$hr_task_id'><textarea data-autoresize placeholder='Notatka dnia rano...' class='task_job_textarea' id='textarea_$hr_task_id' style='margin-top:-10px; width:100%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false' onchange='hr_tasks_updatenote($hr_task_id)'>$hr_task_info</textarea></div>";
                                    echo '      <div style="color: #006699; font-style: italic; margin-left: 20px;">POPO <span style="color:black; font-size:60%; font-style: italic; margin-left: 10px;">Ostatnia edycja: '.name_by_id($hr_task_whocompleted).'</span></div>';
                                    echo "      <div class='task_job' id='$hr_task_id'><textarea data-autoresize placeholder='Notatka dnia popołudnie...' class='task_job_textarea' id='textarea_add_$hr_task_id' style='margin-top:-10px; width:100%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false' onchange='hr_tasks_updatenoteadd($hr_task_id)'>$hr_task_infoadd</textarea></div>";
                                }
                                /// ==========

                                /// WYPISANIE ZADAŃ DLA DNIA
                                $sql = "SELECT * FROM hr_tasks WHERE Deadline='$weekDay'";
                                $que = $conn -> query($sql);
                                if(mysqli_num_rows($que)!=0){
                                    echo '     <div style="color: #006699; font-style: italic; margin-left: 20px;">ZADANIA</div>';
                                }
                                while($res = mysqli_fetch_array($que)){
                                    $hr_task_id = $res["ID"];
                                    $hr_task_whoadd = $res["WhoAdd"];
                                    $hr_task_adddate = $res["AddDate"];
                                    $hr_task_deadline = $res["Deadline"];
                                    $hr_task_info = $res["Info"];
                                    $hr_task_infoadd = $res["InfoAdd"];
                                    $hr_task_completed = $res["Completed"];
                                    $hr_task_whocompleted = $res["WhoCompleted"];
                                    $hr_task_completeddate = $res["CompletedDate"];

                                    $string = $hr_task_info;
                                    $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
                                    $string = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $string);

                                    echo '  <div class="hr_task_title" id="hr_task_title_'.$hr_task_id.'" onclick="hr_tasks_toggleDiv('.$hr_task_id.')">'.$string.'</div>';
                                    echo '      <div class="hr_task_body" id="'.$hr_task_id.'">
                                                    <div><div style="float:left; width:80%;"><img style="float:left; height:15px; margin-left:10%; margin-right:20%;" src="icons/add.png" /><div style="float:left; color:white; height:15px; width:30%; text-align:center">'.name_by_id($hr_task_whoadd).'</div><div style="float:left; color:white; height:15px; width:30%; text-align:center">'.proper_date($hr_task_adddate).'</div></div><button class="ri_job_end_button" id="button_delete_'.$hr_task_id.'" style="background-color:#006699;" onclick="hr_tasks_delete('.$hr_task_id.')">x</button><button class="ri_job_complete_button" id="button_check_'.$hr_task_id.'" style="background-color:#006699; color:#00FF00;" onclick="hr_tasks_check('.$hr_task_id.')">✓</button></div>';
                                    if($hr_task_completed == "true"){
                                        echo '          <div><div style="float:left; width:80%;"><img style="float:left; height:15px; margin-left:10%; margin-right:20%;" src="icons/done.png" /><div style="float:left; color:white; height:15px; width:30%; text-align:center">'.name_by_id($hr_task_whocompleted).'</div><div style="float:left; color:white; height:15px; width:30%; text-align:center">'.proper_date($hr_task_completeddate).'</div></div></div>';
                                        echo "          <script>
                                                            $('#$hr_task_id').css('background-color', 'green');
                                                            $('#hr_task_title_$hr_task_id').css('background-color', 'green');
                                                            $('#button_check_$hr_task_id').css('color', 'gray');
                                                            $('#button_check_$hr_task_id').css('background-color', 'green');
                                                            $('#button_delete_$hr_task_id').css('display', 'none');
                                                        </script>";
                                    }
                                    else{
                                        echo '          <div><div style="float:left; width:80%;"><img style="float:left; height:15px; margin-left:10%; margin-right:20%;" src="icons/done.png" /><div style="float:left; color:white; height:15px; width:30%; text-align:center;">-</div><div style="float:left; color:white; height:15px; width:30%; text-align:center">-</div></div></div>';
                                        echo "          <script>
                                                            $('#$hr_task_id').css('background-color', '#006699');
                                                            $('#hr_task_title_$hr_task_id').css('background-color', '#006699');
                                                            $('#button_check_$hr_task_id').css('color', '#00FF00');
                                                            $('#button_check_$hr_task_id').css('background-color', '#006699');
                                                            $('#button_delete_$hr_task_id').css('display', 'inline');
                                                        </script>";
                                    }
                                    echo "          <div style='clear:both;'></div>";
                                    echo "          <div class='task_job' id='$hr_task_id' style='margin-top:10px;'><textarea data-autoresize placeholder='Notatka do zadania...' class='hr_task_textarea' id='textarea_hrtask_$hr_task_id' rows='1' spellcheck='false' onchange='hr_tasks_updatenotetask($hr_task_id)'>$hr_task_infoadd</textarea></div>";
                                    echo "          <div style='clear:both;'></div>";
                                    echo '      </div>';
                                }
                                /// ==========
                                echo '  </div>';
                            }
                        }

                        echo '      </div>
                                </div>';
                    }
                    $conn -> close();
                    /// ==========

                ?>
            </div>

            <div id="hr_list_hide" onclick="hideHrList('2020')">2020</div>
            <div id="hr_list" class="hr_list_2020">
                <?php
                    $weekStart = "2020-08-10";
                    $weekEnd = "2020-08-16";
                    $weekNumber = 33;

                    /// POKAŻ WSZYSTKIE TYGODNIE
                    $conn = connect();
                    $conn -> query("SET CHARSET utf8");
                    $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
                    for($i=$weekNumber; $i<53; $i++){
                        $weekStart = date("Y-m-d" ,strtotime("+1 week",  strtotime($weekStart)));
                        $weekEnd = date("Y-m-d" ,strtotime("+1 week",  strtotime($weekEnd)));
                        $weekNumber++;

                        echo '  <div class="hr_week" id="week_'.$weekNumber.'">
                                    <h2>T'.$weekNumber.' <br><span style="font-size:40%">'.proper_date($weekStart).' - '.proper_date($weekEnd).'</span></h2>
                                    <div class="hr_list" id="2003">';

                        for($j=0; $j<7; $j++){
                            $weekDay = date("Y-m-d" ,strtotime("+".$j." day",  strtotime($weekStart)));
                            $weekDayProper = proper_date($weekDay);

                            if($weekDay[0].$weekDay[1].$weekDay[2].$weekDay[3]=="2020"){
                                echo '  <div class="hr_week_note"><h3>'.$weekDayProper.'</h3><br>';

                                /// SPRAWDZENIE NOTATKI DNIA
                                $noteWeekDay = "2030-".$weekDay[5].$weekDay[6].$weekDay[7].$weekDay[8].$weekDay[9];
                                $noteWeekDayProper = proper_date($noteWeekDay);
                                $noteWeekDayDisplay = substr($noteWeekDayProper, 0, 7);
                                $sql = "SELECT * FROM hr_tasks WHERE Deadline='$noteWeekDay' LIMIT 1";
                                $que = $conn -> query($sql);
                                while($res = mysqli_fetch_array($que)){
                                    $hr_task_id = $res["ID"];
                                    $hr_task_whoadd = $res["WhoAdd"];
                                    $hr_task_adddate = $res["AddDate"];
                                    $hr_task_deadline = $res["Deadline"];
                                    $hr_task_info = $res["Info"];
                                    $hr_task_infoadd = $res["InfoAdd"];
                                    $hr_task_completed = $res["Completed"];
                                    $hr_task_whocompleted = $res["WhoCompleted"];
                                    $hr_task_completeddate = $res["CompletedDate"];
                                    
                                    echo '      <div style="color: #006699; font-style: italic; margin-left: 20px;">RANO <span style="color:black; font-size:60%; font-style: italic; margin-left: 10px;">Ostatnia edycja: '.name_by_id($hr_task_whoadd).'</span></div>';
                                    echo "      <div class='task_job' id='$hr_task_id'><textarea data-autoresize placeholder='Notatka dnia rano...' class='task_job_textarea' id='textarea_$hr_task_id' style='margin-top:-10px; width:100%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false' onchange='hr_tasks_updatenote($hr_task_id)'>$hr_task_info</textarea></div>";
                                    echo '      <div style="color: #006699; font-style: italic; margin-left: 20px;">POPO <span style="color:black; font-size:60%; font-style: italic; margin-left: 10px;">Ostatnia edycja: '.name_by_id($hr_task_whocompleted).'</span></div>';
                                    echo "      <div class='task_job' id='$hr_task_id'><textarea data-autoresize placeholder='Notatka dnia popołudnie...' class='task_job_textarea' id='textarea_add_$hr_task_id' style='margin-top:-10px; width:100%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false' onchange='hr_tasks_updatenoteadd($hr_task_id)'>$hr_task_infoadd</textarea></div>";
                                }
                                /// ==========

                                /// WYPISANIE ZADAŃ DLA DNIA
                                $sql = "SELECT * FROM hr_tasks WHERE Deadline='$weekDay'";
                                $que = $conn -> query($sql);
                                if(mysqli_num_rows($que)!=0){
                                    echo '     <div style="color: #006699; font-style: italic; margin-left: 20px;">ZADANIA</div>';
                                }
                                while($res = mysqli_fetch_array($que)){
                                    $hr_task_id = $res["ID"];
                                    $hr_task_whoadd = $res["WhoAdd"];
                                    $hr_task_adddate = $res["AddDate"];
                                    $hr_task_deadline = $res["Deadline"];
                                    $hr_task_info = $res["Info"];
                                    $hr_task_infoadd = $res["InfoAdd"];
                                    $hr_task_completed = $res["Completed"];
                                    $hr_task_whocompleted = $res["WhoCompleted"];
                                    $hr_task_completeddate = $res["CompletedDate"];

                                    $string = $hr_task_info;
                                    $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
                                    $string = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $string);

                                    echo '  <div class="hr_task_title" id="hr_task_title_'.$hr_task_id.'" onclick="hr_tasks_toggleDiv('.$hr_task_id.')">'.$string.'</div>';
                                    echo '      <div class="hr_task_body" id="'.$hr_task_id.'">
                                                    <div><div style="float:left; width:80%;"><img style="float:left; height:15px; margin-left:10%; margin-right:20%;" src="icons/add.png" /><div style="float:left; color:white; height:15px; width:30%; text-align:center">'.name_by_id($hr_task_whoadd).'</div><div style="float:left; color:white; height:15px; width:30%; text-align:center">'.proper_date($hr_task_adddate).'</div></div><button class="ri_job_end_button" id="button_delete_'.$hr_task_id.'" style="background-color:#006699;" onclick="hr_tasks_delete('.$hr_task_id.')">x</button><button class="ri_job_complete_button" id="button_check_'.$hr_task_id.'" style="background-color:#006699; color:#00FF00;" onclick="hr_tasks_check('.$hr_task_id.')">✓</button></div>';
                                    if($hr_task_completed == "true"){
                                        echo '          <div><div style="float:left; width:80%;"><img style="float:left; height:15px; margin-left:10%; margin-right:20%;" src="icons/done.png" /><div style="float:left; color:white; height:15px; width:30%; text-align:center">'.name_by_id($hr_task_whocompleted).'</div><div style="float:left; color:white; height:15px; width:30%; text-align:center">'.proper_date($hr_task_completeddate).'</div></div></div>';
                                        echo "          <script>
                                                            $('#$hr_task_id').css('background-color', 'green');
                                                            $('#hr_task_title_$hr_task_id').css('background-color', 'green');
                                                            $('#button_check_$hr_task_id').css('color', 'gray');
                                                            $('#button_check_$hr_task_id').css('background-color', 'green');
                                                            $('#button_delete_$hr_task_id').css('display', 'none');
                                                        </script>";
                                    }
                                    else{
                                        echo '          <div><div style="float:left; width:80%;"><img style="float:left; height:15px; margin-left:10%; margin-right:20%;" src="icons/done.png" /><div style="float:left; color:white; height:15px; width:30%; text-align:center;">-</div><div style="float:left; color:white; height:15px; width:30%; text-align:center">-</div></div></div>';
                                        echo "          <script>
                                                            $('#$hr_task_id').css('background-color', '#006699');
                                                            $('#hr_task_title_$hr_task_id').css('background-color', '#006699');
                                                            $('#button_check_$hr_task_id').css('color', '#00FF00');
                                                            $('#button_check_$hr_task_id').css('background-color', '#006699');
                                                            $('#button_delete_$hr_task_id').css('display', 'inline');
                                                        </script>";
                                    }
                                    echo "          <div style='clear:both;'></div>";
                                    echo "          <div class='task_job' id='$hr_task_id' style='margin-top:10px;'><textarea data-autoresize placeholder='Notatka do zadania...' class='hr_task_textarea' id='textarea_hrtask_$hr_task_id' rows='1' spellcheck='false' onchange='hr_tasks_updatenotetask($hr_task_id)'>$hr_task_infoadd</textarea></div>";
                                    echo "          <div style='clear:both;'></div>";
                                    echo '      </div>';
                                }
                                /// ==========
                                echo '  </div>';
                            }
                        }

                        echo '      </div>
                                </div>';
                    }
                    $conn -> close();
                    /// ==========

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
        document.getElementById("okno_background").style.display="none";
        document.getElementById("nav_background").style.display="none";
        document.getElementById("task_background").style.display="none";
        var task_old_info = "";

        // Skrypty nav
        var okno=0;
        function nav_hidenot(){
            okno=1;
        }

        // ZMIENIONE!
        function nav_hide(){
            if(okno==0){
                var navback = document.getElementById("nav_background");
                navback.style.display="none";
                document.body.style.overflowY="auto";

                $("#hr_list").css('transform', 'rotateX(180deg)');
                $("#hr_list").css('-ms-transform', 'rotateX(180deg)');
                $("#hr_list").css('-webkit-transform', 'rotateX(180deg)');
                $(".hr_week").css('transform', 'rotateX(180deg)');
                $(".hr_week").css('-ms-transform', 'rotateX(180deg)');
                $(".hr_week").css('-webkit-transform', 'rotateX(180deg)');
                $(".hr_week").css('vertical-align', 'bottom');
            }
            okno=0;
        }

        // ZMIENIONE!
        function nav_open(){
            var navback = document.getElementById("nav_background");
            navback.style.display="inline";
            document.body.style.overflowY="hidden";

            $("#hr_list").css('transform', 'none');
            $("#hr_list").css('-ms-transform', 'none');
            $("#hr_list").css('-webkit-transform', 'none');
            $(".hr_week").css('transform', 'none');
            $(".hr_week").css('-ms-transform', 'none');
            $(".hr_week").css('-webkit-transform', 'none');
            $(".hr_week").css('vertical-align', 'top');
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

        // ZMIENIONE!
        function task_hide(){
            if(okno==0){
                var taskback = document.getElementById("task_background");
                taskback.style.display="none";
                document.body.style.overflowY="auto";

                $("#hr_list").css('transform', 'rotateX(180deg)');
                $("#hr_list").css('-ms-transform', 'rotateX(180deg)');
                $("#hr_list").css('-webkit-transform', 'rotateX(180deg)');
                $(".hr_week").css('transform', 'rotateX(180deg)');
                $(".hr_week").css('-ms-transform', 'rotateX(180deg)');
                $(".hr_week").css('-webkit-transform', 'rotateX(180deg)');
                $(".hr_week").css('vertical-align', 'bottom');
            }
            okno=0;
        }

        // ZMIENIONE!
        function task_open(){
            var taskback = document.getElementById("task_background");
            taskback.style.display="inline";
            document.body.style.overflowY="hidden";

            $("#hr_list").css('transform', 'none');
            $("#hr_list").css('-ms-transform', 'none');
            $("#hr_list").css('-webkit-transform', 'none');
            $(".hr_week").css('transform', 'none');
            $(".hr_week").css('-ms-transform', 'none');
            $(".hr_week").css('-webkit-transform', 'none');
            $(".hr_week").css('vertical-align', 'top');

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

        /// SKRYPTY ZADAŃ KADROWYCH

        var okno=0;

        $('textarea').blur(); //DZIĘKI TEMU TEXTAREA SIĘ NAPRAWIĄ

        $(".hr_list_2020").toggle(); // UKRYJ STARY ROK

        // OBSŁUGA ENTERÓW W TEXTAREA
        var shiftDown = false;
        $(window).keydown(function(evt) {
        if (evt.which == 16) { // shift
            shiftDown = true;
        }
        }).keyup(function(evt) {
        if (evt.which == 16) { // shift
            shiftDown = false;
        }
        });
        $('textarea').keypress(
        function(e){
            if (e.keyCode == 13 && shiftDown == false) {
                if ($(this).index() == 0) {
                    $(this).blur();
                }
            }
        });

        // DZIĘKI TEMU PRZESUWA SIĘ SKRYPT DO BIEŻĄCEGO TYGODNIA
        $(document).ready(function() {
            $(".hr_list_2021").scrollLeft($(<?php echo '"#week_'.intval(date("W")).'"' ?>).offset().left - 20);
        });


        function hr_tasks_toggleDiv(task_id){
            $("#"+task_id).toggle();
            $("#textarea_hrtask_"+task_id).blur();
            if($("#"+task_id).css("display") == "none"){
                $("#hr_task_title_"+task_id).css("border-bottom-left-radius", "20px");
                $("#hr_task_title_"+task_id).css("border-bottom-right-radius", "20px");
            }
            else{
                $("#hr_task_title_"+task_id).css("border-bottom-left-radius", "0");
                $("#hr_task_title_"+task_id).css("border-bottom-right-radius", "0");
            }
            
        }

        function hr_tasks_hidenot(){
            okno=1;
        }

        // ZMIENIONE!
        function hr_tasks_hide(){
            if(okno==0){
                $("#okno_background").css("display", "none");
                $("#hr_list").css("overflowX", "auto");
                document.body.style.overflowY="auto";

                $("#hr_list").css('transform', 'rotateX(180deg)');
                $("#hr_list").css('-ms-transform', 'rotateX(180deg)');
                $("#hr_list").css('-webkit-transform', 'rotateX(180deg)');
                $(".hr_week").css('transform', 'rotateX(180deg)');
                $(".hr_week").css('-ms-transform', 'rotateX(180deg)');
                $(".hr_week").css('-webkit-transform', 'rotateX(180deg)');
                $(".hr_week").css('vertical-align', 'bottom');
            }
            okno=0;
        }

        // ZMIENIONE!
        function hr_tasks_open(){
            $("#okno_background").css("display", "inline");
            $("#hr_list").css("overflowX", "hidden");
            document.body.style.overflowY="hidden";

            $("#hr_list").css('transform', 'none');
            $("#hr_list").css('-ms-transform', 'none');
            $("#hr_list").css('-webkit-transform', 'none');
            $(".hr_week").css('transform', 'none');
            $(".hr_week").css('-ms-transform', 'none');
            $(".hr_week").css('-webkit-transform', 'none');
            $(".hr_week").css('vertical-align', 'top');

            $('textarea').blur();
        }

        function hr_tasks_weekFocus(){
            $(".hr_list_2021").scrollLeft($(<?php echo '"#week_'.intval(date("W")).'"' ?>).offset().left - 20);
        }

        function hr_tasks_reload(){
            location.reload(true);
        }

        function hr_tasks_check(task_id){
            $.ajax({
                url: "additional/hr_task_processor.php?check=1&task_number="+task_id
            }).done(function(data) { // data what is sent back by the php page
                $('#thrash').html(data); // display data
            });
            // ZMIANY KOLORÓW SĄ POD PĘTLĄ WYPISANIA ZADAŃ
        }

        function hr_tasks_delete(task_id){
            var confirm_prompt = confirm("Czy na pewno chcesz usunąć te zadanie na zawsze?");

            if(confirm_prompt == true){
                $.ajax({
                    url: "additional/hr_task_processor.php?delete=1&task_number="+task_id
                }).done(function(data) { // data what is sent back by the php page
                    $('#thrash').html(data); // display data
                });

                //hr_tasks_reload();
            }
        }

        function hr_tasks_updatenote(task_id){
            var new_task = document.getElementById("textarea_"+task_id).value;
            new_task = new_task.replace(/\n\r?/g, '\\n');

            $.ajax({
                url: "additional/hr_task_processor.php?update=1&task_number="+task_id+"&new="+new_task
            }).done(function(data) { // data what is sent back by the php page
                $('#thrash').html(data); // display data
            });
        }

        function hr_tasks_updatenoteadd(task_id){
            var new_task = document.getElementById("textarea_add_"+task_id).value;
            new_task = new_task.replace(/\n\r?/g, '\\n');

            $.ajax({
                url: "additional/hr_task_processor.php?update=2&task_number="+task_id+"&new="+new_task
            }).done(function(data) { // data what is sent back by the php page
                $('#thrash').html(data); // display data
            });
        }

        function hr_tasks_updatenotetask(task_id){
            var new_task = document.getElementById("textarea_hrtask_"+task_id).value;
            new_task = new_task.replace(/\n\r?/g, '\\n');

            $.ajax({
                url: "additional/hr_task_processor.php?update=3&task_number="+task_id+"&new="+new_task
            }).done(function(data) { // data what is sent back by the php page
                $('#thrash').html(data); // display data
            });
        }

        function hideHrList(year){
            $(".hr_list_"+year).toggle();
        }

        /// ==========
    </script>
</html>