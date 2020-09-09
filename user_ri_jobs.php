<?php
session_start();

require_once('additional/func.php');
require_once('additional/navbar.php');
require_once('additional/taskbar.php');
require_once('additional/footer.php');
require_once('additional/weather.php');

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

if(!isset($_SESSION["ri_forwho"])){
    header("location:user_ri.php");
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
        <title>Panel Zadań RI</title>
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

        <div class="ri">
            <?php
                $ri_user_forwho = $_SESSION["ri_forwho"];
                $ri_user_coach = "";
                $ri_user_month = date("ym");
                $ri_user_todo_month = "0";
                $ri_user_percentage = 0;
                $conn = connect();

                $sql = "SELECT ID FROM job_ri WHERE ForWho='$ri_user_forwho' AND Month='$ri_user_month' AND Completed='true'";
                $que = $conn -> query($sql);
                $ri_user_month_true = mysqli_num_rows($que);

                $sql = "SELECT ID FROM job_ri WHERE ForWho='$ri_user_forwho' AND Month='$ri_user_month' AND Completed='false'";
                $que = $conn -> query($sql);
                $ri_user_month_false = mysqli_num_rows($que);
                $ri_user_todo_month = $ri_user_month_false;

                if(($ri_user_month_true + $ri_user_month_false) > 0){
                    $ri_user_percentage = $ri_user_month_true / ($ri_user_month_true + $ri_user_month_false) * 100;
                    $ri_user_percentage = round($ri_user_percentage, 0);
                }
                else{
                    $ri_user_percentage = 100;
                }

                $sql = "SELECT RI FROM users WHERE ID='$ri_user_forwho'";
                $que = $conn -> query($sql);
                while($res = mysqli_fetch_array($que)){
                    $ri_user_coach = $res["RI"];
                }

                echo '
                <div class="ri_information_div"><h2><span>PRACOWNIK:</span><br>'.name_by_id($ri_user_forwho).'</h2></div> 
                <div class="ri_information_div"><h2><span>COACH:</span><br>'.name_by_id($ri_user_coach).'</h2></div>
                <div class="ri_information_div"><h2><span>DO WYKONANIA W TYM MIESIĄCU:</span><br>'.$ri_user_todo_month.'</h2></div>
                <div class="ri_information_div"><h2><span>UKOŃCZENIE MIESIĄCA:</span><br><span id="ri_percentage_span">'.$ri_user_percentage.' %</span></h2></div>
                ';

                if($ri_user_percentage<50){
                    echo '
                    <script>
                    document.getElementById("ri_percentage_span").style.color= "red";
                    </script>
                    ';
                }
                else if($ri_user_percentage<100){
                    echo '
                    <script>
                    document.getElementById("ri_percentage_span").style.color= "#ffbf00";
                    </script>
                    ';
                }
                else{
                    echo '
                    <script>
                    document.getElementById("ri_percentage_span").style.color= "#00af00";
                    </script>
                    ';
                }

                echo '<div style="clear:both;"></div>';

                $conn -> close();
            ?>
            <h2 style="font-size:200%;">PLAN ZADAŃ RI 2020 - 2021</h2>
            <div id="ri_job_list">
                <div class="ri_job_month">
                    <h2>MARZEC<span id="ri_add" onclick="ri_add('2003')">+</span></h2>
                    <div class="ri_job_list" id="2003">
                        <?php 
                            $ri_user_month = "2003";
                            $ri_user_forwho = $_SESSION["ri_forwho"];
                            $conn = connect();

                            $conn -> query("SET CHARSET utf8");
                            $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                            $sql = "SELECT Identificator, Info, Completed FROM job_ri WHERE Month='$ri_user_month' AND ForWho='$ri_user_forwho'";
                            $que = $conn -> query($sql);
                            while($res = mysqli_fetch_array($que)){
                                $ri_user_identificator = $res["Identificator"];
                                $ri_user_info = $res["Info"];
                                $ri_user_completed = $res["Completed"];

                                echo "<div class='task_job' id='$ri_user_identificator' onclick='ri_getinfo(\"$ri_user_identificator\")' onchange='ri_change(\"$ri_user_identificator\", \"$ri_user_month\")'><textarea data-autoresize class='task_job_textarea' id='textarea_$ri_user_identificator' style='width:80%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false'>$ri_user_info</textarea><button class='ri_job_end_button' id='butt_$ri_user_identificator' onclick='ri_done(\"$ri_user_identificator\")'>x</button><button class='ri_job_complete_button' id='check_$ri_user_identificator' onclick='ri_check(\"$ri_user_identificator\")'>✓</button></div>";

                                if($ri_user_completed == "true"){
                                    echo '
                                    <script>
                                    document.getElementById("'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.color = "gray";
                                    document.getElementById("butt_'.$ri_user_identificator.'").style.display = "none";
                                    document.getElementById("textarea_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    </script>
                                    ';
                                }
                            }

                            $conn -> close();
                        ?>
                    </div>
                </div>
                <div class="ri_job_month">
                    <h2>KWIECIEŃ<span id="ri_add" onclick="ri_add('2004')">+</span></h2>
                    <div class="ri_job_list" id="2004">
                        <?php 
                            $ri_user_month = "2004";
                            $ri_user_forwho = $_SESSION["ri_forwho"];
                            $conn = connect();

                            $conn -> query("SET CHARSET utf8");
                            $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                            $sql = "SELECT Identificator, Info, Completed FROM job_ri WHERE Month='$ri_user_month' AND ForWho='$ri_user_forwho'";
                            $que = $conn -> query($sql);
                            while($res = mysqli_fetch_array($que)){
                                $ri_user_identificator = $res["Identificator"];
                                $ri_user_info = $res["Info"];
                                $ri_user_completed = $res["Completed"];

                                echo "<div class='task_job' id='$ri_user_identificator' onclick='ri_getinfo(\"$ri_user_identificator\")' onchange='ri_change(\"$ri_user_identificator\", \"$ri_user_month\")'><textarea data-autoresize class='task_job_textarea' id='textarea_$ri_user_identificator' style='width:80%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false'>$ri_user_info</textarea><button class='ri_job_end_button' id='butt_$ri_user_identificator' onclick='ri_done(\"$ri_user_identificator\")'>x</button><button class='ri_job_complete_button' id='check_$ri_user_identificator' onclick='ri_check(\"$ri_user_identificator\")'>✓</button></div>";

                                if($ri_user_completed == "true"){
                                    echo '
                                    <script>
                                    document.getElementById("'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.color = "gray";
                                    document.getElementById("butt_'.$ri_user_identificator.'").style.display = "none";
                                    document.getElementById("textarea_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    </script>
                                    ';
                                }
                            }

                            $conn -> close();
                        ?>
                    </div>
                </div>
                <div class="ri_job_month">
                    <h2>MAJ<span id="ri_add" onclick="ri_add('2005')">+</span></h2>
                    <div class="ri_job_list" id="2005">
                        <?php 
                            $ri_user_month = "2005";
                            $ri_user_forwho = $_SESSION["ri_forwho"];
                            $conn = connect();

                            $conn -> query("SET CHARSET utf8");
                            $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                            $sql = "SELECT Identificator, Info, Completed FROM job_ri WHERE Month='$ri_user_month' AND ForWho='$ri_user_forwho'";
                            $que = $conn -> query($sql);
                            while($res = mysqli_fetch_array($que)){
                                $ri_user_identificator = $res["Identificator"];
                                $ri_user_info = $res["Info"];
                                $ri_user_completed = $res["Completed"];

                                echo "<div class='task_job' id='$ri_user_identificator' onclick='ri_getinfo(\"$ri_user_identificator\")' onchange='ri_change(\"$ri_user_identificator\", \"$ri_user_month\")'><textarea data-autoresize class='task_job_textarea' id='textarea_$ri_user_identificator' style='width:80%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false'>$ri_user_info</textarea><button class='ri_job_end_button' id='butt_$ri_user_identificator' onclick='ri_done(\"$ri_user_identificator\")'>x</button><button class='ri_job_complete_button' id='check_$ri_user_identificator' onclick='ri_check(\"$ri_user_identificator\")'>✓</button></div>";

                                if($ri_user_completed == "true"){
                                    echo '
                                    <script>
                                    document.getElementById("'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.color = "gray";
                                    document.getElementById("butt_'.$ri_user_identificator.'").style.display = "none";
                                    document.getElementById("textarea_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    </script>
                                    ';
                                }
                            }

                            $conn -> close();
                        ?>
                    </div>
                </div>
                <div class="ri_job_month">
                    <h2>CZERWIEC<span id="ri_add" onclick="ri_add('2006')">+</span></h2>
                    <div class="ri_job_list" id="2006">
                        <?php 
                            $ri_user_month = "2006";
                            $ri_user_forwho = $_SESSION["ri_forwho"];
                            $conn = connect();

                            $conn -> query("SET CHARSET utf8");
                            $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                            $sql = "SELECT Identificator, Info, Completed FROM job_ri WHERE Month='$ri_user_month' AND ForWho='$ri_user_forwho'";
                            $que = $conn -> query($sql);
                            while($res = mysqli_fetch_array($que)){
                                $ri_user_identificator = $res["Identificator"];
                                $ri_user_info = $res["Info"];
                                $ri_user_completed = $res["Completed"];

                                echo "<div class='task_job' id='$ri_user_identificator' onclick='ri_getinfo(\"$ri_user_identificator\")' onchange='ri_change(\"$ri_user_identificator\", \"$ri_user_month\")'><textarea data-autoresize class='task_job_textarea' id='textarea_$ri_user_identificator' style='width:80%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false'>$ri_user_info</textarea><button class='ri_job_end_button' id='butt_$ri_user_identificator' onclick='ri_done(\"$ri_user_identificator\")'>x</button><button class='ri_job_complete_button' id='check_$ri_user_identificator' onclick='ri_check(\"$ri_user_identificator\")'>✓</button></div>";

                                if($ri_user_completed == "true"){
                                    echo '
                                    <script>
                                    document.getElementById("'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.color = "gray";
                                    document.getElementById("butt_'.$ri_user_identificator.'").style.display = "none";
                                    document.getElementById("textarea_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    </script>
                                    ';
                                }
                            }

                            $conn -> close();
                        ?>
                    </div>
                </div>
                <div class="ri_job_month">
                    <h2>LIPIEC<span id="ri_add" onclick="ri_add('2007')">+</span></h2>
                    <div class="ri_job_list" id="2007">
                        <?php 
                            $ri_user_month = "2007";
                            $ri_user_forwho = $_SESSION["ri_forwho"];
                            $conn = connect();

                            $conn -> query("SET CHARSET utf8");
                            $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                            $sql = "SELECT Identificator, Info, Completed FROM job_ri WHERE Month='$ri_user_month' AND ForWho='$ri_user_forwho'";
                            $que = $conn -> query($sql);
                            while($res = mysqli_fetch_array($que)){
                                $ri_user_identificator = $res["Identificator"];
                                $ri_user_info = $res["Info"];
                                $ri_user_completed = $res["Completed"];

                                echo "<div class='task_job' id='$ri_user_identificator' onclick='ri_getinfo(\"$ri_user_identificator\")' onchange='ri_change(\"$ri_user_identificator\", \"$ri_user_month\")'><textarea data-autoresize class='task_job_textarea' id='textarea_$ri_user_identificator' style='width:80%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false'>$ri_user_info</textarea><button class='ri_job_end_button' id='butt_$ri_user_identificator' onclick='ri_done(\"$ri_user_identificator\")'>x</button><button class='ri_job_complete_button' id='check_$ri_user_identificator' onclick='ri_check(\"$ri_user_identificator\")'>✓</button></div>";

                                if($ri_user_completed == "true"){
                                    echo '
                                    <script>
                                    document.getElementById("'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.color = "gray";
                                    document.getElementById("butt_'.$ri_user_identificator.'").style.display = "none";
                                    document.getElementById("textarea_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    </script>
                                    ';
                                }
                            }

                            $conn -> close();
                        ?>
                    </div>
                </div>
                <div class="ri_job_month">
                    <h2>SIERPIEŃ<span id="ri_add" onclick="ri_add('2008')">+</span></h2>
                    <div class="ri_job_list" id="2008">
                        <?php 
                            $ri_user_month = "2008";
                            $ri_user_forwho = $_SESSION["ri_forwho"];
                            $conn = connect();

                            $conn -> query("SET CHARSET utf8");
                            $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                            $sql = "SELECT Identificator, Info, Completed FROM job_ri WHERE Month='$ri_user_month' AND ForWho='$ri_user_forwho'";
                            $que = $conn -> query($sql);
                            while($res = mysqli_fetch_array($que)){
                                $ri_user_identificator = $res["Identificator"];
                                $ri_user_info = $res["Info"];
                                $ri_user_completed = $res["Completed"];

                                echo "<div class='task_job' id='$ri_user_identificator' onclick='ri_getinfo(\"$ri_user_identificator\")' onchange='ri_change(\"$ri_user_identificator\", \"$ri_user_month\")'><textarea data-autoresize class='task_job_textarea' id='textarea_$ri_user_identificator' style='width:80%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false'>$ri_user_info</textarea><button class='ri_job_end_button' id='butt_$ri_user_identificator' onclick='ri_done(\"$ri_user_identificator\")'>x</button><button class='ri_job_complete_button' id='check_$ri_user_identificator' onclick='ri_check(\"$ri_user_identificator\")'>✓</button></div>";

                                if($ri_user_completed == "true"){
                                    echo '
                                    <script>
                                    document.getElementById("'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.color = "gray";
                                    document.getElementById("butt_'.$ri_user_identificator.'").style.display = "none";
                                    document.getElementById("textarea_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    </script>
                                    ';
                                }
                            }

                            $conn -> close();
                        ?>
                    </div>
                </div>
                <div class="ri_job_month">
                    <h2>WRZESIEŃ<span id="ri_add" onclick="ri_add('2009')">+</span></h2>
                    <div class="ri_job_list" id="2009">
                        <?php 
                            $ri_user_month = "2009";
                            $ri_user_forwho = $_SESSION["ri_forwho"];
                            $conn = connect();

                            $conn -> query("SET CHARSET utf8");
                            $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                            $sql = "SELECT Identificator, Info, Completed FROM job_ri WHERE Month='$ri_user_month' AND ForWho='$ri_user_forwho'";
                            $que = $conn -> query($sql);
                            while($res = mysqli_fetch_array($que)){
                                $ri_user_identificator = $res["Identificator"];
                                $ri_user_info = $res["Info"];
                                $ri_user_completed = $res["Completed"];

                                echo "<div class='task_job' id='$ri_user_identificator' onclick='ri_getinfo(\"$ri_user_identificator\")' onchange='ri_change(\"$ri_user_identificator\", \"$ri_user_month\")'><textarea data-autoresize class='task_job_textarea' id='textarea_$ri_user_identificator' style='width:80%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false'>$ri_user_info</textarea><button class='ri_job_end_button' id='butt_$ri_user_identificator' onclick='ri_done(\"$ri_user_identificator\")'>x</button><button class='ri_job_complete_button' id='check_$ri_user_identificator' onclick='ri_check(\"$ri_user_identificator\")'>✓</button></div>";

                                if($ri_user_completed == "true"){
                                    echo '
                                    <script>
                                    document.getElementById("'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.color = "gray";
                                    document.getElementById("butt_'.$ri_user_identificator.'").style.display = "none";
                                    document.getElementById("textarea_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    </script>
                                    ';
                                }
                            }

                            $conn -> close();
                        ?>
                    </div>
                </div>
                <div class="ri_job_month">
                    <h2>PAŹDZIERNIK<span id="ri_add" onclick="ri_add('2010')">+</span></h2>
                    <div class="ri_job_list" id="2010">
                        <?php 
                            $ri_user_month = "2010";
                            $ri_user_forwho = $_SESSION["ri_forwho"];
                            $conn = connect();

                            $conn -> query("SET CHARSET utf8");
                            $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                            $sql = "SELECT Identificator, Info, Completed FROM job_ri WHERE Month='$ri_user_month' AND ForWho='$ri_user_forwho'";
                            $que = $conn -> query($sql);
                            while($res = mysqli_fetch_array($que)){
                                $ri_user_identificator = $res["Identificator"];
                                $ri_user_info = $res["Info"];
                                $ri_user_completed = $res["Completed"];

                                echo "<div class='task_job' id='$ri_user_identificator' onclick='ri_getinfo(\"$ri_user_identificator\")' onchange='ri_change(\"$ri_user_identificator\", \"$ri_user_month\")'><textarea data-autoresize class='task_job_textarea' id='textarea_$ri_user_identificator' style='width:80%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false'>$ri_user_info</textarea><button class='ri_job_end_button' id='butt_$ri_user_identificator' onclick='ri_done(\"$ri_user_identificator\")'>x</button><button class='ri_job_complete_button' id='check_$ri_user_identificator' onclick='ri_check(\"$ri_user_identificator\")'>✓</button></div>";

                                if($ri_user_completed == "true"){
                                    echo '
                                    <script>
                                    document.getElementById("'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.color = "gray";
                                    document.getElementById("butt_'.$ri_user_identificator.'").style.display = "none";
                                    document.getElementById("textarea_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    </script>
                                    ';
                                }
                            }

                            $conn -> close();
                        ?>
                    </div>
                </div>
                <div class="ri_job_month">
                    <h2>LISTOPAD<span id="ri_add" onclick="ri_add('2011')">+</span></h2>
                    <div class="ri_job_list" id="2011">
                        <?php 
                            $ri_user_month = "2011";
                            $ri_user_forwho = $_SESSION["ri_forwho"];
                            $conn = connect();

                            $conn -> query("SET CHARSET utf8");
                            $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                            $sql = "SELECT Identificator, Info, Completed FROM job_ri WHERE Month='$ri_user_month' AND ForWho='$ri_user_forwho'";
                            $que = $conn -> query($sql);
                            while($res = mysqli_fetch_array($que)){
                                $ri_user_identificator = $res["Identificator"];
                                $ri_user_info = $res["Info"];
                                $ri_user_completed = $res["Completed"];

                                echo "<div class='task_job' id='$ri_user_identificator' onclick='ri_getinfo(\"$ri_user_identificator\")' onchange='ri_change(\"$ri_user_identificator\", \"$ri_user_month\")'><textarea data-autoresize class='task_job_textarea' id='textarea_$ri_user_identificator' style='width:80%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false'>$ri_user_info</textarea><button class='ri_job_end_button' id='butt_$ri_user_identificator' onclick='ri_done(\"$ri_user_identificator\")'>x</button><button class='ri_job_complete_button' id='check_$ri_user_identificator' onclick='ri_check(\"$ri_user_identificator\")'>✓</button></div>";

                                if($ri_user_completed == "true"){
                                    echo '
                                    <script>
                                    document.getElementById("'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.color = "gray";
                                    document.getElementById("butt_'.$ri_user_identificator.'").style.display = "none";
                                    document.getElementById("textarea_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    </script>
                                    ';
                                }
                            }

                            $conn -> close();
                        ?>
                    </div>
                </div>
                <div class="ri_job_month">
                    <h2>GRUDZIEŃ<span id="ri_add" onclick="ri_add('2012')">+</span></h2>
                    <div class="ri_job_list" id="2012">
                        <?php 
                            $ri_user_month = "2012";
                            $ri_user_forwho = $_SESSION["ri_forwho"];
                            $conn = connect();

                            $conn -> query("SET CHARSET utf8");
                            $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                            $sql = "SELECT Identificator, Info, Completed FROM job_ri WHERE Month='$ri_user_month' AND ForWho='$ri_user_forwho'";
                            $que = $conn -> query($sql);
                            while($res = mysqli_fetch_array($que)){
                                $ri_user_identificator = $res["Identificator"];
                                $ri_user_info = $res["Info"];
                                $ri_user_completed = $res["Completed"];

                                echo "<div class='task_job' id='$ri_user_identificator' onclick='ri_getinfo(\"$ri_user_identificator\")' onchange='ri_change(\"$ri_user_identificator\", \"$ri_user_month\")'><textarea data-autoresize class='task_job_textarea' id='textarea_$ri_user_identificator' style='width:80%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false'>$ri_user_info</textarea><button class='ri_job_end_button' id='butt_$ri_user_identificator' onclick='ri_done(\"$ri_user_identificator\")'>x</button><button class='ri_job_complete_button' id='check_$ri_user_identificator' onclick='ri_check(\"$ri_user_identificator\")'>✓</button></div>";

                                if($ri_user_completed == "true"){
                                    echo '
                                    <script>
                                    document.getElementById("'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.color = "gray";
                                    document.getElementById("butt_'.$ri_user_identificator.'").style.display = "none";
                                    document.getElementById("textarea_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    </script>
                                    ';
                                }
                            }

                            $conn -> close();
                        ?>
                    </div>
                </div>
                <div class="ri_job_month">
                    <h2>STYCZEŃ<span id="ri_add" onclick="ri_add('2101')">+</span></h2>
                    <div class="ri_job_list" id="2101">
                        <?php 
                            $ri_user_month = "2101";
                            $ri_user_forwho = $_SESSION["ri_forwho"];
                            $conn = connect();

                            $conn -> query("SET CHARSET utf8");
                            $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                            $sql = "SELECT Identificator, Info, Completed FROM job_ri WHERE Month='$ri_user_month' AND ForWho='$ri_user_forwho'";
                            $que = $conn -> query($sql);
                            while($res = mysqli_fetch_array($que)){
                                $ri_user_identificator = $res["Identificator"];
                                $ri_user_info = $res["Info"];
                                $ri_user_completed = $res["Completed"];

                                echo "<div class='task_job' id='$ri_user_identificator' onclick='ri_getinfo(\"$ri_user_identificator\")' onchange='ri_change(\"$ri_user_identificator\", \"$ri_user_month\")'><textarea data-autoresize class='task_job_textarea' id='textarea_$ri_user_identificator' style='width:80%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false'>$ri_user_info</textarea><button class='ri_job_end_button' id='butt_$ri_user_identificator' onclick='ri_done(\"$ri_user_identificator\")'>x</button><button class='ri_job_complete_button' id='check_$ri_user_identificator' onclick='ri_check(\"$ri_user_identificator\")'>✓</button></div>";

                                if($ri_user_completed == "true"){
                                    echo '
                                    <script>
                                    document.getElementById("'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.color = "gray";
                                    document.getElementById("butt_'.$ri_user_identificator.'").style.display = "none";
                                    document.getElementById("textarea_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    </script>
                                    ';
                                }
                            }

                            $conn -> close();
                        ?>
                    </div>
                </div>
                <div class="ri_job_month">
                    <h2>LUTY<span id="ri_add" onclick="ri_add('2102')">+</span></h2>
                    <div class="ri_job_list" id="2102">
                        <?php 
                            $ri_user_month = "2102";
                            $ri_user_forwho = $_SESSION["ri_forwho"];
                            $conn = connect();

                            $conn -> query("SET CHARSET utf8");
                            $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                            $sql = "SELECT Identificator, Info, Completed FROM job_ri WHERE Month='$ri_user_month' AND ForWho='$ri_user_forwho'";
                            $que = $conn -> query($sql);
                            while($res = mysqli_fetch_array($que)){
                                $ri_user_identificator = $res["Identificator"];
                                $ri_user_info = $res["Info"];
                                $ri_user_completed = $res["Completed"];

                                echo "<div class='task_job' id='$ri_user_identificator' onclick='ri_getinfo(\"$ri_user_identificator\")' onchange='ri_change(\"$ri_user_identificator\", \"$ri_user_month\")'><textarea data-autoresize class='task_job_textarea' id='textarea_$ri_user_identificator' style='width:80%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false'>$ri_user_info</textarea><button class='ri_job_end_button' id='butt_$ri_user_identificator' onclick='ri_done(\"$ri_user_identificator\")'>x</button><button class='ri_job_complete_button' id='check_$ri_user_identificator' onclick='ri_check(\"$ri_user_identificator\")'>✓</button></div>";

                                if($ri_user_completed == "true"){
                                    echo '
                                    <script>
                                    document.getElementById("'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    document.getElementById("check_'.$ri_user_identificator.'").style.color = "gray";
                                    document.getElementById("butt_'.$ri_user_identificator.'").style.display = "none";
                                    document.getElementById("textarea_'.$ri_user_identificator.'").style.backgroundColor = "#ccffcc";
                                    </script>
                                    ';
                                }
                            }

                            $conn -> close();
                        ?>
                    </div>
                </div>
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
        var ri_old_info = "";

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

                $("#ri_job_list").css('transform', 'rotateX(180deg)');
                $("#ri_job_list").css('-ms-transform', 'rotateX(180deg)');
                $("#ri_job_list").css('-webkit-transform', 'rotateX(180deg)');
                $(".ri_job_month").css('transform', 'rotateX(180deg)');
                $(".ri_job_month").css('-ms-transform', 'rotateX(180deg)');
                $(".ri_job_month").css('-webkit-transform', 'rotateX(180deg)');
                $(".ri_job_month").css('vertical-align', 'bottom');
            }
            okno=0;
        }

        // ZMIENIONE!
        function nav_open(){
            var navback = document.getElementById("nav_background");
            navback.style.display="inline";
            document.body.style.overflowY="hidden";

            $("#ri_job_list").css('transform', 'none');
            $("#ri_job_list").css('-ms-transform', 'none');
            $("#ri_job_list").css('-webkit-transform', 'none');
            $(".ri_job_month").css('transform', 'none');
            $(".ri_job_month").css('-ms-transform', 'none');
            $(".ri_job_month").css('-webkit-transform', 'none');
            $(".ri_job_month").css('vertical-align', 'top');
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

                $("#ri_job_list").css('transform', 'rotateX(180deg)');
                $("#ri_job_list").css('-ms-transform', 'rotateX(180deg)');
                $("#ri_job_list").css('-webkit-transform', 'rotateX(180deg)');
                $(".ri_job_month").css('transform', 'rotateX(180deg)');
                $(".ri_job_month").css('-ms-transform', 'rotateX(180deg)');
                $(".ri_job_month").css('-webkit-transform', 'rotateX(180deg)');
                $(".ri_job_month").css('vertical-align', 'bottom');
            }
            okno=0;
        }

        // ZMIENIONE!
        function task_open(){
            var taskback = document.getElementById("task_background");
            taskback.style.display="inline";
            document.body.style.overflowY="hidden";

            $("#ri_job_list").css('transform', 'none');
            $("#ri_job_list").css('-ms-transform', 'none');
            $("#ri_job_list").css('-webkit-transform', 'none');
            $(".ri_job_month").css('transform', 'none');
            $(".ri_job_month").css('-ms-transform', 'none');
            $(".ri_job_month").css('-webkit-transform', 'none');
            $(".ri_job_month").css('vertical-align', 'top');

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

        // Skrypty misji RI

        function ri_add(month){
            var task_month = month;
            var newtask = document.createElement("div");
            var task_timestamp = Date.now();
            newtask.innerHTML = "<textarea data-autoresize class='task_job_textarea' id='textarea_"+task_timestamp+"' style='width:80%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false'></textarea><button class='ri_job_end_button' id='butt_"+task_timestamp+"' onclick='ri_done("+task_timestamp+")'>x</button><button class='ri_job_complete_button' id='check_"+task_timestamp+"' onclick='ri_check("+task_timestamp+")'>✓</button>";
            newtask.className = "task_job";
            newtask.id = task_timestamp;
            newtask.onclick = function(){
                ri_getinfo(task_timestamp);
            };
            newtask.onchange = function(){
                ri_change(task_timestamp, task_month);
            };

            document.getElementById(month).appendChild(newtask);

            document.getElementById(task_timestamp).focus();
            ri_old_info = "";

            jQuery.each(jQuery('textarea[data-autoresize]'), function() {
            var offset = this.offsetHeight - this.clientHeight;
            
            var resizeTextarea = function(el) {
                jQuery(el).css('height', 'auto').css('height', el.scrollHeight + offset);
            };
            jQuery(this).on('keyup input', function() { resizeTextarea(this); }).removeAttr('data-autoresize');
            });
        }

        function ri_check(task_id){

            $.ajax({
                url: "additional/ri_processor.php?check=1&ri_job="+task_id
            }).done(function(data) { // data what is sent back by the php page
                $('#thrash').html(data); // display data
            });

            if($('#butt_'+task_id).css('display') != "none"){
                document.getElementById(task_id).style.backgroundColor = "#ccffcc";
                document.getElementById("check_"+task_id).style.backgroundColor = "#ccffcc";
                document.getElementById("check_"+task_id).style.color = "gray";
                document.getElementById("butt_"+task_id).style.display = "none";
                document.getElementById("textarea_"+task_id).style.backgroundColor = "#ccffcc";
            }
            else{
                document.getElementById(task_id).style.backgroundColor = "#f2f2f2";
                document.getElementById("check_"+task_id).style.backgroundColor = "#f2f2f2";
                document.getElementById("check_"+task_id).style.color = "green";
                document.getElementById("butt_"+task_id).style.display = "inline";
                document.getElementById("textarea_"+task_id).style.backgroundColor = "#f2f2f2";
            }
        }

        function ri_done(task_id){

            var ri_done_confirm = confirm("Czy na pewno chcesz usunąć te zadanie?");

            if(ri_done_confirm == true){
                $.ajax({
                    url: "additional/ri_processor.php?delete=1&ri_job="+task_id
                }).done(function(data) { // data what is sent back by the php page
                    $('#thrash').html(data); // display data
                });

                document.getElementById(task_id).style.display = "none";
            }
        }

        function ri_change(task_id, task_month){
            var new_task = document.getElementById("textarea_"+task_id).value;
            new_task = new_task.replace(/\n\r?/g, '\\n');

            $.ajax({
                url: "additional/ri_processor.php?update=1&ri_job="+task_id+"&new="+new_task+"&month="+task_month
            }).done(function(data) { // data what is sent back by the php page
                $('#thrash').html(data); // display data
            });
        }

        function ri_getinfo(task_id){
            val = document.getElementById(task_id).value;
            val = val.replace(/\n\r?/g, '\\n');
            task_old_info = val;
        }

        $('textarea').blur(); //DZIĘKI TEMU TEXTAREA SIĘ NAPRAWIĄ
        

        // -----
    </script>
</html>