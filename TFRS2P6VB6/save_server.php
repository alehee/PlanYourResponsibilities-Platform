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

if($already == 0){	
// POBIERANIE WSZYSTKICH CZERWONYCH ZADAŃ I ZAPISYWANIE ICH W job_red

$sql = "SELECT The_ID, ForWho, End FROM job";
$que = $conn -> query($sql);
while($res = mysqli_fetch_array($que)){
    if(checkdays($res["End"]) <= 0){
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
/// EWENTUALNE DODANIE ZADAŃ CYKLICZNYCH

    /// SEBASTIAN GERLICH - Inwentaryzacja wózków pickingowych
    if(date("d") == "01"){
        $conn -> query("SET CHARSET utf8");
        $conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        // POBIERA OSTATNI NUMER ZADANIA JEDNOSTKI I WSTAWIA KOLEJNY NUMER DO BAZY
        $new_jednostka = "CAR Gliwice";
        $sql = "SELECT The_ID FROM job_index WHERE Jednostka='$new_jednostka'";
        $que = $conn -> query($sql);
        $res = mysqli_fetch_array($que);
        $the_id = 1 + $res['The_ID'];
        $sql = "UPDATE job_index SET The_ID=$the_id WHERE Jednostka='$new_jednostka'";
        $conn -> query($sql);

        // POBIERA DANE DLA ZADANIA
        $new_title = "Inwentaryzacja wózków pickingowych ".date("m.Y");
        $new_info = "Ocena techniczna wózków pickingowych i uzupełnienie pliku: https://docs.google.com/spreadsheets/d/1WWSRG0pZ0V_mOJxKrbfzdI5qdPXT_UmCiS2bxXKA40E/edit#gid=0";
        $new_type = "def";
        $new_deadline = date("Y-m-")."25";
        $new_whoadd = 0;
        $new_length = 2;

        // POBIERA LISTĘ OSÓB DO ZADANIA
        $sql = "SELECT ID FROM users WHERE Rola='kier' OR ID='47' OR ID='48'";
        $que = $conn -> query($sql);
        while($res = mysqli_fetch_array($que)){
            $forwho_id = $res["ID"];
            // JEŻELI ISTNIEJE JUŻ TAKIE ZADANIE TO OMIJA
            $forwho_error = 0;

            $temp_sql = "SELECT ID FROM job WHERE The_ID='$the_id' AND ForWho='$forwho_id'";
            $temp_que = $conn -> query($temp_sql);
            while($temp_res = mysqli_fetch_array($temp_que)){
                $forwho_error = 1;
            }

            if($forwho_error == 0){
                $temp_sql = "INSERT INTO job(ID, The_ID, Topic, Info, Type, WhoAdd, ForWho, Length, Start, Visited, Visited_Admin, End) VALUES (NULL, '$the_id', '$new_title', '$new_info', '$new_type', '$new_whoadd', '$forwho_id', '$new_length', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '$new_deadline')";
                $conn -> query($temp_sql);
            }
        }
    }
    /// ==========

/// ==========
// WYSYŁANIE MAILI INFORMUJĄCYH O ZBLIŻAJĄCYM SIĘ KOŃCU ZADAŃ

$ilosc_zadan = 0;
$sql = "SELECT ID, Email FROM users";
$que = $conn -> query($sql);
while($res = mysqli_fetch_array($que)){
$ilosc_zadan = 0;
$email_message = "
Oto Twoja dzisiejsza przypominajka:

";
	
	$user_id = $res["ID"];
	$user_email = $res["Email"];
	$user_jobs = array();
	$temp_sql = "SELECT * FROM job WHERE ForWho='$user_id' ORDER BY End DESC";
    $temp_que = $conn -> query($temp_sql);
    // W ZALEŻNOŚCI OD DŁUGOŚCI ZADANIA SĄ INNE WARUNKI CZY WYŚLE SIĘ ZADANIE
	while($temp_res = mysqli_fetch_array($temp_que)){
		$user_topic = $temp_res["Topic"];
        $days_left = checkdays($temp_res["End"]);
        $job_length = $temp_res["Length"];
		if($days_left < 3 && $job_length == 3){
			$user_jobs[$user_topic] = $days_left;
        }
        else if($days_left < 5 && $job_length == 2){
			$user_jobs[$user_topic] = $days_left;
        }
        else if($days_left < 7 && $job_length == 1){
			$user_jobs[$user_topic] = $days_left;
		}
	}
	foreach($user_jobs as $zadanie => $ile_dni){
		if($ile_dni>=0){
$email_message=$email_message."
$zadanie - $ile_dni dni do końca
";
$ilosc_zadan++;
		}
		else{
$email_message=$email_message."
$zadanie - spóźnione!
";
$ilosc_zadan++;
		}
    }
    
    // JEŻELI NIE MA ZADAŃ DO WYKONANIA TO NIE WYSYŁAJ MAILA
    if($ilosc_zadan<1){
        $user_email = "none";
    }

$email_message=$email_message."

Zaloguj się na plandeca.pl i sprawdź szczegóły!
Wygenerowano: ".date("Y-m-d G:i:s");
	
	mail($user_email, "[PLANDECA] Zadania $today", $email_message, "From: PlanDeca@aleksanderheese.pl");
}

// -----
/// WYSYŁANIE MAILI DO KADR Z INFORMACJĄ O DZISIEJSZYCH OBOWIĄZKACH

$hr_date = date("Y-m-d");
$hr_notedate = "2030-".date("m-d",strtotime("-1 days"));
if(date("Y",strtotime("-1 days"))=="2021")
    $hr_notedate = "2031-".date("m-d",strtotime("-1 days"));
$hr_message = "";
$hr_note = "";
$ilosc_zadan = 0;

$sql = "SELECT Info, InfoAdd FROM hr_tasks WHERE Deadline='$hr_notedate'";
$que = $conn -> query($sql);
while($res = mysqli_fetch_array($que)){
    $hr_note = $res["Info"];
    $hr_note_add = $res["InfoAdd"];
}

$hr_message.="
Notatka z wczoraj:
RANO:
$hr_note

POPO:
$hr_note_add

ZADANIA:";

$sql = "SELECT Info, Deadline FROM hr_tasks WHERE Deadline > '2020-08-01' AND Deadline <= '$hr_date' AND Completed='false'";
$que = $conn -> query($sql);
while($res = mysqli_fetch_array($que)){
    $hr_task = $res["Info"];
    $hr_task_date = $res["Deadline"];
    $hr_task_date_buffer = $hr_task_date[8].$hr_task_date[9].".".$hr_task_date[5].$hr_task_date[6].".".$hr_task_date[0].$hr_task_date[1].$hr_task_date[2];
    $hr_task_date = $hr_task_date_buffer;

$hr_message.="
- $hr_task_date - $hr_task";

$ilosc_zadan++;
}

$hr_message.="

Zaloguj się na plandeca.pl i sprawdź szczegóły!
Wygenerowano: ".date("Y-m-d G:i:s");

// WYŚLIJ JEŚLI SĄ ZADANIA I JEST NOTATKA
if($ilosc_zadan > 0 || $hr_note != ""){
    $sql = "SELECT Email FROM users WHERE Rola='kadr' OR ID='6'";
    $que = $conn -> query($sql);
    while($res = mysqli_fetch_array($que)){
        $mail = $res["Email"];

        mail($mail, "[PLANDECA] Zadania kadrowe $today", $hr_message, "From: PlanDeca@aleksanderheese.pl");
    }
}

/// ==========
// CZYSZCZENIE TABEL

$sql = "SELECT The_ID, Date FROM done";
$que = $conn -> query($sql);
while($res = mysqli_fetch_array($que)){
	$done_date = $res["Date"];
	$done_the_id = $res["The_ID"];
    
    $all_jobs_done = 1;
    $temp_sql = "SELECT ID FROM job WHERE The_ID='$done_the_id'";
    $temp_que = $conn -> query($temp_sql);
    while($temp_res = mysqli_fetch_array($temp_que)){
        $all_jobs_done = 0;
    }
    
	if($all_jobs_done==1){
		$temp_sql = "DELETE FROM done WHERE The_ID='$done_the_id'";
		$conn -> query($temp_sql);
		$temp_sql = "DELETE FROM job_red WHERE The_ID='$done_the_id'";
        $conn -> query($temp_sql);
        $temp_sql = "DELETE FROM chat WHERE The_ID='$done_the_id'";
		$conn -> query($temp_sql);
	}
}

// -----

$message = "ZAKOŃCZONO SKRYPT SAVE_SERVER - ".date("Y-m-d H:i:s");
echo $message;

// INFORMACJA DO MNIE NA MAILA, ŻE SAVE SERVER DOSZŁO POPRAWNIE I DO BAZY DANYCH

$sql = "INSERT INTO save_server(ID, Date) VALUES (NULL, '$today')";
$conn -> query($sql);

$sql = "DELETE FROM save_server WHERE Date!='$today'";
$conn -> query($sql);

mail("aleksander.heese@decathlon.com", "PlanDeca - skrypt save_server został wykonany", $message, "From: PlanDeca@aleksanderheese.pl");

// -----

} // Koniec if'a already

if($already == 1){
	echo "DZIŚ JUŻ WYKONANO SKRYPT!";
}

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