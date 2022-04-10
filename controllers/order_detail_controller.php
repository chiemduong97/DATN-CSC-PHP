<?php 
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/order_detail_service.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/models/order_detail_model.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/product_service.php';


    class OrderDetailController{
        private $service;
        

        public function __construct(){
            $this->service = new OrderDetailService();
        }

        // public function insertItem($quantity,$order_id,$product_id) {
        //     $orderdetail = new OrderDetail();
        //     $orderdetail -> amount = (int)$quantity * ((new ProductService) -> getByID($product_id))["price"];
        //     $orderdetail -> quantity = $quantity;
        //     $orderdetail -> order_id = $order_id;
        //     $orderdetail -> product_id = $product_id;
        //     return $this->service->insertItem($orderdetail);
        // }

        public function getByOrderCode($ordercode) {
            return $this->service->getByOrderCode($ordercode);

        }
    
}
?> 