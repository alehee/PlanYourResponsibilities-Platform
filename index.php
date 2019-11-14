<?php
session_start();

if(isset($_SESSION["log"]))
{
    header("location:user.php");
    exit();
}

else if(isset($_POST["log_login"]) && isset($_POST["log_password"]))
{
    $login=$_POST["log_login"];
    $pass=$_POST["log_password"];

    require_once "connection.php";
    $conn = mysqli_connect($host, $user_db, $password_db, $db_name);
    $sql = "SELECT ID, Login FROM users WHERE Login='$login' AND Password='$pass'";
    $que = mysqli_query($conn, $sql);
    $res = mysqli_fetch_array($que);

    if($res>0)
    {
        $_SESSION["log"]=$login;
        $_SESSION["id"]=$res["ID"];
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
        <title>Projekt PYR</title>
        <link rel="stylesheet" href="style/main.css"/>
    </head>
    <body>
        <header>
            <h1>. : Plan Your Responsibilities : .</h1>
        </header>

        <div id="div_login">
            <form action="index.php" method="POST">
                <h3>LOGIN</h3>
                <input type="text" name="log_login" required/>
                <h3>HASŁO</h3>
                <input type="password" name="log_password" required/>
                <br>
                <input type="submit" value="Zaloguj"/>
                <p id="alert"><br></p>
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