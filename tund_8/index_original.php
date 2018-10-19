<?php
	//echo "See on minu esimene PHP!";
	$firstName = "Rasmus";
	$lastName = "Liiv";
	$dayToday = date("d");
	$monthToday = date("m");
	$monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	$yearToday = date("Y");
	$weekdayNow = date("N");
	$weekdayNamesET = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	//echo $weekdayNamesET[1];
	//var_dump($weekdayNamesET);
	//echo $weekdayNow;
	$hourNow = date("G");
	$partOfDay = "";
	if ($hourNow < 8){
		$partOfDay = "varane hommik";
	}
	if ($hourNow >= 8 and $hourNow < 16){
		$partOfDay = "koolipäev";
	}
	if ($hourNow >= 16){
		$partOfDay = "ilmselt vaba aeg";
	}
	
	$picNum = mt_rand(2, 43);
	//echo $pickNum;
	$picURL = "http://www.cs.tlu.ee/~rinde/media/fotod/TLU_600x400/tlu_";
	$picEXT = ".jpg";
	$picFile = $picURL .$picNum .$picEXT;
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php
			echo $firstName;
			echo " ";
			echo $lastName;
		?>, õppetöö</title>
</head>
<body>
	<h1><?php echo $firstName ." " .$lastName;?>, IF18</h1>
	<p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames, ei pruugi parim välja näha ning kindlasti ei sisalda tõsisevõetavat sisu.</p>
	
	<p>Tundides tehtu: <a href="kiisu.php">kiisu.php</a>, <a href="newuser.php">newuser.php</a></p>
	
	<?php
		//echo "<p>Tänane kuupäev on: " .$dateToday .".</p> \n";
		//echo "<p>Täna on " .$weekdayNow .", " .$dateToday .".</p> \n";
		echo "<p>Täna on " .$weekdayNamesET[$weekdayNow - 1] .", " .$dayToday .". " .$monthNamesET[$monthToday - 1] ." ".$yearToday .".</p> \n";
		echo "<p>Lehe avamise hetkel oli kell " .date("H:i:s") ."." ." Käes oli " .$partOfDay .".</p> \n";
	?>
	
	
	<!--<img src="http://greeny.cs.tlu.ee/~rinde/veebiprogrammeerimine2018s/tlu_terra_600x400_1.jpg" alt="TLÜ Terra õppehoone">-->
	<!--<img src="../../../~rinde/veebiprogrammeerimine2018s/tlu_terra_600x400_1.jpg" alt="TLÜ Terra õppehoone">-->
	
	<img src="<?php echo $picFile; ?>" alt="TLÜ Terra õppehoone">
	<p>Mul on ka sõber, kes teeb oma <a href="../../../~stevsal">veebi</a>.</p>

</body>
</html>