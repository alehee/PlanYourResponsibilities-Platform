<?php

function connect(){
    $host = "riverlakestudios.pl";
    $user_db = "30908302_pyr";
    $password_db = "rvrlkPYR_";
    $db_name = "30908302_pyr";

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

    $sql = "SELECT Imie, Nazwisko FROM users WHERE ID='$id' LIMIT 1";
    $que = $conn -> query($sql);
    while($res = mysqli_fetch_array($que)){
        $name = $res["Imie"]." ".$res["Nazwisko"];
    }

    $conn -> close();

    return $name;
}

?>