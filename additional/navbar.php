<?php
require_once("func.php");

$id = $_SESSION["id"];

$navbar='
<div id="nav_background" onclick="nav_hide()">
<div id="nav" onclick="nav_hidenot()">
	<div id="nav_profile">
		<img src="photo/'.$id.'.png"/>
		<p style="padding: 5px;">'.name_by_id($id).'</p>
		<div style="width:100%; height:5px; background-color:#ec6607;"></div>
	</div>
	<div id="nav_link_header">ZADANIA</div>
	<div id="nav_link" onclick=\'nav_classic_link("main.php")\'><span>PANEL GŁÓWNY</span></div>
	<div id="nav_link" onclick=\'nav_classic_link("user.php")\'><span>ZADANIA OGÓLNE</span></div>
	<div id="nav_link" onclick=\'nav_classic_link("user_ri.php")\'><span>ZADANIA RI</span></div>
	<div id="nav_link" onclick=\'nav_classic_link("user_staff.php")\'><span>ZADANIA KADROWE</span></div>
	<div id="nav_link" onclick=\'nav_classic_link("project.php")\'><span>PANEL PROJEKTÓW</span></div>
	<div id="nav_link_header">LINKI</div>
	<div id="nav_link" onclick=\'nav_link("http:\/\/mail.oxylane.com")\'>GMAIL</div>';

	if($_SESSION["rola"]=="kier")
		$navbar = $navbar.'<div id="nav_link" onclick=\'nav_link("https:\/\/sites.google.com\/decathlon.com\/menadzer-car-gliwice\/")\'>TECZKA MENADŻERA</div>';

	$navbar = $navbar.'<div id="nav_link" onclick=\'nav_link("https:\/\/sites.google.com\/decathlon.com\/cargliwice\/strona-g%C5%82%C3%B3wna?authuser=0")\'>CAR GLIWICE</div>
	<div id="nav_link_header">KONTO</div>
	<div id="nav_link" onclick=\'nav_classic_link("logout.php")\'><span>WYLOGUJ</span></div>
	<div id="nav_link" onclick=\'nav_classic_link("profile.php")\'><span>MÓJ PROFIL</span></div>';

	$is_su=0;
	$conn = connect();
	$sql = "SELECT ID FROM susers WHERE User_ID='$id'";
	$que = $conn -> query($sql);
	while($res = mysqli_fetch_array($que))
		$is_su=1;

	if($is_su == 1){
		$navbar = $navbar.'<div id="nav_link" onclick=\'nav_classic_link("stats.php")\'><span>STATYSTYKI</span></div>';
	}

	$navbar = $navbar.'<div id="nav_link" onclick=\'nav_classic_link("create-account.php")\'><span>DODAJ NOWĄ OSOBĘ</span></div>
	<div id="nav_link" onclick=\'nav_classic_link("report.php")\'><span>ZGŁOŚ USTERKĘ</span></div>
</div>
</div>
';

?>