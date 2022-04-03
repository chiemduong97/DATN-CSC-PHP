<?php 
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/category/category_service.php';

    class CategoryController{
        private $service;

        public function __construct(){
            $this->service = new CategoryService();
        }

        public function getAll() {
            return $this->service->getAll();
        }
        public function getByID($id) {
            return $this->service->getByID($id);
        }
        public function insertItem($name = "chua co ten", $avatar = "https://cdn.pixabay.com/photo/2014/04/02/10/25/man-303792_960_720.png") {
            $category = new Category(null,$name, $avatar);

            return $this->service->insertItem($category);
        }
        public function updateItem($id, $name, $avatar) {
            $category = new Category($id,$name, $avatar);

            return $this->service->updateItem($category);
        }
        public function removeItem($id) {
            return $this->service->removeItem($id);
        }
    }
?>