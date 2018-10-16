<?php
	//laen andmebaasi info
	require("../../../config.php");
	//echo $GLOBALS["serverUsername"];
	
	$database = "if18_rasmus_li_1";
	
	function signup($firstName, $lastName, $birthDate, $gender, $email, $password){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		// $stmt = $mysqli->prepare("SELECT id FROM vpusers WHERE email=$email");
		// echo $mysqli->error;

		// $stmt->bind_param("s", $email);
		// $stmt->execute();
		// if($stmt->fetch()){
		// 	$notice = "Selle emailiga kasutaja on juba registreeritud!";
		//   } else {
		// 	//kasutajat pole, seega kogu salvestamine siia sisse
		

			$stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
			echo $mysqli->error;
			//hash to encrypt
			
			$options = [
				"cost" => 12,
				"salt" => substr(sha1(rand()), 0, 22),];
			
			$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
			$stmt->bind_param("sssiss", $firstName, $lastName, $birthDate, $gender, $email, $pwdhash);
			if($stmt->execute()){
				$notice = "Uue kasutaja lisamine õnnestus!";
			} else {
				$notice = "Kasutaja lisamisel tekkis viga: " .$stmt->error;
			}
			
		//}
		
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	
	
	function addcat($catname, $catcolor, $cattail){

		$tulemus = "";

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO kiisu (nimi, v2rv, saba) VALUES(?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("ssi", $catname, $catcolor, $cattail);
		if ($stmt->execute()){
			$tulemus = 'Kass on salvestatud järgmiste andmetega: ' .$catname .', ' .$catcolor .', ' .$cattail;
		} else {
			$tulemus = "Kassi salvestamisel tekkis tõrge: " .$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
		return $tulemus;
	}
	
	
	
	
	
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