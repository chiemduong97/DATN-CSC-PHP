<?php
    class Branch{
        private $id;
        private $name;
        private $latitude;
        private $longitude;
        private $address;
        function __construct($id,$name,$latitude,$longitude,$address){
            $this -> id = $id;
            $this -> name = $name;
            $this -> latitude = $latitude;
            $this -> longitude = $longitude;
            $this -> address = $address;
        }

        public function getId(){
            return $this -> id;
        }

        public function getName(){
            return $this -> name;
        }

        public function getLatitude(){
            return $this -> latitude;
        }

        public function getLongitude(){
            return $this -> longitude;
        }

        public function getAddress() {
            return $this -> address;
        }
    }

?>