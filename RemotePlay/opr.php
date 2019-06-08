<?php
if (isset($_GET['opr'])){
	if($_GET["opr"] == "scanalive"){
		$output = [];
		$file = glob('data/*.alive');
		foreach($file as $alive){
			array_push($output,basename($alive,".alive"));
		}
		echo json_encode($output);
		
	}else if($_GET["opr"] == "mime"){
		
		echo explode("/",mime_content_type($_GET["file"]))[0];
		
	}else{
		
		echo "[]";
		
	}
	
}else{
	echo "[]";
}