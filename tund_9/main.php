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

	$data = userprofileload();
	$pagetitle = "Pealeht";
	require("header.php");
?>


		<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Tere tulemast, <?php echo $_SESSION["firstName"] ." " .$_SESSION["lastName"] ."."; ?></p>
	<ul>
		<li><a href="userprofile.php">Profiil</a></li>
		<li><a href="validatemsg.php">Valideeri anonüümseid sõnumeid</a></li>
		<li><a href="users.php">Kasutajad</a></li>
		<li>Näita valideeritud <a href="validatedmessages.php">sõnumeid</a> valideerijate kaupa</li>
		<li>Fotode <a href="photoupload.php">üleslaadimine</a></li>
		
		<li><a href="?logout=1">Logi välja</a></li>
	</ul>
	
	</body>
</html>