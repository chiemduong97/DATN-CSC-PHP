<?php
    class Order{
        private $id;
        private $ordercode;
        private $products;
        private $amount;
        private $createAt;
        private $status;
        private $user;
    
        function __construct($id,$ordercode,$products,$createAt,$amount,$status,$user){
            $this -> id = $id;
            $this -> user = $user;
            $this -> products = $products;
            $this -> ordercode = $ordercode;
            $this -> createAt = $createAt;
            $this -> amount = $amount;
            $this -> status = $status;
        }

        public function getId(){
            return $this -> id;
        }

        public function getUser(){
            return $this -> user;
        }

        public function getOrdercode(){
            return $this -> ordercode;
        }

        public function getCreateAt(){
            return $this -> createAt;
        }
      
        public function getAmount(){
            return $this -> amount;
        }

        public function getStatus(){
            return $this -> status;
        }

        public function getProducts(){
            return $this -> products;
        }
       
    }

?>