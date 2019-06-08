<?php
include_once("../auth.php");
if(!file_exists("data")){
	mkdir("data",0777,true);
}
if (isset($_GET['fp']) && isset($_GET['rid'])){
	$rid = $_GET['rid'];
	$rid = explode(",",$rid)[0];
	file_put_contents("data/" . $rid . ".inf","fopen," . $_GET['fp']);
	echo "DONE";
	exit(0);
}

function check_file_is_audio( $tmp ) 
{
    $allowed = array(
        'audio/mpeg', 'audio/x-mpeg', 'audio/mpeg3', 'audio/x-mpeg-3', 'audio/aiff', 
        'audio/mid', 'audio/x-aiff', 'audio/x-flac', 'audio/x-mpequrl','audio/midi', 'audio/x-mid', 
        'audio/x-midi','audio/wav','audio/x-wav','audio/xm','audio/x-aac','audio/basic',
        'audio/flac','audio/mp4','audio/x-matroska','audio/ogg','audio/s3m','audio/x-ms-wax',
        'audio/xm', 'image/jpeg', 'video/mp4'
    );
    
    // check REAL MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $type = finfo_file($finfo, $tmp );
    finfo_close($finfo);
    
    // check to see if REAL MIME type is inside $allowed array
    if( in_array($type, $allowed) ) {
        return true;
    } else {
        return false;
    }
}

//Check if the file exists and it is audio file.
$valid = true;
if(isset($_GET['filepath']) && file_exists($_GET['filepath'])){
	//This file exists.
	$filename = $_GET['filepath'];
}else{
	$valid = false;
}

if (isset($_GET['filename'])){
	$displayName =  $_GET['filename'];
}else{
	$displayName =  basename($filename);
}
if (!check_file_is_audio($_GET['filepath'])){
	//This is not an audio file
	$valid = false;
}
if(!$valid){
	die("Error. There are problems with the selected files.");
}
?>
<html>
<head>
	<link rel="stylesheet" href="../script/tocas/tocas.css">
	<script src="../script/tocas/tocas.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<script src="../script/jquery.min.js"></script>
	<script src="../script/ao_module.js"></script>
	<link rel="manifest" href="manifest.json">
	<style>
		body{
			background-color:#0c0c0c;
			color:white;
		}
		.white{
			color:white !important;
		}
	</style>
	
</head>
<body>
<br>
<div class="ts container" style="color:white;">
	<h5 class="ts header">
		<i class="white feed icon"></i>
		<div class="white content">
			Send to RemotePlay
			<div class="white sub header">Request Remote Device to play a file</div>
		</div>
	</h5>
	<hr>
	<p class="white">Target RemotePlay ID</p>
	<div class="ts basic mini fluid input">
		<select class="ts basic dropdown" id="remoteID" style="background: black;color: white;width: 100%">
			<option>Scanning...</option>
		</select>
	</div>
	<br><p class="white">Filename</p>
	<div class="ts basic mini fluid input">
		<input id="filename" class="white" type="text" value="<?php echo $displayName;?>" readonly=true>
	</div>
	<br><p class="white">Target Filepath</p>
	<div class="ts basic mini fluid input">
		<input id="filepath" class="white" type="text" value="<?php echo $filename;?>" readonly=true>
	</div>
	<br><br>
	<div align="right">
		<button class="ts basic white mini button" onClick="createRequest();">Send</button>
	</div>
</div>
	<script>
	$(document).ready(function(){
		$.get("opr.php?opr=scanalive",function(data){
				var obj = JSON.parse(data);
				$("#remoteID").html("");
				$("#remoteID").append($("<option></option>").attr("value", "").text("Not selected"));
				$.each( obj, function( key, value ) {
					$("#remoteID").append($("<option></option>").attr("value", value).text(value));
				});
				$("#remoteID").val("");
				var previousRemoteID = ao_module_getStorage("remoteplay","remoteID");
				if (previousRemoteID !== undefined && $("#remoteID option[value='" + previousRemoteID + "']").length > 0){
					$("#remoteID").val(previousRemoteID);
				}
			});
	});
	var rid = $("#rid").text().trim();
	ao_module_setWindowSize(385,420);
	ao_module_setWindowTitle("Send to RemotePlay");
	ao_module_setWindowIcon("feed");
	
	$("#remoteID").on("change",function(){
		ao_module_saveStorage("remoteplay","remoteID",$(this).val());
	});
	
	$("#remoteID").on("keydown",function(e){
		if (e.keyCode == 13){
			//Enter is pressed
			createRequest();
		}
	});
	
	function createRequest(){
		var filepath = $("#filepath").val();
		var remoteID = $("#remoteID").val();
		$.get("embedded.php?fp=" + filepath + "&rid=" + remoteID,function(data){
			if (data.includes("ERROR") == false){
				ao_module_close();
			}
		});
	}
	</script>
</body>
</html>