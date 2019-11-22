<?php
session_start();
require_once("../connection.php");
$conn = @new mysqli($host, $user_db, $password_db, $db_name);

$conn -> query("SET CHARSET utf8");
$conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

// WYSYŁANIE WIADOMOŚCI DO BAZY DANYCH
if(isset($_GET['message'])){

	$is_clear=0;
	$temp = $_GET['message'];

	$chat_sentfrom = $_SESSION['id'];
	$chat_the_id="";
	$chat_message="";
	
	// ROZKODOWUJE WIADOMOŚĆ (~ oznacza koniec ID i początek wiadomości)
	for($i=0; $i<strlen($temp); $i++){
		if($temp[$i]=='~')
			$is_clear=2;
		else if($is_clear==0){
			$chat_the_id=$chat_the_id.$temp[$i];
		}
		else if($is_clear==1){
			$chat_message=$chat_message.$temp[$i];
		}
		
		if($is_clear==2)
			$is_clear=1;
	}
	
	if($chat_message!=""){
		$sql = "INSERT INTO chat(ID, The_ID, SentFrom, Message, Date) VALUES (NULL, '$chat_the_id', '$chat_sentfrom', '$chat_message', CURRENT_TIMESTAMP)";
		$que = $conn -> query($sql);
	}

    unset($_GET['message']);
	$conn ->close();
    header("location:../user.php");
}

else{
	$conn ->close();
    header("location:../user.php");
}

?>