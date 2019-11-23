<?php
session_start();

require_once('additional/func.php');

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
        <title>Mój Profil</title>
        <link rel="stylesheet" href="style/main.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body onload="time()" class='normal'>

        <!-- Pasek z linkami --->
        <div id="nav_background" onclick="nav_hide()">
            <div id="nav" onclick="nav_hidenot()">
                <div id="nav_profile">
                    <img src="<?php echo "photo/".$_SESSION["id"].".png" ?>"/>
                    <p style="color:white; padding: 5px;"><?php echo name_by_id($_SESSION["id"]) ?></p>
                </div>
                <div id="nav_link" onclick='nav_classic_link("user.php")'><span style="color:#00ffff;">PANEL GŁÓWNY</span></div>
                <div id="nav_link" onclick='nav_link("http:\/\/mail.oxylane.com")'>MAIL</div>
                <div id="nav_link" onclick='nav_link("http:\/\/riverlakestudios.pl")'>LINK 1</div>
                <div id="nav_link" onclick='nav_link("http:\/\/wp.pl")'>LINK 2</div>
                <div id="nav_link" onclick='nav_link("http:\/\/lowcygier.pl")'>LINK 3</div>
                <div id="nav_link" onclick='nav_link("http:\/\/drive.google.com")'>LINK 4</div>
                <div id="nav_link" onclick='nav_classic_link("profile.php")'><span style="color:#ff00ff;">MÓJ PROFIL</span></div>
                <div id="nav_link" onclick='nav_classic_link("create-account.php")'><span style="color:#00ff00;">DODAJ NOWĄ OSOBĘ</span></div>
                <div id="nav_link" onclick='nav_classic_link("logout.php")'><span style="color:red;">WYLOGUJ</span></div>
                <div id="nav_link" onclick='nav_classic_link("report.php")'><span style="color:#ffbf00;">ZGŁOŚ USTERKĘ</span></div>
            </div>
        </div>

        <header>
            <div id="nav_handle"><img src='icons/menu-3-white.png' onclick="nav_open()"/></div>
            <h1 style="width:60%; float:left;">. : Plan Your Responsibilities : .</h1><br>
            <div style="clear:both;"></div>
            <p id="p_timer"><br></p>
        </header>

        <!-- Panel z profilami --->
        <div class="profile_panel">

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