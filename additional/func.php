<?php
require_once __DIR__ . '/../connection.php';

function connect(){
    return @new mysqli($host, $user_db, $password_db, $db_name);
}

// FUNKCJA ZWRACAJĄCA POPRAWNĄ DATĘ
function proper_date($date){
    $better_date="";
    $bufor="";
    // DZIEŃ
    for($i=8; $i<10; $i++)
    {
        $better_date = $better_date.$date[$i];
    }
    // MIESIĄC
    for($i=5; $i<7; $i++)
    {
        $bufor = $bufor.$date[$i];
    }
    switch($bufor){
        case "01":
            $better_date= $better_date." sty ";
        break;
        case "02":
            $better_date= $better_date." lut ";
        break;
        case "03":
            $better_date= $better_date." mar ";
        break;
        case "04":
            $better_date= $better_date." kwi ";
        break;
        case "05":
            $better_date= $better_date." maj ";
        break;
        case "06":
            $better_date= $better_date." cze ";
        break;
        case "07":
            $better_date= $better_date." lip ";
        break;
        case "08":
            $better_date= $better_date." sie ";
        break;
        case "09":
            $better_date= $better_date." wrz ";
        break;
        case "10":
            $better_date= $better_date." paź ";
        break;
        case "11":
            $better_date= $better_date." lis ";
        break;
        case "12":
            $better_date= $better_date." gru ";
        break;
    }
    // ROK
    for($i=0; $i<4; $i++)
    {
        $better_date = $better_date.$date[$i];
    }

    return $better_date;
}

// FUNKCJA ZWRACA IMIĘ I NAZWISKO DLA ID
function name_by_id($id){
    $name="";
    $conn = connect();

    if($id != 0){
        $conn->query("SET CHARSET utf8");
        $conn->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        $sql = "SELECT Imie, Nazwisko FROM users WHERE ID='$id' LIMIT 1";
        $que = $conn -> query($sql);
        while($res = mysqli_fetch_array($que)){
            $name = $res["Imie"]." ".$res["Nazwisko"];
        }
    }

    else{
        $name = "PlanDeca";
    }    

    $conn -> close();

    return $name;
}

// FUNKCJA WYŚWIETLAJĄCA ILE DNI ZOSTAŁO DO WYKONANIA ZADANIA
function how_many_days_left($date_job){

    $date_curr = date("Y-m-d");
    $job_important=0;

    $date_curr_buf="";
    $date_job_buf="";

    for($i=0; $i<4; $i++){
        $date_curr_buf = $date_curr_buf.$date_curr[$i];
        $date_job_buf = $date_job_buf.$date_job[$i];
    }

    // YEAR
    $date_curr_var=(intval($date_curr_buf)-1970)*365;
    $date_job_var=(intval($date_job_buf)-1970)*365;

    $date_curr_buf="";
    $date_job_buf="";

    for($i=5; $i<7; $i++){
        $date_curr_buf = $date_curr_buf.$date_curr[$i];
        $date_job_buf = $date_job_buf.$date_job[$i];
    }

    // MONTH
    $date_curr_var=$date_curr_var+(intval($date_curr_buf))*30;
    $date_job_var=$date_job_var+(intval($date_job_buf))*30;

    $date_curr_buf="";
    $date_job_buf="";

    for($i=8; $i<10; $i++){
        $date_curr_buf = $date_curr_buf.$date_curr[$i];
        $date_job_buf = $date_job_buf.$date_job[$i];
    }

    // DAY
    $date_curr_var=$date_curr_var+(intval($date_curr_buf));
    $date_job_var=$date_job_var+(intval($date_job_buf));

    return $date_job_var-$date_curr_var;
}

// FUNKCJA ZWRACAJĄCA ILOŚCI NA PANEL MAIN
function how_many_jobs($id, $type){
    $conn = connect();
    $sql = "";
    $result = 0;

    switch($type){
        case "def":
            $sql = "SELECT ID FROM job WHERE ForWho='$id' AND Type='def'";
        break;

        case "sta":
            $sql = "SELECT ID FROM job WHERE ForWho='$id' AND Type='sta'";
        break;

        case "nadane":
                $exist = 1;
                $already = 0;
                $nadane_tab = array();
                $job_number = 0;
                
                $sql = "SELECT * FROM job WHERE WhoAdd='$id' AND ForWho!='$id'";
                $que = $conn -> query($sql);

                while($res = mysqli_fetch_array($que)){
                    $exist = 1;
                    $the_id=$res["The_ID"];

                    $temp_sql = "SELECT ID FROM job WHERE The_ID='$the_id' AND ForWho='$id'";
                    $temp_que = $conn -> query($temp_sql);
                    while($temp = mysqli_fetch_array($temp_que))
                        $exist=0;

                    foreach($nadane_tab as $nadane_id){
                        if($nadane_id == $the_id)
                            $exist=0;
                    }

                    if($exist==1){
                        $job_number++;
                        array_push($nadane_tab, $the_id);
                    }
                }
                $result = $job_number;
        break;

        case "ri":
            $func_ri_month = date("ym");
            $sql = "SELECT ID FROM job_ri WHERE ForWho='$id' AND Month='$func_ri_month' AND Completed='false'";
        break;
    }

    if($type=="def" || $type=="sta" || $type=="ri"){
        $que = $conn -> query($sql);
        $result = mysqli_num_rows($que);
    }

    $conn -> close();
    return $result;
}

?>
