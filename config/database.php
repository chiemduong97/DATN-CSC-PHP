<?php
	class DatabaseConfig{
		// private $host = "localhost";
		private $host = "sql6.freemysqlhosting.net";
		// private $username = "root";
		private $username = "sql6515473";
		// private $password = "";
		private $password = "wzz3w3lVBX";
		// private $db_name = "datn_csc";
		private $db_name = "sql6515473";
		public $connect; 
		public function db_connect(){
			$this -> connect = null;
			try{
				$this -> connect = new PDO("mysql:host=" . $this -> host . 
										"; dbname=" . $this -> db_name, 
										$this -> username, $this -> password);
				$this -> connect -> exec("set names utf8");
			}catch(Exception $e){
				echo "Connect failed: " . $e -> getMessage();
			}
			return $this -> connect;
		}
	}
?>