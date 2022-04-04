<?php 
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/product/product_service.php';

    class ProductController{
        private $service;

        public function __construct(){
            $this->service = new ProductService();
        }

        public function getAll($category) {
            return $this->service->getAll($category);
        }
        public function getByID($id) {
            return $this->service->getByID($id);
        }
        public function insertItem($name, $avatar, $description, $price, $category) {
            $product = new Product(null,$name, $avatar, $description, $price, $category);

            if($this->service->checkName($name)){
                return 1011;
            }else{
                return $this->service->insertItem($product, $category);
            }
            
        }
        public function updateItem($id, $name, $avatar, $description, $price, $category) {
            $product = new Product($id,$name, $avatar, $description, $price, $category);

            if($this->service->checkName($name)){
                return 1011;
            }else{
                return $this->service->updateItem($product, $category);
            }

        }
        public function removeItem($id) {
            if($this->service->checkRemove($id)){
                return $this->service->removeItem($id);
            }else{
                return 1014;
            }
        }
    }
?> 