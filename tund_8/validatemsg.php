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
	
	$messages = readallunvalidatedmessages();
	$data = userprofileload();
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Anonüümsed sõnumid</title>

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
	<h1>Sõnumid</h1>
	<p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
	<hr>
	<ul>

		<li><a href="main.php">Tagasi</a> pealehele!</li>
		
	</ul>
	<hr>
	<h2>Valideerimata sõnumid</h2>
	<?php echo $messages; ?>


</body>
</html>