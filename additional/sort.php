<?php
    session_start();

    if(isset($_SESSION["sort"])){
		
		$sort=$_SESSION["sort"];
		
        if($sort=="Deadline"){
			$sort="Najstarsze";
		}
		
		else
			$sort="Deadline";
		
		$_SESSION["sort"]=$sort;
		
		echo "<script>location.reload();</script>";
    }
?>