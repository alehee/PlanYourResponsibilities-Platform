<?php
// TEN PLIK POWINIEN BYĆ WYKONYWANY RAZ DZIENNIE NA KOMPUTERZE, ABY ZAKTUALIZOWAĆ ZADANIA ORAZ WYCZYŚCIĆ TABELĘ

$conn = @new mysqli("riverlakestudios.pl", "30908302_pyr", "rvrlkPYR_", "30908302_pyr");

$conn -> query("SET CHARSET utf8");
$conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

$already = 0;
$today = date("Y-m-d");
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
// WYSYŁANIE MAILI INFORMUJĄCYH O ZBLIŻAJĄCYM SIĘ KOŃCU ZADAŃ

$sql = "SELECT ID, Email FROM users";
$que = $conn -> query($sql);
while($res = mysqli_fetch_array($que)){
$email_message = "
Oto Twoja dzisiejsza przypominajka z platformy PYR:

";
	
	$user_id = $res["ID"];
	$user_email = $res["Email"];
	$user_jobs = array();
	$temp_sql = "SELECT * FROM job WHERE ForWho='$user_id'";
	$temp_que = $conn -> query($temp_sql);
	while($temp_res = mysqli_fetch_array($temp_que)){
		$user_topic = $temp_res["Topic"];
		$days_left = checkdays($temp_res["End"]);
		if($days_left < 3){
			$user_jobs[$user_topic] = $days_left;
		}
	}
	foreach($user_jobs as $zadanie => $ile_dni){
		if($ile_dni>=0){
$email_message=$email_message."
$zadanie - $ile_dni dni do końca
";
		}
		else{
$email_message=$email_message."
$zadanie - spóźnione!
";
		}
	}
$email_message=$email_message."

Zaloguj się na riverlakestudios.pl/pyr i sprawdź szczegóły!
Wygenerowano: ".date("Y-m-d G:i:s");
	
	mail($user_email, "PYR - przypominajka!", $email_message, "From: PYR@riverlakestudios.pl");
}

// -----
// CZYSZCZENIE TABEL

$sql = "SELECT The_ID, Date, ForWho FROM done";
$que = $conn -> query($sql);
while($res = mysqli_fetch_array($que)){
	$done_date = $res["Date"];
	$done_the_id = $res["The_ID"];
	$done_forwho = $res["ForWho"];
	
	$done_difference = checkdays($done_date);
	if($done_difference<-7){
		$temp_sql = "DELETE FROM done WHERE The_ID='$done_the_id' AND ForWho='$done_forwho'";
		$conn -> query($temp_sql);
		$temp_sql = "DELETE FROM job_red WHERE The_ID='$done_the_id' AND ForWho='$done_forwho'";
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

mail("aleksander.heese@decathlon.com", "PYR - skrypt save_server został wykonany", $message, "From: PYR@riverlakestudios.pl");

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