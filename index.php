<?php
session_start();

require_once('additional/footer.php');

if(isset($_SESSION["log"]))
{
    header("location:main.php");
    exit();
}

else if(isset($_POST["log_login"]) && isset($_POST["log_password"]) && isset($_POST["log_city"]))
{
    $login=$_POST["log_login"];
    $pass=$_POST["log_password"];
    $city=$_POST["log_city"];

    require_once "connection.php";
    $conn = mysqli_connect($host, $user_db, $password_db, $db_name);
    $sql = "SELECT ID, Login, Dzial, Rola FROM users WHERE Login='$login' AND Password='$pass'";
    $que = mysqli_query($conn, $sql);
    $res = mysqli_fetch_array($que);

    if($res>0)
    {
        $_SESSION["log"]=$login;
        $_SESSION["id"]=$res["ID"];
        $_SESSION["city"]=$city;
        $_SESSION["dzial"]=$res["Dzial"];
        $_SESSION["rola"]=$res["Rola"];
        header("location:main.php");
        exit();
    }

    else
    {
        echo '<script>document.getElementById("alert").innerHTML="Błąd logowania!"</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8"/>
        <title>Zaloguj</title>
        <link rel="stylesheet" href="style/main.css?version=0.2.0"/>
    </head>
    <body>

    <div class="content">

        <header>
            <h1>PlanDeca</h1>
        </header>

        <div class="index_info">
            <h1 style="color:#0082C3; padding-top:10px;">Rozwijamy się!</h1>
            <h5 style="color:#0082C3; padding-bottom:10px;">Aktualna wersja: 0.2.0</h5>
            <div class="index_info_text">
                Cały czas idziemy do przodu co mnie ogromnie cieszy!<br>
                Przychodzę do was z kolejnymi nowościami:<br><br>
            <span style="font-size:90%; color:green;">
                - pinezka na zadaniu, które jest nowe, lub ma nową zawartość<br>
                - nowy styl podziału osób przy dodawaniu nowego zadania<br>
                - nowy styl dodawania osób<br>
                - przystosowanie kolejnych komponentów do obsługi większej ilości zadań<br><br>
            </span>
                <span style="color:gray;">
                Platforma jest nadal rozwijana, daj znać o każdym problemie w sekcji "Zgłoś Usterkę"!<br>
                </span>
            </div>
            <h2 style="color:#0082C3; padding:10px; font-style: normal;">Miłego użytkowania!</h2>
        </div>

        <div id="div_login">
            <form action="index.php" method="POST">
                <div style="margin: 0 auto;"><img src="icons/id-card-3-blue.png" class="index_img"/><input type="text" class="index_logpanel" name="log_login" placeholder="Login" required/></div>
                <div style="margin: 0 auto;"><img src="icons/locked-4-blue.png" class="index_img"/><input type="password" class="index_logpanel" name="log_password" placeholder="Hasło" required/></div>
                <div style="margin 0 auto;"><img src="icons/map-location-blue.png" class="index_img"/>
                <select name="log_city" class="index_logpanel" required>
                    <option value="CAR Gliwice">CAR Gliwice</option>
                </select>
                </div>
                <input type="submit" value="Zaloguj" class="index_logbutt"/>
                <p id="alert"><br></p>

                <datalist id="cities">
                    <option value="CAR Gliwice">
                </datalist>
            </form>
        </div>
        
    </div>

        <?php
            echo $footer;
        ?>
    </body>
</html>

<?php
if(isset($_POST["log_login"]) && isset($_POST["log_password"]))
{
    $login=$_POST["log_login"];
    $pass=$_POST["log_password"];

    require_once "connection.php";
    $conn = mysqli_connect($host, $user_db, $password_db, $db_name);
    $sql = "SELECT Login FROM users WHERE Login='$login' AND Password='$pass'";
    $que = mysqli_query($conn, $sql);
    $res = mysqli_fetch_array($que);

    if($res<0)
    {

    }

    else
    {
        echo '<script>document.getElementById("alert").innerHTML="Błąd logowania!"</script>';
    }
}
?>
