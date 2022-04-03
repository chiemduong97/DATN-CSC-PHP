<?php
    class Promotion{
        private $id;
        private $avatar;
        private $description;
        private $code;
        private $status;
        private $createdAt;
        private $start;
        private $end;
    
        function __construct($id,$avatar,$description,$code,$status,$createdAt,$start,$end){
            $this -> id = $id;
            $this -> avatar = $avatar;
            $this -> description = $description;
            $this -> code = $code;
            $this -> status = $status;
            $this -> createdAt = $createdAt;
            $this -> start = $start;
            $this -> end = $end;
        }

        public function getId(){
            return $this -> id;
        }

        public function getAvatar(){
            return $this -> avatar;
        }

        public function getDescription(){
            return $this -> description;
        }

        public function getCode(){
            return $this -> code;
        }

        public function getStatus(){
            return $this -> status;
        }
      
        public function getCreatedAt(){
            return $this -> createdAt;
        }

        public function getStart(){
            return $this -> start;
        }

        public function getEnd(){
            return $this -> end;
        }

       
    }

?>