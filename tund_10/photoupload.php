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
	
	require("classes/Photoupload.class.php");
	
	/*
	require("classes/Test.class.php");
	$myTest = new Test(4);
	$mySecondaryTest = new Test(7);
	echo "Teine avalik number on " .$mySecondaryTest->publicNumber ."! ";
	echo $myTest->publicNumber;
	$myTest->tellInfo();
	unset($myTest);
	*/
	
	$target_dir = "../vp_pic_uploads/";
	$uploadOk = 1;
	$notice = "";
	$notice1 = "";
	
	// Check if image file is a actual image or fake image
	if(isset($_POST["submitImage"])) {
		
		if(!empty($_FILES["fileToUpload"]["name"])){
			
			//var_dump($_FILES["fileToUpload"]);
			
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));			
			$timestamp = microtime(1) * 10000;	

			$target_file_name = "vp_" .$timestamp ."." .$imageFileType;
			$target_file = $target_dir ."vp_" .$timestamp ."." .$imageFileType;
			
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
				$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
				$myPhoto->changePhotoSize(400, 600);
				$myPhoto->addWatermark();
				$myPhoto->addTextToImage();
				$myPhoto->savePhoto($target_file);
				unset($myPhoto);
				
				if($notice == 1){
					addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
				}
			}
		}
	}
	
	$data = userprofileload();
	$pagetitle = "Fotode üleslaadimine";
	require("header.php");
	
	
?>


		<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>

	<ul>
		<li><a href="main.php">Pealeht</a></li>
		<li><a href="?logout=1">Logi välja</a></li>
	</ul>
	
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
		<label>Vali üleslaetav pildifail (kuni 2,5MB)</label><br>
		<input type="file" name="fileToUpload" id="fileToUpload">
		<br>
		<br>
		<label>Alt tekst: </label>
		<input type="text" name="altText">
		<br>
		<br>
		<label>Pildi privaatsus:</label>
		<br>
		<input type="radio" name="privacy" value="1"><label>Avalik</label>
		<input type="radio" name="privacy" value="2"><label>Ainult Sisselogitud kasutajad</label>
		<input type="radio" name="privacy" value="3" checked><label>Privaatne</label>
		<br>
		<input type="submit" value="Lae üles" name="submitImage">
	</form>
	<p>
	<?php
		
		/*
		if($notice != 1 and $notice != 0){
			echo $notice;
		} else {
			if($notice == 0){
				echo "Faili üleslaadimisel tekkis tehniline viga.";
			}
			
			if($notice == 1){
				echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " on üles laetud.";
			}
		}
		*/
	
		echo $notice;
	
	
	
	
	
	
	
	
	
	
	
	
	echo $notice1; ?></p>
	
	</body>
</html>