<?php 
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/category_service.php';

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

            if($this->service->checkName($name)){
                return 1011;
            }else{
                return $this->service->insertItem($category);
            }
            
        }
        public function updateItem($id, $name, $avatar) {
            $category = new Category($id,$name, $avatar);

            if($this->service->checkName($name)){
                return 1011;
            }else{
                return $this->service->updateItem($category);
            }

        }
        public function removeItem($id) {

            if($this->service->checkRemove($id)){
                return $this->service->removeItem($id);
            }else{
                return 1014;
            }

        }
        public function getCategoriesWithPage($page = 1) {
            return $this->service->getCategoriesWithPage($page);
        }
        public function getTotalPagesCategories() {
            return $this->service->getTotalPagesCategories();
        }

}
?> 