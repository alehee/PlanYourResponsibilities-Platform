<?php
session_start();

if(isset($_SESSION["log"]))
{
    header("location:user.php");
    exit();
}

else if(isset($_POST["log_login"]) && isset($_POST["log_password"]) && isset($_POST["log_city"]))
{
    $login=$_POST["log_login"];
    $pass=$_POST["log_password"];
    $city=$_POST["log_city"];

    require_once "connection.php";
    $conn = mysqli_connect($host, $user_db, $password_db, $db_name);
    $sql = "SELECT ID, Login FROM users WHERE Login='$login' AND Password='$pass'";
    $que = mysqli_query($conn, $sql);
    $res = mysqli_fetch_array($que);

    if($res>0)
    {
        $_SESSION["log"]=$login;
        $_SESSION["id"]=$res["ID"];
        $_SESSION["city"]=$city;
        header("location:user.php");
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
        <link rel="stylesheet" href="style/main.css"/>
    </head>
    <body>
        <header>
            <h1>. : Plan Your Responsibilities : .</h1>
        </header>

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