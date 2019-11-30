<?php
// TEN PLIK POWINIEN BYĆ WYKONYWANY RAZ DZIENNIE NA KOMPUTERZE, ABY ZAKTUALIZOWAĆ ZADANIA ORAZ WYCZYŚCIĆ TABELĘ

$conn = @new mysqli("riverlakestudios.pl", "30908302_pyr", "rvrlkPYR_", "30908302_pyr");

// POBIERANIE WSZYSTKICH CZERWONYCH ZADAŃ I ZAPISYWANIE ICH W job_red

$sql = "SELECT The_ID, ForWho, End FROM job";
$que = $conn -> query($sql);
while($res = mysqli_fetch_array($que)){
    if(checkif_red($res["End"]) == 1){
        $the_id = $res["The_ID"];
        $forwho = $res["ForWho"];
        $is_in = 0;
        $temp_sql = "SELECT ID FROM job_red WHERE The_ID='$the_id' AND ForWho='$forwho'";
        $temp_que = $conn -> query($temp_sql);
        while($temp_res = mysqli_fetch_array($temp_que)){
            $is_in = 1;
        }
        if($is_in == 0){
            $ilosc_spoznien=0;
            $temp_sql = "INSERT INTO job_red(ID, The_ID, ForWho, Date) VALUES (NULL, '$the_id', '$forwho', CURRENT_TIMESTAMP)";
            $conn -> query($temp_sql);

            $temp_sql = "SELECT Spoznien FROM users WHERE ID='$forwho'";
            $temp_que = $conn -> query($temp_sql);
            while($temp_res = mysqli_fetch_array($temp_que)){
                $ilosc_spoznien=$temp_res["Spoznien"];
            }
            $ilosc_spoznien++;
            $temp_sql = "UPDATE users SET Spoznien='$ilosc_spoznien' WHERE ID='$forwho'";
            $conn -> query($temp_sql);
        }
    }
}

// -----

$conn -> close();

echo "ZAKOŃCZONO SKRYPT SAVE_SERVER - ".date("Y-m-d H:i:s");

// FUNKCJE

function checkif_red($date_job){
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

    $sum = $date_job_var-$date_curr_var;
    if($sum<=0){
        return 1;
    }
    else{
        return 0;
    }
}

?>