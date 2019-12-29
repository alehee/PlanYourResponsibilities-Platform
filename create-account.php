<?php
session_start();

require_once('additional/func.php');
require_once('additional/navbar.php');

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
        <title>Utwórz Profil</title>
        <link rel="stylesheet" href="style/main.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body onload="time()" class='normal'>

        <!-- Pasek z linkami --->
        <?php echo $navbar ?>

        <header>
            <div id="nav_handle"><img src='icons/menu-3-white.png' onclick="nav_open()"/></div>
            <h1 style="width:60%; float:left;">PlanDeca</h1><br>
            <div style="clear:both;"></div>
            <p id="p_timer"><br></p>
        </header>

        <div class="create_panel">
        <div style="text-align:center; font-size:200%; padding:20px;"><b>UTWÓRZ NOWY PROFIL</b></div>
        <form action="additional/create_acc.php" method="POST" enctype="multipart/form-data">
            <div style="margin:0 auto; padding:10px; font-size:120%; width:350px;"><b style='color:#0082C3;'>IMIĘ: </b><input type="text" style="font-size:80%; float:right;" name="imie" required/></div> 
            <div style="margin:0 auto; padding:10px; font-size:120%; width:350px;"><b style='color:#0082C3;'>NAZWISKO: </b><input type="text" style="font-size:80%; float:right;" name="nazwisko" required/></div> 
            <div style="margin:0 auto; padding:10px; font-size:120%; width:350px;"><b style='color:#0082C3;'>LOGIN: </b><input type="text" style="font-size:80%; float:right;" name="login" required/></div>
            <div style="margin:0 auto; padding:10px; font-size:120%; width:350px;"><b style='color:#0082C3;'>HASŁO: </b><input type="password" style="font-size:80%; float:right;" name="password" required/></div>
            <div style="margin:0 auto; padding:10px; font-size:120%; width:350px;"><b style='color:#0082C3;'>E-MAIL: </b><input type="text" style="font-size:80%; float:right;" name="email" required/></div>
            <div style="text-align:center; margin:0 auto; padding:10px; font-size:120%; width:350px;"><b style='color:red;'>PAMIĘTAJ ŻE ZDJĘCIE POWINNO BYĆ KWADRATOWE</b><br><b>ZDJĘCIE (.png): </b><input type="file" accept="image/png" name="photo"/><br><a href="https://imageresizer.com" target="_blank">LINK DO EDYTORA ZDJĘĆ</a></div>
            <div style="text-align:center; margin:0 auto; padding:10px; font-size:120%; width:350px;"><b style='color:#0082C3;'>GRUPA: </b>
            <select name="dzial" style="font-size:100%;" required>
                <option value="nskl">Niski Skład</option>
                <option value="wskl">Wysoki Skład</option>
                <option value="ecom">E-commerce</option>
                <option value="ramp">Rampa</option>
                <option value="resz">Reszta</option>
            </select>
            </div>
            <div style="text-align:center; margin:10px;"><input type="submit" class="create_panel_butt" value="UTWÓRZ UŻYTKOWNIKA"/></div>
        </form>
        </div>

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