<?php
session_start();

if(!isset($_SESSION["log"]))
{
    header("location:index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-2">
        <title>user - PYR</title>
        <link rel="stylesheet" href="style/main.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body>
        <header>
            <h1>.:Plan Your Responsibilities:.</h1><br>
            <h2>Konto: <?php echo $_SESSION["log"]." ID: ".$_SESSION["id"]; ?></h2>
        </header>

        <p><a href="logout.php" id="logout">WYLOGUJ</a></p>

        <div id="div_jobs">
            <div><h2>Zadania</h2></div>
            <?php
                require_once("connection.php");
                require_once("additional/build.php");
                $conn = mysqli_connect($host, $user_db, $password_db, $db_name);

                mysqli_query($conn, "SET CHARSET utf8");
                mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                $id = $_SESSION["id"];
                if(isset($conn))
                {
                    $sql="SELECT * FROM job WHERE ForWho=$id";
                    $que=mysqli_query($conn, $sql);
                    while($res=mysqli_fetch_array($que))
                    {
                        echo $div_job_top;
                        echo "Deadline: ".$res["End"]."<br>";
                        echo "Dodano przez ID: ".$res["WhoAdd"]."<br><br>";
                        $topic = $res["Topic"];
                        $bufor = "";
                        if(strlen($topic)>150)
                        {
                            for($i=0; $i<150; $i++)
                            {
                                if($i>130 && $topic[$i]==" ")
                                {
                                    echo "...";
                                    $i=149;
                                }
                                else 
                                    echo $topic[$i];
                            }
                        }
                        else
                            $bufor=$topic;
                        echo $bufor."<br>";
                        echo $div_job_bottom;
                    }
                }

                mysqli_close($conn);
            ?>
        </div>  

        <div id="div_done"><p>Pokaż wypełnione zadania</p></div>
        <div id="done"><p>*wypełnione zadania*</p></div>

    </body>

    <script>
        $(document).ready(function(){
            $("#div_done").click(function(){
                $("#done").slideToggle("slow");
            });
        });
    </script>
</html>