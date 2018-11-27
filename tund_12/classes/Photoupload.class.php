<?php

	class Photoupload
	{
		private $tempName;
		private $imageFileType;
		public $imageSize;
		public $fileName;
		private $myTempImage;
		private $myImage;
		public $errorsForUpload;
		private $uploadOk;
		public $photoDate;
		
		function __construct($tmpPic){
			$this->tempName = $tmpPic["tmp_name"];
			$this->imageFileType = strtolower(pathinfo($tmpPic["name"], PATHINFO_EXTENSION));
			$this->imageSize = $tmpPic["size"];
			$this->createImageFromFile();
			$this->uploadOk = 1;
		}

		function __destruct(){
			imagedestroy($this->myTempImage);
			imagedestroy($this->myImage);
		}
		
		private function createImageFromFile(){
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				$this->myTempImage = imagecreatefromjpeg($this->tempName);
			}
			
			if($this->imageFileType == "png"){
				$this->myTempImage = imagecreatefrompng($this->tempName);
			}
			
			if($this->imageFileType == "gif"){
				$this->myTempImage = imagecreatefromgif($this->tempName);
			}
		}
		
		public function readExif(){
			//vältimaks probleeme (warning), kasutan @ märki
			@$exif = exif_read_data($this->tempName, "ANY_TAG", 0, true);
			echo $exif["DateTimeOriginal"];
		}
		
		public function makeFileName($prefix){
			$timeStamp = microtime(1) * 10000;
			$this->fileName = $prefix .$timeStamp ."." .$this->imageFileType;
		}
	
		public function checkForImage(){
			$this->errorsForUpload = "";
			$check = getimagesize($this->tempName);
			if($check == false){
				$this->errorsForUpload .= "Fail ei ole pilt.";
				$this->uploadOk = 0;
			}
			return $this->uploadOk;
		}
		
		public function checkForFileSize($size){
			// faili suurus
			if ($this->imageSize > $size) {
			$this->errorsForUpload .= " Kahjuks on fail liiga suur!";
			$this->uploadOk = 0;
			}
			return $this->uploadOk;
		}
		
		public function checkForFileType(){
			// kindlad failitüübid
			if($this->imageFileType != "jpg" && $this->imageFileType != "png" && $this->imageFileType != "jpeg" && $this->imageFileType != "gif" ) {
				$this->errorsForUpload ." Kahjuks on lubatud vaid JPG, JPEG, PNG ja GIF failid!";
				$uploadOk = 0;
			}
			return $this->uploadOk;		
		}
		
		public function checkIfExists($target){
			// kas on juba olemas
			if (file_exists($target)) {
			  $this->errorsForUpload .= "Kahjuks on selline pilt juba olemas!";
			  $this->uploadOk = 0;
			}
			return $this->uploadOk;
		}
		
		public function changePhotoSize($width, $height){
			$imageWidth = imagesx($this->myTempImage);
			$imageHeight = imagesy($this->myTempImage);
			
			if($imageWidth > $imageHeight){
				$sizeRatio = $imageWidth / $width;
			} else {
				$sizeRatio = $imageHeight / $height;
			}
			
			$newWidth = round($imageWidth / $sizeRatio);
			$newHeigth= round($imageHeight / $sizeRatio);
			
			$this->myImage = $this->resizeImage($this->myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeigth);
		}
		
		private function resizeImage($image, $ow, $oh, $w, $h){
			$newImage = imagecreatetruecolor($w, $h);
			imagesavealpha($newImage, true);
			$transcolor = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
			imagefill($newImage, 0, 0, $transcolor);
			imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $ow, $oh);
			return $newImage;
		}
		
		public function addWatermark($pathToWatermark){
			$watermark = imagecreatefrompng($pathToWatermark);
			$watermarkwidth = imagesx($watermark);
			$watermarkheigth = imagesy($watermark);
			$watermarkPosX = imagesx($this->myImage) - $watermarkwidth - 10;
			$watermarkPosY = imagesy($this->myImage) - $watermarkheigth - 10;
			imagecopy($this->myImage, $watermark, $watermarkPosX, $watermarkPosY, 0, 0, $watermarkwidth, $watermarkheigth);
		}
		
		public function addTextToImage($textToImage){
			$textColor = imagecolorallocatealpha($this->myImage, 255, 255, 255, 60);
			imagettftext($this->myImage, 20, 0, 10, 25, $textColor, "../vp_picfiles/ARIALBD.TTF", $textToImage);
		}
		
		public function createThumbnail($directory, $size){
			$imageWidth = imagesx($this->myTempImage);
			$imageHeight = imagesy($this->myTempImage);
			if($imageWidth > $imageHeight){
				$cutSize = $imageHeight;
				$cutX = round(($imageWidth - $cutSize) / 2);
				$cutY = 0;
			} else {
				$cutSize = $imageWidth;
				$cutX = 0;
				$cutY = round(($imageHeight - $cutSize) / 2);
			}
			
			$myThumbnail = imagecreatetruecolor($size, $size);
			imagesavealpha($myThumbnail, true);
			$transcolor = imagecolorallocatealpha($myThumbnail, 0, 0, 0, 127);
			imagefill($myThumbnail, 0, 0, $transcolor);
			imagecopyresampled($myThumbnail, $this->myTempImage, 0, 0, $cutX, $cutY, $size, $size, $cutSize, $cutSize);
			$target_file = $directory .$this->fileName;
			
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				imagejpeg($myThumbnail, $target_file, 90);
			}
			
			if($this->imageFileType == "png"){
				imagepng($myThumbnail, $target_file, 6);
			}
			
			if($this->imageFileType == "gif"){
				imagegif($myThumbnail, $target_file);
			}
			
		}
		
		public function savePhoto($target_file){
			$notice = "";
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				if(imagejpeg($this->myImage, $target_file, 90)){
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			
			if($this->imageFileType == "png"){
				if(imagepng($this->myImage, $target_file, 6)){
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			
			if($this->imageFileType == "gif"){
				if(imagegif($this->myImage, $target_file)){
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			
			return $notice;
		}
	}

?>


















