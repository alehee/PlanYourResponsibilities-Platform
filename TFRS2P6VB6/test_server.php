<?php
// TEN PLIK POWINIEN BYĆ WYKONYWANY RAZ DZIENNIE NA KOMPUTERZE, ABY ZAKTUALIZOWAĆ ZADANIA ORAZ WYCZYŚCIĆ TABELĘ

$conn = @new mysqli("localhost", "u986763087_pld", "AleHeePLD$", "u986763087_pld");

$conn -> query("SET CHARSET utf8");
$conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

$already = 0;
$today = date("d.m.Y");
$sql = "SELECT Date FROM save_server WHERE Date='$today'";
$que = $conn -> query($sql);
while($res = mysqli_fetch_array($que)){
	$already = 1;
}


/// WYSYŁANIE MAILI DO KADR Z INFORMACJĄ O DZISIEJSZYCH OBOWIĄZKACH

$hr_date = date("Y-m-d");
$hr_notedate = "2030-".date("m-d");
$hr_message = "";
$hr_note = "";
$ilosc_zadan = 0;

$sql = "SELECT Info FROM hr_tasks WHERE Deadline='$hr_notedate'";
$que = $conn -> query($sql);
while($res = mysqli_fetch_array($que)){
    $hr_note = $res["Info"];
}

$hr_message.="
Notatka dnia $today:
$hr_note
";

$sql = "SELECT Info FROM hr_tasks WHERE Deadline='$hr_date' AND Completed='false'";
$que = $conn -> query($sql);
while($res = mysqli_fetch_array($que)){
    $hr_task = $res["Info"];

$hr_message.="
- $hr_task,";

$ilosc_zadan++;
}

$hr_message.="

Zaloguj się na plandeca.pl i sprawdź szczegóły!
Wygenerowano: ".date("Y-m-d G:i:s");

// WYŚLIJ JEŚLI SĄ ZADANIA
if($ilosc_zadan > 0 || $hr_note != ""){
    $sql = "SELECT Email FROM users WHERE Rola='kadr' OR ID='6'";
    $que = $conn -> query($sql);
    while($res = mysqli_fetch_array($que)){
        $mail = $res["Email"];

        mail($mail, "[PLANDECA] Zadania kadrowe $today", $hr_message, "From: PlanDeca@aleksanderheese.pl");
    }
}

/// ==========

$conn -> close();

// FUNKCJE

function checkdays($date_job){
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
    
	
	return $sum;
}

?>