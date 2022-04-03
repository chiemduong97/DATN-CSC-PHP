<?php
	class DatabaseConfig{
		private $host = "localhost";
		private $username = "root";
		private $password = "";
		private $db_name = "datn_csc";
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