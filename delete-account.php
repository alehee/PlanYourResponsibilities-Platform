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

if($_SESSION["rola"]!="kier" && $_SESSION["dzial"]!="kadr")
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
        <title>Usuń konto</title>
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

        <form id="reset_password_form" action="additional/delete_acc.php" method="POST">
            <div style="text-align:center; font-size:200%; padding:20px;"><b>USUŃ KONTO</b></div>
            <div style="text-align:center; font-size:110%; padding-top:-20px; color:red;"><b>Usunięcie konta jest permamentne i nieodwracalne!</b></div>
            <div style="text-align:center; margin:10px auto; padding-top:15px;" class="reset_butt" onclick="delete_acc_process()">ROZUMIEM</div>

            <div class="reset_option" style="display:none;">
               <input id="reset_radio_1" type="radio" name="reset_option" value="1" checked/><b>PODAM LOGIN</b>
            </div>
            <div class="reset_option" style="display:none;">
                <input id="reset_radio_2" type="radio" name="reset_option" value="2"/><b>PODAM IMIĘ I NAZWISKO</b>
            </div>
            <div class="reset_option" style="display:none;">
                <input id="reset_radio_3" type="radio" name="reset_option" value="3"/><b>PODAM E-MAIL</b>
            </div>

            <div id="reset_input" style="display:none;"><input type="text" name="reset_text"/></div>

            <div id="delete_butt" style="text-align:center; margin:10px; display:none;"><input type="submit" class="reset_butt" value="USUŃ KONTO"/></div>
        </form>

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

                timer.innerHTML= <?php echo '"'.proper_date(date("Y-m-d")).' - "+'; ?> full_day+" - "+full_time;
            }, 1000, 1000)
        }

        // -----
        // Skrypty usuwania konta

        function delete_acc_process(){
            document.getElementsByClassName("reset_butt")[0].style.display = "none";
            for(i=0; i<3; i++){
                document.getElementsByClassName("reset_option")[i].style.display = "block";
            }
            document.getElementById("reset_input").style.display = "block";
            document.getElementById("delete_butt").style.display = "block";
        }

        // -----
    </script>
</html>