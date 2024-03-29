<?php 
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/promotion_service.php';

    class PromotionController{
        private $service;
        

        public function __construct(){
            $this->service = new PromotionService();
        }

        public function getAll() {
            return $this->service->getAll();
        }

        public function getByCode($code) {
            return $this->service->getByCode($code);
        }
    
}
?> 