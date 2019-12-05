<?php
session_start();

require_once('additional/func.php');
require_once('additional/navbar.php');

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
        <link rel="stylesheet" href="style/main.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body onload="time()" class='normal'>

        <!-- Pasek z linkami --->
        <?php echo $navbar ?>

        <header>
            <div id="nav_handle"><img src='icons/menu-3-white.png' onclick="nav_open()"/></div>
            <h1 style="width:60%; float:left;">. : Plan Your Responsibilities : .</h1><br>
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