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
				
				if($imageWidth > $imageHeigth){
					$sizeRatio = $imageWidth / 600;
				} else {
					$sizeRatio = $imageHeigth / 400;
				}
				
				$newWidth = round($imageWidth / $sizeRatio);
				$newHeigth= round($imageHeigth / $sizeRatio);
				
				$myImage = resizeImage($myTempImage, $imageWidth, $imageHeigth, $newWidth, $newHeigth);
				
				$watermark = imagecreatefrompng("../vp_picfiles/vp_logo_w100_overlay.png");
				$watermarkwidth = imagesx($watermark);
				$watermarkheigth = imagesy($watermark);
				$watermarkPosX = $newWidth - $watermarkwidth - 10;
				$watermarkPosY = $newHeigth - $watermarkheigth - 10;
				imagecopy($myImage, $watermark, $watermarkPosX, $watermarkPosY, 0, 0, $watermarkwidth, $watermarkheigth);
				
				$textToImage = "Veebiprogrammeerimine";
				$textColor = imagecolorallocatealpha($myImage, 255, 255, 255, 60);
				imagettftext($myImage, 20, 0, 10, 30, $textColor, "../vp_picfiles/ARIALBD.TTF", $textToImage);
				
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					if(imagejpeg($myImage, $target_file, 90)){
						$notice = "Fail ". basename( $_FILES["fileToUpload"]["name"]). " on üles laetud.";
						addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
					} else {
						$notice = "Faili üleslaadimisel tekkis tehniline viga.";
					}
				}
				
				if($imageFileType == "png"){
					if(imagepng($myImage, $target_file, 6)){
						$notice = "Fail ". basename( $_FILES["fileToUpload"]["name"]). " on üles laetud.";
						addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
					} else {
						$notice = "Faili üleslaadimisel tekkis tehniline viga.";
					}
				}
				
				if($imageFileType == "gif"){
					if(imagegif($myImage, $target_file)){
						$notice = "Fail ". basename( $_FILES["fileToUpload"]["name"]). " on üles laetud.";
						addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
					} else {
						$notice = "Faili üleslaadimisel tekkis tehniline viga.";
					}
				}
				
				imagedestroy($myTempImage);
				imagedestroy($myImage);
				imagedestroy($watermark);
				
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
	<p><?php echo $notice1; ?></p>
	
	</body>
</html>