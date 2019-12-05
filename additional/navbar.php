<?php
require_once("func.php");

$id = $_SESSION["id"];

$navbar='
<div id="nav_background" onclick="nav_hide()">
<div id="nav" onclick="nav_hidenot()">
	<div id="nav_profile">
		<img src="photo/'.$id.'.png"/>
		<p style="color:white; padding: 5px;">'.name_by_id($id).'</p>
	</div>
	<div id="nav_link" onclick=\'nav_classic_link("user.php")\'><span>PANEL GŁÓWNY</span></div>
	<div id="nav_link" onclick=\'nav_link("http:\/\/mail.oxylane.com")\'>GMAIL</div>
	<div id="nav_link" onclick=\'nav_link("http:\/\/riverlakestudios.pl")\'>LINK 1</div>
	<div id="nav_link" onclick=\'nav_link("http:\/\/wp.pl")\'>LINK 2</div>
	<div id="nav_link" onclick=\'nav_link("http:\/\/lowcygier.pl")\'>LINK 3</div>
	<div id="nav_link" onclick=\'nav_link("http:\/\/drive.google.com")\'>LINK 4</div>
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