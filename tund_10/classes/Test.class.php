<?php

	class Test
	{
		//omadused ehk muutujad
		private $secretNumber;
		public $publicNumber;
		
		//constructor - käivitatakse objekti loomisel
		function __construct($sentNumber){
			$this->secretNumber = 5;
			$this->publicNumber = $this->secretNumber * $sentNumber;
			$this->tellSecret();
		}
		
		//kui klass suletakse/objekt eemaldatakse
		function __destruct(){
			echo "\n Lõpetame!";
		}
		
		private function tellSecret(){
			echo "Salajane number on: " .$this->secretNumber .". ";
		}
		
		public function tellInfo(){
			echo "\n Saladusi ei paljasta.";
		}
	}

?>