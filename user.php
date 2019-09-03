<?php
session_start();

if(!isset($_SESSION["log"]) || !isset($_SESSION["id"]))
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
        <!-- Popup okienko zadań -->
        <div id="okno_background" onclick="job_popup()">
            <div id="okno_job" onclick="job_okno()">
                <?php
                    if(isset($_GET["the_id"]))
                        echo $_GET["the_id"];
                ?>
            </div>
        </div>

        <header>
            <h1>.:Plan Your Responsibilities:.</h1><br>
            <h2>Konto: <?php echo $_SESSION["log"]." ID: ".$_SESSION["id"]; ?></h2>
        </header>

        <p><a href="logout.php" id="logout">WYLOGUJ</a></p>

        <!-- Wszystkie zadania wyświetlane -->
        <form id="div_jobs" method="GET" action="user.php">
            <div><h2>Zadania</h2></div>
            <?php
                require_once("connection.php");
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
                        $div_job_top='<div class="job" id="'.$res["The_ID"].'"><div class="job_topic" id="'.$res["The_ID"].'" onclick="job_popup(this.id)">';
                        $div_job_bottom='</div><input type="button" id="'.$res["The_ID"].'" value="Wykonano" onclick="job_done(this.id)"/></div>';

                        echo $div_job_top;
                        echo $res["The_ID"]."<br>";
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
        </form>  

        <!-- Zadania ukończone -->
        <div id="div_done"><p>Pokaż wypełnione zadania</p></div>
        <div id="done">
                <?php
                    $my_id=$_SESSION["id"];

                    require_once("connection.php");
                    $conn = @new mysqli($host, $user_db, $password_db, $db_name);

                    $conn -> query("SET CHARSET utf8");
                    $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

                    $sql="SELECT * FROM done WHERE ForWho=$my_id";
                    $que = $conn -> query($sql);
                    while($res = mysqli_fetch_array($que)){
                        echo "<input type='button' id='".$res["The_ID"]."' value='Przywróć zadanie' onclick='job_undone(this.id)'> ID:".$res["The_ID"]." - ".$res["Topic"]." - ".$res["WhoAdd"]." - ".$res["ForWho"]." - ".$res["End"]."<br>";
                    }

                    $conn -> close();
                ?>
        </div>

        <div id="thrash"></div>

    </body>

    <script>
        // Musi tu być bo nie działa skrypt
        document.getElementById("okno_background").style.display="none";

        // Skrypty dla wypełnionych zadań
        $(document).ready(function(){
            $("#div_done").click(function(){
                $("#done").slideToggle("slow");
            });
        });

        // -----
        // Skrypty dla aktywnych zadań

        var okno=0;
        function job_okno(){
            okno=1;
        };

        function job_popup(elem){
            var popup = document.getElementById("okno_job");

            if(document.getElementById("okno_background").style.display=="none"){
                document.getElementById("okno_background").style.display="inline";
                $.get("additional/processor.php", {elem: elem}, function(data){
                    $('#okno_job').html(data);
                });
            }
            else if(okno==0)
                document.getElementById("okno_background").style.display="none";
            
            okno=0;
        };

        // -----
        // Skrypty zakończonych zadań

        function job_done(id){
            $.get("additional/done.php", {id: id}, function(data){
                $("#thrash").html(data);
            });
        }

        function job_undone(id){
            $.get("additional/undone.php", {id: id}, function(data){
                $("#thrash").html(data);
            })
        }

        // -----

    </script>
</html>