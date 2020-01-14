<?php
session_start();
require_once("../connection.php");

if(isset($_POST["imie"])){
	
	$conn = @new mysqli($host, $user_db, $password_db, $db_name);

	mysqli_query($conn, "SET CHARSET utf8");
    mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
	
	$imie = $_POST["imie"];
	$nazwisko = $_POST["nazwisko"];
	$login = $_POST["login"];
	$password = $_POST["password"];
	$email = $_POST["email"];
	$dzial = $_POST["dzial"];
	$city = $_SESSION["city"];

	$is_ok=1;

	$sql = "SELECT ID FROM users WHERE Login='$login'";
	$que = $conn -> query($sql);
	while($res = mysqli_fetch_array($que)){
		$_SESSION["error"]="Użytkownik o podanym loginie już istnieje!";
		$is_ok=0;
	}

	if($is_ok==1){
		$sql = "INSERT INTO users(ID, Login, Password, Imie, Nazwisko, Dzial, Email, Jednostka, Activity, Spoznien) VALUES(NULL, '$login', '$password', '$imie', '$nazwisko', '$dzial', '$email', '$city', CURRENT_TIMESTAMP, '0')";
		$conn -> query($sql);

		$user_id;
		$sql = "SELECT ID FROM users WHERE Login='$login'";
		$que = $conn -> query($sql);
		while($res = mysqli_fetch_array($que)){
			$user_id=$res["ID"];
		}

		if(isset($_FILES['photo'])){
			$plik_tmp = $_FILES['photo']['tmp_name'];
			if(is_uploaded_file($plik_tmp)){
				//move_uploaded_file($plik_tmp, "../photo/".$user_id.".png");
				imagepng(imagecreatefromstring(file_get_contents($plik_tmp)), "../photo/".$user_id.".png");
			}
			else{
				copy("../photo/default/default.png", "../photo/$user_id.png");
			}
		}
	}

	$conn -> close();

	unset($_POST["imie"]);
	unset($_POST["nazwisko"]);
	unset($_POST["login"]);
	unset($_POST["password"]);
	unset($_POST["email"]);
	unset($_FILES["photo"]);
	unset($_POST["dzial"]);
	
    header("location:../user.php");
}

else
    header("location:../user.php");

?>