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
            </div>
        </div>

        <header>
            <h1>.:Plan Your Responsibilities:.</h1><br>
            <h2>Konto: <?php echo $_SESSION["log"]." ID: ".$_SESSION["id"]; ?></h2>
        </header>

        <p><a href="logout.php" id="logout">WYLOGUJ</a></p><br>
        <p onclick="new_job()" id="new_job">DODAJ ZADANIE</p>

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

                        $temp = $res["WhoAdd"];
                        $temp_sql = "SELECT Login FROM users WHERE ID='$temp'";
                        $temp_que = mysqli_query($conn, $temp_sql);
                        $temp = mysqli_fetch_array($temp_que);

                        echo "Dodano przez: ".$temp["Login"]."<br><br>";
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

        <!-- Zadania nadane -->
        <div id="div_nadane">
        <div><h2>Zadania nadane</h2></div>
        <?php
            $my_id_nadane = $_SESSION["id"];
            $exist = 1;
            $is_needed = 0;

            /*
                JAK DZIAŁA KOD?
                Sprawdza czy istnieje zadanie, które nadaliśmy my.
                Jeżeli tak to te zadania są filtrowane czy to my jesteśmy dodani jako wykonawcy. 
                Jeżeli jest przynajmniej jedno to wtedy ukazuje się sekcja.
            */

            require_once("connection.php");
            $conn = @new mysqli($host, $user_db, $password_db, $db_name);

            $conn->query("SET CHARSET utf8");
            $conn->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

            $sql = "SELECT * FROM job WHERE WhoAdd='$my_id_nadane' AND ForWho!='$my_id_nadane'";
            $que = $conn -> query($sql);
            while($res = mysqli_fetch_array($que)){
                $the_id=$res["The_ID"];
                $temp_sql = "SELECT ID FROM job WHERE The_ID='$the_id' AND ForWho='$my_id_nadane'";
                $temp_que = $conn -> query($temp_sql);
                while($temp = mysqli_fetch_array($temp_que))
                    $exist=0;

                if($exist==1){
                    $div_job_top='<div class="job" id="'.$the_id.'"><div class="job_topic" id="'.$the_id.'" onclick="job_popup(this.id)">';

                    $div_job_bottom='</div></div>';

                    echo $div_job_top;
                    echo $the_id."<br>";
                    echo "Deadline: ".$res["End"]."<br>";

                    $temp = $res["WhoAdd"];
                    $temp_sql = "SELECT Login FROM users WHERE ID='$temp'";
                    $temp_que = mysqli_query($conn, $temp_sql);
                    $temp = mysqli_fetch_array($temp_que);

                    echo "Dodano przez: ".$temp["Login"]."<br><br>";
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

                    $is_needed=1;
                }

                $exist=1;
            }

            $conn -> close();
        ?>
        </div>

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
                        echo "<b>".$res["Topic"]."</b><br>";

                        $temp = $res["WhoAdd"];
                        $temp_sql = "SELECT Login FROM users WHERE ID='$temp'";
                        $temp_que = mysqli_query($conn, $temp_sql);
                        $temp = mysqli_fetch_array($temp_que);

                        echo "Dodano przez: ".$temp["Login"]."<br>";
                        echo "Planowany koniec: ".$res["End"]."<br>";
                        echo "ID:".$res["The_ID"]." <input type='button' id='".$res["The_ID"]."' value='Przywróć zadanie' onclick='job_undone(this.id)'><br><br>";
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
        // Skrypty dodawania zadania

        function new_job(){
            var the_job = "";

            if(document.getElementById("okno_background").style.display=="none"){
                document.getElementById("okno_background").style.display="inline";

                $.get("additional/processor.php", {the_job: the_job}, function(data){
                    $('#okno_job').html(data);
                });
            }
            else if(okno==0){
                document.getElementById("okno_background").style.display="none";
                document.getElementById("new_job_div_1").style.display="none";
            }
            
            okno=0;
        }

        // -----

    </script>
</html>