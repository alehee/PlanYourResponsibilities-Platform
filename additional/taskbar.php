<?php
require_once("func.php");

$id = $_SESSION["id"];
$conn = connect();

$conn -> query("SET CHARSET utf8");
$conn -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

$taskbar='
<div id="task_background" onclick="task_hide()">
<div id="task" onclick="task_hidenot()">
<div id="task_header">LISTA ZADAŃ</div>
<div id="task_add" onclick="task_add()">DODAJ ZADANIE +</div>';

$task_num = 0;
$sql = "SELECT Info FROM task WHERE WhoAdd='$id' ORDER BY ID DESC";
$que = $conn -> query($sql);
$task_num = mysqli_num_rows($que);
while($res = mysqli_fetch_array($que)){
	$task_info = $res["Info"];
	$taskbar=$taskbar."<div class='task_job' id='task_job_$task_num' onclick='task_getinfo($task_num)' onchange='task_change($task_num)'><textarea data-autoresize class='task_job_textarea' id='task_$task_num' style='width:90%; border:none; padding:2px; background-color:#f2f2f2; resize:none;' rows='1' spellcheck='false'>$task_info</textarea><button class='task_job_end_button' id='task_butt_$task_num' onclick='task_done($task_num)'>x</button></div>";
	$task_num--;
}

$taskbar=$taskbar.'</div>
</div>
';
?>