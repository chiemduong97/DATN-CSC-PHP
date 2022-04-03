<?php
    class User{
        private $id;
        private $email;
        private $password;
        private $avatar;
        private $fullname;
        private $birthday;
        private $phone;
        private $status;
        private $permission;
        private $firstorder;
        private $wallet;
        private $deviceToken;
        private $createdAt;
        function __construct($id,$email,$password,$avatar,$fullname,$birthday,$phone,$status,$permission,$firstorder,$wallet,$deviceToken,$createdAt){
            $this -> id = $id;
            $this -> email = $email;
            $this -> password = $password;
            $this -> avatar = $avatar;
            $this -> fullname = $fullname;
            $this -> birthday = $birthday;
            $this -> phone = $phone;
            $this -> status = $status;
            $this -> permission = $permission;
            $this -> firstorder = $firstorder;
            $this -> wallet = $wallet;
            $this -> deviceToken = $deviceToken;
            $this -> createdAt = $createdAt;
        }

        public function getId(){
            return $this -> id;
        }

        public function getEmail(){
            return $this -> email;
        }

        public function getPassword(){
            return $this -> password;
        }

        public function getAvatar(){
            return $this -> avatar;
        }

        public function getFullname(){
            return $this -> fullname;
        }
      
        public function getBirthday(){
            return $this -> birthday;
        }

        public function getPhone(){
            return $this -> phone;
        }

        public function getStatus(){
            return $this -> status;
        }
        public function getFirstOrder(){
            return $this -> firstorder;
        }

        public function getPermission(){
            return $this -> permission;
        }

        public function getWallet(){
            return $this -> wallet;
        }

        public function getDeviceToken(){
            return $this -> deviceToken;
        }

        public function getCreatedAt(){
            return $this -> createdAt;
        }
    }

?>