<?php
	//kutsume funktioonide faili
	require("functions.php");
	
	$firstName = "";
	$lastName = "";
	$birthMonth = null;
	$birthDay = null;
	$birthYear = null;
	$birthDate = null;
	$gender = null;
	$email = "";
	$notice = "";
	
	$firstNameError = "";
	$lastNameError = "";
	$birthMonthError = "";
	$birthDayError = "";
	$birthYearError = "";
	$birthDateError = "";
	$genderError = "";
	$emailError = "";
	$passwordError = "";
	
	$monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
  
	if (isset($_POST["submitUserData"])){
	//Kontrollime, kas kasutaja on nuppu vajutanud
	//var_dump($_POST);
	if (isset($_POST["firstName"]) and !empty($_POST["firstName"])){
		//$firstName = $_POST["firstName"];
		$firstName = test_input($_POST["firstName"]);
	} else {
		$firstNameError = " Palun sisesta oma eesnimi!";
	}
	if (isset($_POST["lastName"]) and !empty($_POST["lastName"])){
		$lastName = test_input($_POST["lastName"]);
	} else {
		$lastNameError = " Palun sisesta oma perekonnanimi!";
	}
	
	if (isset($_POST["gender"]) and !empty($_POST["gender"])){
		$gender = intval($_POST["gender"]);
	} else {
		$genderError = "Palun vali sugu!";
	}
	
	if (isset($_POST["email"]) and !empty($_POST["email"])){
		$email = test_input($_POST["email"]);
	} else {
		$emailError = " Palun sisesta oma e-posti aadress!";
	}

	if (empty($_POST["birthDay"])){
		$birthDayError = "Palun sisesta sünnipäev!";
	}

	if (empty($_POST["birthMonth"])){
		$birthMonthError = "Palun sisesta sünnikuu!";
	}

	if (empty($_POST["birthYear"])){
		$birthYearError = "Palun sisesta sünniaasta!";
	}
	
	if(isset($_POST["birthDay"]) and isset($_POST["birthMonth"]) and isset($_POST["birthYear"])){
		
		//checkdate kuu,päev,aasta täisarvud
		if(checkdate(intval($_POST["birthMonth"]), intval($_POST["birthDay"]), intval($_POST["birthYear"]))){
			$birthDate = date_create($_POST["birthMonth"] ."/" .$_POST["birthDay"] ."/" .$_POST["birthYear"]);
			$birthDate = date_format($birthDate, "Y-m-d");
			
			//echo $birthDate;
			
		} else {
			$birthDateError = "Palun vali võimalik kuupäev!";
		}
		
		
	}
	
	if (isset($_POST["password"]) and strlen($_POST["password"]) > 7){
		if(isset($_POST["confirmpassword"]) and $_POST["password"] == $_POST["confirmpassword"]){
			$_POST["password"] = $_POST["password"];
		} else {
			$passwordError = "Paroolid ei kattu!";
		}
	} else {
		$passwordError = " Parooli pikkus peab olema vähemalt 8 märki!";
	}


	if(empty($firstNameError) and empty($lastNameError) and empty($birthMonthError) and empty($birthDayError) and empty($birthYearError) and empty($birthDateError) and empty($genderError) and empty($emailError) and empty($passwordError)){
		$notice = signup($firstName, $lastName, $birthDate, $gender, $email, $_POST["password"]);
	}
	
	}
	
	
	
	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Uue kasutaja loomine</title>
</head>
<body>
	<h1>Sisesta oma andmed ja loo kasutaja</h1>
	<p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames, ei pruugi parim välja näha ning kindlasti ei sisalda tõsisevõetavat sisu.</p>
	
	<hr>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label>Eesnimi:</label><br>
		<input type="text" name="firstName" value="<?php echo $firstName; ?>"><span><?php echo $firstNameError; ?></span><br>
		<label>Perekonnanimi:</label><br>
		<input type="text" name="lastName" value="<?php echo $lastName; ?>"><span><?php echo $lastNameError; ?></span><br><br>
		<label>Sünnipäev: </label>
		  <?php
			echo '<select name="birthDay">' ."\n";
			for ($i = 1; $i < 32; $i ++){
				echo '<option value="' .$i .'"';
				if ($i == $birthDay){
					echo " selected ";
				}
				echo ">" .$i ."</option> \n";
			}
			echo "</select> \n";
		  ?>
		  <label>Sünnikuu: </label>
		  <?php
			echo '<select name="birthMonth">' ."\n";
			for ($i = 1; $i < 13; $i ++){
				echo '<option value="' .$i .'"';
				if ($i == $birthMonth){
					echo " selected ";
				}
				echo ">" .$monthNamesET[$i - 1] ."</option> \n";
			}
			echo "</select> \n";
		  ?>
		  <label>Sünniaasta: </label>
		  <!--<input name="birthYear" type="number" min="1914" max="2003" value="1998">-->
		  <?php
			echo '<select name="birthYear">' ."\n";
			for ($i = date("Y") - 15; $i >= date("Y") - 100; $i --){
				echo '<option value="' .$i .'"';
				if ($i == $birthYear){
					echo " selected ";
				}
				echo ">" .$i ."</option> \n";
			}
			echo "</select> \n";
		  ?>
			
			<span><?php echo $birthDayError; ?></span><br>
			<span><?php echo $birthMonthError; ?></span><br>
			<span><?php echo $birthYearError; ?></span><br>
		
		<input type="radio" name="gender" value="2" <?php if($gender == 2) {echo "checked";} ?>><label>Naine</label><br>
		<input type="radio" name="gender" value="1" <?php if($gender == 1) {echo "checked";} ?>><label>Mees</label><br>
		<span><?php echo $genderError; ?></span>
		<br>
		<br>
		
		<label>E-posti aadress (kasutajatunnuseks)</label><br>
		<input name="email" type="email" value="<?php echo $email; ?>"><span><?php echo $emailError; ?></span><br>
		<br>
		
		<label>Salasõna (min 8 märki):</label><br>
		<input name="password" type="password"><br>
		<br>

		<label>Korda salasõna:</label><br>
		<input name="confirmpassword" type="password"><br>
		<span><?php echo $passwordError; ?></span><br>
		
		<input type="submit" name="submitUserData" value="Loo kasutaja">
	</form>
	<hr>
	
	<p><?php echo $notice; ?></p>
	
	
	
	
	
	
	
	
	
	
	
</body>
</html>