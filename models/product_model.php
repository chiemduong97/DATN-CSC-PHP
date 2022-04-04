<?php
    class Product{
        private $id;
        private $name;
        private $avatar;
        private $description;
        private $price;
        private $createdAt;
        private $updatedAt;
        private $category;

        function __construct($id,$name,$avatar,$description,$price,$category){
            $this -> id = $id;
            $this -> name = $name;
            $this -> avatar = $avatar;
            $this -> description = $description;
            $this -> price = $price;
            $this -> category = $category;
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
        
        public function getDescription(){
            return $this -> description;
        }

        public function getPrice(){
            return $this -> price;
        }


        public function getCreatedAt(){
            return $this -> createdAt;
        }

        public function getUpdatedAt(){
            return $this -> updatedAt;
        }

        public function getCategory(){
            return $this -> category;
        }

      
       
    }

?>