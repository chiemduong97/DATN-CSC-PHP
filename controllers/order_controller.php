<?php 
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/order_service.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/models/order_model.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/order_detail_service.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/models/order_detail_model.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/product_service.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/branch_service.php';

    class OrderController{
        private $service;
        

        public function __construct(){
            $this->service = new OrderSerivce();
        }

        public function getByUser($user_id) {
            return $this -> service -> getByUser($user_id);
        }

        public function updateStatus($order_code,$status) {
            return $this -> service -> updateStatus($order_code,$status);
        }

        public function insertItem($orderParam) {
            $order_details = $orderParam -> order_details;
            $order = new Order();
            $order -> order_code = strtoupper(substr(md5(microtime()),rand(0,26),6));
            $order -> user_id = $orderParam -> user_id;
            $order -> branch_id = $orderParam -> branch_id;
            $order -> promotion_id = isset($orderParam -> promotion_id) ? $orderParam -> promotion_id : null;
            $result = $this -> service -> insertItem($order);
            if($result) {
                $amount = 0;
                for ($i = 0; $i < count($order_details); $i++) {
                    $quantity = $order_details[$i] -> quantity;
                    $product_id = $order_details[$i] -> product_id;
                    $orderdetail = new OrderDetail();
                    $orderdetail -> price = (int)$quantity * ((new ProductService()) -> getByID($product_id))["price"];
                    $orderdetail -> quantity = $quantity;
                    $orderdetail -> order_code = $order -> order_code;
                    $orderdetail -> product_id = $product_id;
                    $orderdetail -> name = $order_details[$i] -> name;
                    $amount += ($orderdetail -> price * (int)$quantity);
                    $result = (new OrderDetailService) -> insertItem($orderdetail);
                    if (!$result) {
                        return null;
                    }
                }
                $order -> amount = $amount;
                $order -> lat = $orderParam -> lat;
                $order -> long = $orderParam -> long;
                $order -> address = $orderParam -> address;
                $order -> phone = $orderParam -> phone;
                $order -> shipping_fee = $orderParam -> shipping_fee;
                $order -> promotion_code = isset($orderParam -> promotion_code)?$orderParam -> promotion_code:null;
                $order -> promotion_value = isset($orderParam -> promotion_value)?$orderParam -> promotion_value:null;
                $order -> branch_lat = $orderParam -> branch_lat;
                $order -> branch_long = $orderParam -> branch_long;
                $order -> branch_address = $orderParam -> branch_address;
                $result = $this -> service -> insertItem($order);
                if ($result) {
                    return $order -> order_code;
                } 
            }
            return null;
        }

        public function getByorder_code($order_code) {
            return $this -> service -> getByorder_code($order_code);
        }
    
}
?> 