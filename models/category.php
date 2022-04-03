<?php
    class Category{
        private $id;
        private $name;
        private $avatar;
        function __construct($id,$name,$avatar){
            $this -> id = $id;
            $this -> name = $name;
            $this -> avatar = $avatar;
        }

        public function getId(){
            return $this -> id;
        }

        public function getName(){
            return $this -> name;
        }

        public function getAvatar(){
            return $this -> avatar;
        }
    }

?>