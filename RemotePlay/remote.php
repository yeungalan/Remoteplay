<?php
include_once '../auth.php';
if(!file_exists("data")){
	mkdir("data",0777,true);
}
if (isset($_GET['comm']) && isset($_GET['rid'])){
	$rid = $_GET['rid'];
	$rid = explode(",",$rid)[0];
	file_put_contents("data/" . $rid . ".inf",$_GET['comm']);
	echo "DONE";
	exit(0);
}
?>
<html>
<head>
    <link rel="stylesheet" href="../script/tocas/tocas.css">
	<script src="../script/tocas/tocas.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<script src="../script/jquery.min.js"></script>
	<script src="../script/ao_module.js"></script>
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
<div class="ts container">
<div class="ts white header">
    <i class="options icon"></i>RemotePlay Remote
    <div class="sub white header">Control your remote player here!</div>
</div>
	<p class="white">Target RemotePlay ID</p>
	<div class="ts basic mini fluid input">
		<input id="remoteID" class="white" type="text">
	</div>
	<p class="white">Volume Control (Min <--> Max)</p>
	<div class="ts slider">
		<input id="vol" type="range" min="0" max="1" step="0.05" value="0">
	</div>
	<br>
	<div class="ts separated mini buttons">
		<button class="ts basic white button" onClick="play();"><i class="play icon"></i>Play</button>
		<button class="ts basic white button" onClick="pause();"><i class="pause icon"></i>Pause</button>
		<button class="ts basic white button" onClick="bwd();"><i class="play icon"></i>Back forward</button>
		<button class="ts basic white button" onClick="fwd();"><i class="play icon"></i>Fast forward</button>
		<button class="ts basic white button" onClick="speedincrease();"><i class="play icon"></i>Speed increase</button>
		<button class="ts basic white button" onClick="stop();"><i class="stop icon"></i>Stop</button>
		<button class="ts basic white button" onClick="mute();"><i class="volume off icon"></i>Mute</button>
		<button class="ts basic white button" onClick="reset();"><i class="stop icon"></i>Reset</button>
	</div>
</div>
<script>
var rid = "";
ao_module_setWindowSize(500,320);
$("#vol").on("change",function(){
	sendCommand("setVol",$(this).val());
});

$("#remoteID").on("change",function(){
	ao_module_saveStorage("remoteplay","remoteID",$(this).val());
	rid = $(this).val();
});

function play(){
	sendCommand("play","");
}

function pause(){
	sendCommand("pause","");
}

function fwd(){
	sendCommand("fwd","");
}

var speedincreaseing = false;
function speedincrease(){
	if(speedincreaseing){
		clearInterval(timer_1);
		speedincreaseing = false;
	}else{
	  timer_1 = setInterval(fwd, 1000);
	  speedincreaseing = true;
	}
}

function bwd(){
	sendCommand("bwd","");
}

function stop(){
	sendCommand("stop","");
}

function mute(){
	sendCommand("setVol","0");
	$("#vol").val(0);
}

function reset(){
	sendCommand("reset","");
}

function sendCommand(comm,value){
	var fullcomm = comm + "," + value;
	$.get("remote.php?comm=" + fullcomm + "&rid=" + rid,function(data){
		if (data.includes("ERROR")){
			
		}
	});
}

$(document).ready(function(){
	var previousRemoteID = ao_module_getStorage("remoteplay","remoteID");
	if (previousRemoteID !== undefined){
		$("#remoteID").val(previousRemoteID);
		rid = previousRemoteID;
	}
});
</script>
</body>
</html>