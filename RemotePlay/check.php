<?php
include_once("../auth.php");
if (isset($_GET['rid'])){
	if (file_exists("data/" . $_GET['rid'] . ".inf")){
		$data = file_get_contents("data/" . $_GET['rid'] . ".inf");
		$data = explode(",",$data);
		header('Content-Type: application/json');
		echo json_encode([true,$data]);
		unlink("data/" . $_GET['rid'] . ".inf");
	}else{
		header('Content-Type: application/json');
		echo json_encode([false,""]);
	}
}else{
	echo "ERROR. rid not given.";
}

?>