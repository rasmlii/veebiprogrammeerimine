<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $pagetitle; ?></title>

		<?php
			echo "<style>
				body{
					background-color: " .$data[1] ."; 
					color: " .$data[2] ."
				} 
			</style>";
		?>

	</head>
	<body>
	
		<div>
			<a href="main.php">
				<img src="../vp_picfiles/vp_logo_w135_h90.png" alt="VP logo">
			</a>
			<img src="../vp_picfiles/vp_banner.png" alt="VP banner">
		</div>
		
		<p><b>Sisselogitud kasutaja: </b><?php echo $_SESSION["firstName"] ." " .$_SESSION["lastName"]?></p>
	
		<h1><?php echo $pagetitle; ?></h1>