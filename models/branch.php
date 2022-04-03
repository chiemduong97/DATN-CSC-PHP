<?php
    class Branch{
        private $id;
        private $name;
        private $location;
        function __construct($id,$name,$location){
            $this -> id = $id;
            $this -> name = $name;
            $this -> location = $location;
        }

        public function getId(){
            return $this -> id;
        }

        public function getName(){
            return $this -> name;
        }

        public function getLocation(){
            return $this -> location;
        }
    }

?>