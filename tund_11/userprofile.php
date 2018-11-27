<?php
	require("functions.php");
	
	if(!isset($_SESSION["userId"])){
		header("Location: index_1.php");
		exit();
	}
	
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: index_1.php");
		exit();
	}
	
	$description = "";
	$bgcolor = "";
	$txtcolor = "";
	$notice = "";
	
	if(isset($_POST["submitProfile"])){
		if(isset($_POST["description"]) and isset($_POST["bgcolor"]) and isset($_POST["txtcolor"])){
			if(empty($_POST["description"])){
			 	$description = "Pole iseloomustust lisanud.";
			} else {
				$description = test_input($_POST["description"]);
			}
			$bgcolor = $_POST["bgcolor"];
			$txtcolor = $_POST["txtcolor"];
			$notice = saveprofile($description, $bgcolor, $txtcolor);
		}
	}

	// if(isset($_POST["submitProfile"])){

	// 	if(isset($_POST["description"])){
	// 		if(empty($_POST["description"])){
	// 			$description = "Pole iseloomustust lisanud.";
	// 		} else {
	// 			$description = test_input($_POST["description"]);
	// 		}
	// 	}

	// 	if(isset($_POST["bgcolor"])){
	// 		$bgcolor = $_POST["bgcolor"];
	// 	}

	// 	if(isset($_POST["txtcolor"])){
	// 		$txtcolor = $_POST["txtcolor"];
	// 	}

	// 	if(empty($descriptionerror)){
	// 		$notice = saveprofile($description, $bgcolor, $txtcolor);
	// 	}

	// }
	
	$target_dir = "../vp_userpic/";
	$uploadOk = 1;

	if(isset($_POST["submitImage"])) {
		
		if(!empty($_FILES["fileToUpload"]["name"])){
			
			//var_dump($_FILES["fileToUpload"]);
			
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));			
			$timestamp = microtime(1) * 10000;	

			$target_file_name = "vp_user_" .$timestamp .".jpg";
			$target_file = $target_dir ."vp_user_" .$timestamp .".jpg";
			
			//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				//echo "Fail on " . $check["mime"] . " pilt.";
				$uploadOk = 1;
			} else {
				$notice = "Fail ei ole pilt.";
				$uploadOk = 0;
			}
		
			if (file_exists($target_file)) {
				$notice = "Selle nimega fail on juba olemas.";
				$uploadOk = 0;
			}
			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 2500000) {
				$notice = "Fail on liiga suur.";
				$uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
				$notice = "Lubatud on ainult JPG, JPEG, PNG ja GIF failid.";
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				$notice .= " Valitud faili ei saa üles laadida.";
			// if everything is ok, try to upload file
			} else {
				
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
				}
				
				if($imageFileType == "png"){
					$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
				}
				
				if($imageFileType == "gif"){
					$myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
				}
				
				$imageWidth = imagesx($myTempImage);
				$imageHeigth = imagesy($myTempImage);
				
				// if($imageWidth > $imageHeigth){
				// 	$sizeRatio = $imageWidth / 600;
				// } else {
				// 	$sizeRatio = $imageHeigth / 400;
				// }
				
				// $newWidth = round($imageWidth / $sizeRatio);
				// $newHeigth= round($imageHeigth / $sizeRatio);
				
				$newWidth = 300;
				$newHeigth = 300;

				$myImage = resizeImage($myTempImage, $imageWidth, $imageHeigth, $newWidth, $newHeigth);
				
				if(imagejpeg($myImage, $target_file, 90)){
					$notice = "Fail ". basename( $_FILES["fileToUpload"]["name"]). " on üles laetud.";
					profilePic($target_file_name);
				} else {
					$notice = "Faili üleslaadimisel tekkis tehniline viga.";
				}
			
				
				
				imagedestroy($myTempImage);
				imagedestroy($myImage);
				
				/* if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					$notice = "Fail ". basename( $_FILES["fileToUpload"]["name"]). " on üles laetud.";
				} else {
					$notice = "Faili üleslaadimisel tekkis tehniline viga.";
				} */
			}
		}
	}
	
	function resizeImage($image, $ow, $oh, $w, $h){
		$newImage = imagecreatetruecolor($w, $h);
		imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $ow, $oh);
		return $newImage;
	}
	
	$data = userprofileload();
	$profilePic = loadProfilePic();
	$pagetitle = "Profiil";
	require("header.php");
?>


		<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Tere tulemast, <?php echo $_SESSION["firstName"] ." " .$_SESSION["lastName"] ."."; ?></p>
	<ul>
		<li><a href="main.php">Pealeht</a></li>	
	</ul>

	<img src="<?php echo $profilePic; ?>" alt="Profiilipilt"><br>

	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
		<label>Vali profiilipilt (300x300, kuni 2,5MB)</label><br>
		<input type="file" name="fileToUpload" id="fileToUpload"><br>
		<input type="submit" value="Lae üles" name="submitImage"><br><br>
	</form>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<textarea rows="10" cols="80" name="description"><?php echo $data[0]; ?></textarea><br>
		<label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $data[1]; ?>"><br>
		<label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $data[2]; ?>"><br>
		<input type="submit" name="submitProfile" value="Salvesta profiil">
	</form>
	
	<p><?php echo $notice; ?> </p>
	
	
	</body>
</html>