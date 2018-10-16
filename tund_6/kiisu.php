<?php
	require("functions.php");
	
	$tulemus = null;
	
	if (isset($_POST["submitCat"])){
		if (!empty($_POST["catName"]) and !empty($_POST["catColor"]) and !empty($_POST["catTail"])){
			$catname = test_input($_POST["catName"]);
			$catcolor = test_input($_POST["catColor"]);
			$cattail = test_input($_POST["catTail"]);
			$tulemus = addcat($catname, $catcolor, $cattail);
		} else {
			$tulemus = "Palun sisesta andmed!";
		}
	}
	
	
	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Kassi lisamine</title>
</head>
<body>
	<h1>Kassi lisamine</h1>
	<p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames, ei pruugi parim välja näha ning kindlasti ei sisalda tõsisevõetavat sisu.</p>
	<hr>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label>Kiisu nimi:</label>
		<input type="text" name="catName">
		<label>Kiisu värv:</label>
		<input type="text" name="catColor">
		<label>Saba pikkus:</label>
		<input type="number" name="catTail">

		

		<br>
		<input type="submit" name="submitCat" value="Saada andmed">
	</form>
	<hr>
	
	<p><?php echo $tulemus; ?></p>
	
	
	
	
	
	

	
	
	
</body>
</html>