<?php
	//laen andmebaasi info
	require("../../../config.php");
	//echo $GLOBALS["serverUsername"];
	
	$database = "if18_rasmus_li_1";
	
	//anonüümse sõnumi salvestamine
	function saveamsg($msg){
		$notice = "";
		
		//serveri ühendus (server, kasutaja, parool, andmebaas)
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		//valmistan ette SQL käsu
		$stmt = $mysqli->prepare("INSERT INTO vpamsg (message) VALUES(?)");
		echo $mysqli->error;
		
		//asendame SQL käsus küsimärgi päris infoga (andmetüüp, andmed ise)
		//s - string; i - integer; d - decimal
		$stmt->bind_param("s", $msg);
		if ($stmt->execute()){
			$notice = 'Sõnum "' .$msg .'" on salvestatud.';
		} else {
			$notice = "Sõnumi salvestamisel tekkis tõrge: " .$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function listallmessages(){
		$msgHTML = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT message FROM vpamsg");
		echo $mysqli->error;
		$stmt->bind_result($msg);
		$stmt->execute();	
		while($stmt->fetch()){
			$msgHTML .= "<p>" .$msg ."</p> \n";			
		}
		
		$stmt->close();
		$mysqli->close();
		return $msgHTML;
	}
	
	

	//tekstisisestuse kontroll
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	
?>