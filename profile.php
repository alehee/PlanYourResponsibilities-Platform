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

if(!isset($_SESSION["sort"]))
	$_SESSION["sort"]='Deadline';
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-2">
        <title>Mój Profil</title>
        <link rel="stylesheet" href="style/main.css?version=0.4.3"/>
        <link rel="icon" type="image/x-icon" href="icons/favicon.ico">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body onload="time()" class='normal'>
    <div class="content">

        <!-- Pasek z linkami --->
        <?php echo $navbar ?>
        <?php echo $taskbar ?>

        <!-- Popup zmień dane (z hr_tasks.php) -->
        <div id="okno_background" onclick="hr_tasks_hide()">
            <div id="hr_task_form" onclick="hr_tasks_hidenot()">
                <form action="additional/change_acc.php" method="POST">
                <input id="profile_changeoption_option" type="text" style="display:none;" name="option">
                <div>
                    <label>Aktualne hasło:</label><br>
                    <input type="password" placeholder="Hasło" name="pwd">
                </div>
                <div>
                    <label id="profile_changeoption_label"></label><br>
                    <input id="profile_changeoption_text" type="text" name="new_info">
                    <input id="profile_changeoption_password" type="password" placeholder="Nowe hasło" name="new_pwd">
                </div>
                <button type="submit">ZMIEŃ</button>
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

        <!-- Panel z profilem --->
        <div class="profile_panel">
        <div style="text-align:center; font-size:200%; padding:20px;"><b>MÓJ PROFIL</b></div>
        <div style='width:80%; margin-left:10%; margin-right:10%;'>
            <div class="profile_img_div"><img src="<?php echo 'photo/'.$_SESSION["id"].'.png' ?>"/>
                <div class="profile_img_button" onclick="open_form()"><b>ZMIEŃ ZDJĘCIE</b></div>
                <form class="profile_img_form" id="img_form" action="additional/change_acc.php" method="POST" enctype="multipart/form-data">
                    <b style='color:red;'>PAMIĘTAJ ŻE ZDJĘCIE POWINNO BYĆ KWADRATOWE</b><br>
                    <input type="file" accept="image/*" name="photo"/><br>
                    <a href="https://imageresizer.com" target="_blank">LINK DO EDYTORA ZDJĘĆ</a><br>
                    <input type="submit" class="profile_img_button_send" value="PRZEŚLIJ"/>
                </form>
            </div>
            <div class="profile_info">
                <?php 
                    require_once("connection.php");
                    $conn = @new mysqli($host, $user_db, $password_db, $db_name);

                    mysqli_query($conn, "SET CHARSET utf8");
                    mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                    $login;
                    $password;
                    $imie;
                    $nazwisko;
                    $dzial;
                    $rola;
                    $email;
                    $jednostka;
                    $activity;

                    $id = $_SESSION["id"];
                    $sql = "SELECT Login, Password, Imie, Nazwisko, Dzial, Rola, Email, Jednostka, Activity FROM users WHERE ID='$id' LIMIT 1";
                    $que = $conn -> query($sql);
                    while($res = mysqli_fetch_array($que)){
                        $login = $res["Login"];
                        $password = $res["Password"];
                        $imie = $res["Imie"];
                        $nazwisko = $res["Nazwisko"];
                        $dzial = $res["Dzial"];
                        $rola = $res["Rola"];
                        $email = $res["Email"];
                        $jednostka = $res["Jednostka"];
                        $activity = $res["Activity"];
                    }

                    switch($dzial){
                        case 'wskl':
                            $dzial = "Wysoki Skład";
                        break;
                        case 'btwn':
                            $dzial = "B'Twin";
                        break;
                        case 'quec':
                            $dzial = "Quechua";
                        break;
                        case 'kale':
                            $dzial = "Kalenji";
                        break;
                        case 'domy':
                            $dzial = "Domyos";
                        break;
                        case 'ines':
                            $dzial = "Inesis";
                        break;
                        case 'sube':
                            $dzial = "Subea";
                        break;
                        case 'ecom':
                            $dzial = "E-commerce";
                        break;
                        case 'geol':
                            $dzial = "Geologic";
                        break;
                        case 'ramp':
                            $dzial = "Rampa";
                        break;
                        case 'kadr':
                            $dzial = "Kadry";
                        break;
                        case 'admi':
                            $dzial = "Administracja i Liderzy";
                        break;
                        default:
                            $dzial = "Niezdefiniowany";
                        break;
                    }

                    switch($rola){
                        case 'prac':
                            $rola = "Pracownik Zespołu Logistycznego";
                        break;
                        case 'szko':
                            $rola = "Szkoleniowiec";
                        break;
                        case 'kier':
                            $rola = "Kierownik";
                        break;
                        case 'staz':
                            $rola = "Stażysta";
                        break;
                        case 'kadr':
                            $rola = "Kadry";
                        break;
                        case 'inna':
                            $rola = "Administracja i Liderzy";
                        break;
                        default:
                            $rola = "Niezdefiniowany";
                        break;
                    }

                    echo "<script> var real_password = '".$password."';</script>";

                    $pass_len = strlen($password);
                    $password = "";

                    for($i=0; $i<$pass_len; $i++){
                        $password = $password."*";
                    }

                    echo "<div style='float:left; margin-right:15%; margin-bottom:20px;'><b style='font-size:80%; color:#0082C3;'>IMIĘ: </b>$imie</div>";
                    echo "<div style='float:left; margin-bottom:20px;'><b style='font-size:80%; color:#0082C3;'>NAZWISKO: </b>$nazwisko</div>";
                    echo "<div style='clear:both;'></div>";
                    echo "<div style='float:left; margin-right:15%; margin-bottom:20px;'><b style='font-size:80%; color:#0082C3;'>LOGIN: </b>$login<span class='panel_info_zmien' onclick='change_login()'><img src='icons/edit-blue.png'/>Zmień</span></div>";
                    echo "<div style='float:left; margin-right:15%; margin-bottom:20px;'><b style='font-size:80%; color:#0082C3;'>HASŁO: </b>$password<span class='panel_info_zmien' onclick='change_password()'><img src='icons/edit-blue.png'/>Zmień</span></div>";
                    echo "<div style='float:left; margin-bottom:20px;'><b style='font-size:80%; color:#0082C3;'>E-MAIL: </b>$email<span class='panel_info_zmien' onclick='change_email()'><img src='icons/edit-blue.png'/>Zmień</span></div>";
                    echo "<div style='clear:both;'></div>";
                    echo "<div style='float:left; margin-right:15%; margin-bottom:20px;'><b style='font-size:80%; color:#0082C3;'>DZIAŁ: </b>$dzial</div>";
                    echo "<div style='float:left; margin-right:15%; margin-bottom:20px;'><b style='font-size:80%; color:#0082C3;'>ROLA: </b>$rola</div>";
                    echo "<div style='float:left; margin-bottom:20px;'><b style='font-size:80%; color:#0082C3;'>JEDNOSTKA: </b>$jednostka</div>";
                    echo "<div style='clear:both;'></div>";
                    echo "<div style='float:left; margin-bottom:20px;'><b style='font-size:80%; color:#0082C3;'>OSTATNIA AKTYWNOŚĆ: </b>$activity</div>";

                    $conn -> close();
                ?>
            </div>
        </div>
        <div style="clear:both;"></div>
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

        /// SKRYPTY ZADAŃ KADROWYCH DLA OBSŁUGI ZMIAN W PROFILU

        var okno=0;

        // KOPIA Z hr_tasks.php
        function hr_tasks_hidenot(){
            okno=1;
        }

        // KOPIA Z hr_tasks.php
        function hr_tasks_hide(){
            if(okno==0){
                $("#okno_background").css("display", "none");
                $("#hr_list").css("overflowX", "auto");
                document.body.style.overflowY="auto";
            }
            okno=0;
        }

        // KOPIA Z hr_tasks.php
        function hr_tasks_open(){
            $("#okno_background").css("display", "inline");
            $("#hr_list").css("overflowX", "hidden");
            document.body.style.overflowY="hidden";
        }



        /// ==========

        /// SKRYPTY ZMIAN W PROFILU

        var form_opened=0;
        function open_form(){
            if(form_opened==0){
                document.getElementById("img_form").style.display="inline";
                form_opened=1;
            }
            else if(form_opened==1){
                document.getElementById("img_form").style.display="none";
                form_opened=0;
            }
        }

        function change_login(){
            hr_tasks_open();
            $('#profile_changeoption_password').css('display', 'none');
            $('#profile_changeoption_text').css('display', 'inline');
            $('#profile_changeoption_label').text('Nowy login:');
            $('#profile_changeoption_option').val('login');

            $('#profile_changeoption_text').attr('placeholder', 'Nowy login');
        }

        function change_password(){
            hr_tasks_open();
            $('#profile_changeoption_password').css('display', 'inline');
            $('#profile_changeoption_text').css('display', 'none');
            $('#profile_changeoption_label').text('Nowe hasło:');
            $('#profile_changeoption_option').val('password');
        }

        function change_email(){
            hr_tasks_open();
            $('#profile_changeoption_password').css('display', 'none');
            $('#profile_changeoption_text').css('display', 'inline');
            $('#profile_changeoption_label').text('Nowy e-mail:');
            $('#profile_changeoption_option').val('email');

            $('#profile_changeoption_text').attr('placeholder', 'Nowy e-mail');
        }

        /// ==========

    </script>
</html>