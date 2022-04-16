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

        public function updateStatus($ordercode,$status) {
            return $this -> service -> updateStatus($ordercode,$status);
        }

        public function insertItem($orderParam) {
            $order_details = $orderParam -> order_details;
            $order = new Order();
            $order -> ordercode = strtoupper(substr(md5(microtime()),rand(0,26),6));
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
                    $orderdetail -> amount = (int)$quantity * ((new ProductService()) -> getByID($product_id))["price"];
                    $orderdetail -> quantity = $quantity;
                    $orderdetail -> ordercode = $order -> ordercode;
                    $orderdetail -> product_id = $product_id;
                    $orderdetail -> name = $order_details[$i] -> name;
                    $amount += $orderdetail -> amount;
                    $result = (new OrderDetailService) -> insertItem($orderdetail);
                    if (!$result) {
                        return null;
                    }
                }
                $order -> amount = $amount;
                $order -> latitude = $orderParam -> latitude;
                $order -> longitude = $orderParam -> longitude;
                $order -> address = $orderParam -> address;
                $order -> phone = $orderParam -> phone;
                $order -> shippingFee = $orderParam -> shipping_fee;
                $order -> promotionCode = isset($orderParam -> promotionCode)?$orderParam -> promotionCode:null;
                $order -> promotionValue = isset($orderParam -> promotionValue)?$orderParam -> promotionValue:null;
                $order -> branch_latitude = $orderParam -> branch_latitude;
                $order -> branch_longitude = $orderParam -> branch_longitude;
                $order -> branch_address = $orderParam -> branch_address;
                $result = $this -> service -> insertItem($order);
                if ($result) {
                    return $order -> ordercode;
                } 
            }
            return null;
        }

        public function getByOrderCode($ordercode) {
            return $this -> service -> getByOrderCode($ordercode);
        }
    
}
?> 