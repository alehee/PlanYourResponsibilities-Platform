<?php
session_start();

if(isset($_SESSION["log"]))
    unset($_SESSION["log"]);

?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8"/>
        <title>Pracujemy nad tym...</title>
        <link rel="stylesheet" href="style/main.css"/>
    </head>
    <body>
        <header>
            <h1>PlanDeca</h1>
        </header>

        <div id="div_login">
            <img src="icons/settings-3-blue.png" style="width:20%; height:auto; margin: 10px 40%;"/>

            <b style="margin:10px; font-size:150%; color:#0082C3;">PLATFORMA JEST TERAZ AKTUALIZOWANA!</b><br><br>
            Obiecuję, że wrócimy za jakiś czas!
        </div>
    </body>
</html>