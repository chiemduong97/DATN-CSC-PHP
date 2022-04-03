<?php
    class Product{
        private $id;
        private $name;
        private $avatar;
        private $description;
        private $price;
        private $quantity;
        private $createdAt;
        private $updatedAt;
        private $category;
        private $branch;

        function __construct($id,$name,$avatar,$description,$price,$quantity,$createdAt,$updatedAt,$category,$branch){
            $this -> id = $id;
            $this -> name = $name;
            $this -> avatar = $avatar;
            $this -> description = $description;
            $this -> price = $price;
            $this -> quantity = $quantity;
            $this -> createdAt = $createdAt;
            $this -> updatedAt = $updatedAt;
            $this -> category = $category;
            $this -> branch = $branch;
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

        public function getQuantity(){
            return $this -> quantity;
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

        public function getBrach(){
            return $this -> branch;
        }
       
    }

?>