<?php
	//laen andmebaasi info
	require("../../../config.php");
	//echo $GLOBALS["serverUsername"];
	
	$database = "if18_rasmus_li_1";
	
	session_start();

	function listprivatephotos(){
		$html = "";

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy=3 AND deleted IS NULL AND userid=?");
		echo $mysqli->error;
		$stmt->bind_param("i", $_SESSION["userId"]);
		$stmt->bind_result($filenameFromDb, $alttextFromDb);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<img src="' .$GLOBALS["thumbDir"] .$filenameFromDb .'" alt="' .$alttextFromDb .'">' ."\n";
		}
		if(empty($html)){
			$html = "<p>Kahjuks privaatseid pilte ei ole.</p> \n";
		}
		
		$stmt->close();
		$mysqli->close();
		return $html;
	}
		
	function listpublicphotospage($privacy){
		$html = "";

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy<=? AND deleted IS NULL");
		echo $mysqli->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filenameFromDb, $alttextFromDb);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<img src="' .$GLOBALS["thumbDir"] .$filenameFromDb .'" alt="' .$alttextFromDb .'">' ."\n";
		}
		if(empty($html)){
			$html = "<p>Kahjuks avalikke pilte ei ole.</p> \n";
		}
		
		$stmt->close();
		$mysqli->close();
		return $html;
	}
	
	function listpublicphotos($privacy){
		$html = "";

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy<=? AND deleted IS NULL");
		echo $mysqli->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filenameFromDb, $alttextFromDb);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<img src="' .$GLOBALS["thumbDir"] .$filenameFromDb .'" alt="' .$alttextFromDb .'">' ."\n";
		}
		if(empty($html)){
			$html = "<p>Kahjuks avalikke pilte ei ole.</p> \n";
		}
		
		$stmt->close();
		$mysqli->close();
		return $html;
	}
		
	function latestPicture($privacy){
		$html = "";

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT filename, alttext FROM vpphotos WHERE id=(SELECT MAX(id) FROM vpphotos WHERE privacy=? AND deleted IS NULL)");
		echo $mysqli->error;
		
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filenameFromDb, $alttextFromDb);
		$stmt->execute();
		if($stmt->fetch()){
			$html = '<img src="' .$GLOBALS["picDir"] .$filenameFromDb .'" alt="' .$alttextFromDb .'">' ."\n";
		} else {
			$html = "<p>Kahjuks avalikke pilte ei ole.</p> \n";
		}
		
		
		$stmt->close();
		$mysqli->close();
		return $html;
	}
	
	
	function loadProfilePic(){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT filename FROM vpuserprofilepic WHERE userid=?");
		echo $mysqli->error;

		$stmt->bind_param("i", $_SESSION["userId"]);
		$stmt->bind_result($filename);
		$stmt->execute();

		if($stmt->fetch()){
			$profilePic = "../vp_userpic/" .$filename;
		}else{
			$profilePic = "../vp_picfiles/vp_user_generic.png";
		}

		$stmt->close();
		$mysqli->close();
		return $profilePic;
	}

	function profilePic($filename){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$noticeB = "";

		$stmt = $mysqli->prepare("SELECT filename FROM vpuserprofilepic WHERE userid=?");
		echo $mysqli->error;

		$stmt->bind_param("i", $_SESSION["userId"]);
		$stmt->execute();

		if($stmt->fetch()){
			$stmt->close();
			$stmt = $mysqli->prepare("UPDATE vpuserprofilepic SET filename=? WHERE userid=?");
			echo $mysqli->error;

			$stmt->bind_param("si", $filename, $_SESSION["userId"]);

			if($stmt->execute()){
				$noticeB = "Pilt on üles laetud.";
			} else {
				$noticeB = "Tekkis viga: " .$stmt->error;
			}

		} else {
			$stmt->close();

			$stmt = $mysqli->prepare("INSERT INTO vpuserprofilepic (userid, filename) VALUES (?, ?)");
			echo $mysqli->error;
			
			$stmt->bind_param("is", $_SESSION["userId"], $filename);
			
			if($stmt->execute()){
				$noticeB = "Pilt on üles laetud.";
			} else {
				$noticeB = "Tekkis viga: " .$stmt->error;
			}
		}
		
		$stmt->close();
		$mysqli->close();
		return $noticeB;
	}

	function addPhotoData($filename, $alttext, $privacy){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$notice1 = "";
		$stmt = $mysqli->prepare("INSERT INTO vpphotos (userid, filename, alttext, privacy) VALUES (?, ?, ?, ?)");
		echo $mysqli->error;
		
		if(empty($privacy)){
			$privacy = 3;
		}
		$stmt->bind_param("issi", $_SESSION["userId"], $filename, $alttext, $privacy);
		
		if($stmt->execute()){
			$notice1 = "Pilt on üles laetud.";
		} else {
			$notice1 = "Tekkis viga: " .$stmt->error;
		}
		
		$stmt->close();
		$mysqli->close();
		return $notice1;
	}
	
	
	function saveprofile($description, $bgcolor, $txtcolor){
		
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT description, bgcolor, txtcolor FROM vpuserprofiles WHERE user_id=?");
		echo $mysqli->error;

		$stmt->bind_param("i", $_SESSION["userId"]);
		$stmt->bind_result($descr, $bgcol, $txtcol);
		$stmt->execute();
		
		if($stmt->fetch()){
			
			$stmt->close();
			$stmt = $mysqli->prepare("UPDATE vpuserprofiles SET description=?, bgcolor=?, txtcolor=? WHERE user_id=?");
			echo $mysqli->error;
			$stmt->bind_param("sssi", $description, $bgcolor, $txtcolor, $_SESSION["userId"]);

			if($stmt->execute()){
				$notice = "Profiil salvestatud!";
			} else {
				$notice = "Tekkis viga: " .$stmt->error;
			}

		} else {
			
			$stmt->close();
			$stmt = $mysqli->prepare("INSERT INTO vpuserprofiles (user_id, description, bgcolor, txtcolor) VALUES (?, ?, ? , ?)");
			echo $mysqli->error;
			$stmt->bind_param("isss", $_SESSION["userId"], $description, $bgcolor, $txtcolor);

			if($stmt->execute()){
				$notice = "Profiil salvestatud!";
			} else {
				$notice = "Tekkis viga: " .$stmt->error;
			}

		}

		$stmt->close();
		$mysqli->close();
		return $notice;

	}

	function userprofileload(){
		$data = [];
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT description, bgcolor, txtcolor FROM vpuserprofiles WHERE user_id=?");
		echo $mysqli->error;
		
		$stmt->bind_param("i", $_SESSION["userId"]);
		$stmt->bind_result($descr, $bgcol, $txtcol);
		$stmt->execute();
		
		if($stmt->fetch()){
			$data = [$descr, $bgcol, $txtcol];
		}else{
			$descr = "Pole iseloomustust lisanud.";
			$bgcol = "#FFFFFF";
			$txtcol = "#000000";
			$data = [$descr, $bgcol, $txtcol];
			
		}
		
		$stmt->close();
		$mysqli->close();
		return $data;
	}
		
	function readallvalidatedmessagesbyuser(){
		$msghtml = "";
		$temphtml = "";
		$hasvalidated = 0;
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT id, firstname, lastname FROM vpusers");
		echo $mysqli->error;
		$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb);
		
		$stmt2 = $mysqli->prepare("SELECT message, accepted FROM vpamsg WHERE acceptedby=?");
		echo $mysqli->error;
		$stmt2->bind_param("i", $idFromDb);
		$stmt2->bind_result($msgFromDb, $acceptedFromDb);
		
		$stmt->execute();
		$stmt->store_result();
		while($stmt->fetch()){
			$temphtml .= "<h3>" .$firstnameFromDb ." " .$lastnameFromDb ."</h3> \n";
			$stmt2->execute();
			
			
		
		
			while($stmt2->fetch()){
				$hasvalidated .= 1;
				$temphtml .= "<p><b>";
				if($acceptedFromDb == 1){
					$temphtml .= "Lubatud: ";
				} else {
					$temphtml .= "Keelatud: ";
				}
				$temphtml .= "</b>" .$msgFromDb ."</p> \n";
				}
			if($hasvalidated > 0){
				$msghtml .= $temphtml;
				$hasvalidated = 0;
			}	
			$temphtml = "";
			
		
		}
		$stmt2->close();
		$stmt->close();
		$mysqli->close();
		return $msghtml;
	}
		
	function validatemsg($id, $validation){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("UPDATE vpamsg SET acceptedby=?, accepted=?, accepttime=now() WHERE id=?");
		echo $mysqli->error;
		$stmt->bind_param("iii", $_SESSION["userId"], $validation, $id);
		$stmt->execute();

		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function readmsgforvalidation($editId){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE id = ?");
		$stmt->bind_param("i", $editId);
		$stmt->bind_result($msg);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = $msg;
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}

	function allvalidmessages(){

		$notice = "<ul> \n";
		$accepted = 1;
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE accepted=? ORDER BY accepttime DESC");
		echo $mysqli->error;
		$stmt->bind_param("i", $accepted);
		$stmt->bind_result($msg);
		$stmt->execute();
		while($stmt->fetch()){
			$notice .= "<li>" .$msg ."</li> \n";
		}

		$notice .= "</ul> \n";
		
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function readallunvalidatedmessages(){
		$notice = "<ul> \n";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, message FROM vpamsg WHERE accepted IS NULL ORDER BY id DESC");
		echo $mysqli->error;
		$stmt->bind_result($id, $msg);
		$stmt->execute();
		
		while($stmt->fetch()){
			$notice .= "<li>" .$msg .'<br><a href="validatemessage.php?id=' .$id .'">Valideeri</a>' ."</li> \n";
		}
		
		$notice .= "</ul> \n";
		
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function signin($email, $password){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT id, firstname, lastname, password FROM vpusers WHERE email=?");
		$mysqli->error;
		$stmt->bind_param("s", $email);
		$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb, $passwordFromDb);
		if($stmt->execute()){
			
			if($stmt->fetch()){
				
				if(password_verify($password, $passwordFromDb)){
					$notice = "Sisselogimine õnnestus!";
					
					$_SESSION["userId"] = $idFromDb;
					$_SESSION["firstName"] = $firstnameFromDb;
					$_SESSION["lastName"] = $lastnameFromDb;
					
					$stmt->close();
					$mysqli->close();
					
					header("Location: main.php");
					exit();
					
				} else {
					$notice = "Sisestasite vale salasõna!";
				}
				
			} else {
				$notice = "Sellist kasutajat (" .$email .") ei leitud!";
			}
			
		} else {
			$notice = "Sisselogimisel tekkis tehniline viga: " .$stmt->error;
		}
		
		
		
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function userlist(){
		$notice = "<ul> \n";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT firstname, lastname, email FROM vpusers WHERE id !=?");
		echo $mysqli->error;
		$stmt->bind_param("i", $_SESSION["userId"]);
		$stmt->bind_result($firstname, $lastname, $email);
		$stmt->execute();
		
		while($stmt->fetch()){
			$notice .= "<li>" .$firstname ." " .$lastname .", " .$email ."</li> \n";
		}
		
		$notice .= "</ul> \n";
		
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function signup($firstName, $lastName, $birthDate, $gender, $email, $password){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT id FROM vpusers WHERE email=?");
		echo $mysqli->error;

		$stmt->bind_param("s", $email);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = "Selle emailiga kasutaja on juba registreeritud!";
			} else {
			//kasutajat pole, seega kogu salvestamine siia sisse
			$stmt->close();

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
			
		}
		
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