<?php
session_start();

if(isset($_SESSION["log"]))
{
    unset($_SESSION["log"]);
}
else
{
    header("location:index.php");
    exit();
}

session_destroy();
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8"/>
        <title>Wylogowano</title>
        <link rel="stylesheet" href="style/main.css?version=0.4.3"/>
        <link rel="icon" type="image/x-icon" href="icons/favicon.ico">
    </head>
    <body>
        <header>
            <h1>PlanDeca</h1>
        </header>

    <p>WYLOGOWANO!</p><br><br>
    <p>Zaraz przekieruję Cię na stronę główną...</p><br>
    <p>Możesz to też zrobić ręcznie:</p>
    <p><a href="index.php">Strona główna</a></p>

    </body>
    <script>
        setTimeout(function(){
            location.reload();
        }, 1000);
    </script>
</html>