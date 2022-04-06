<?php 
    include_once $_SERVER['DOCUMENT_ROOT'].'/services/branch_service.php';

    class BranchController{
        private $service;
        

        public function __construct(){
            $this->service = new BranchService();
        }

        public function getAll() {
            return $this->service->getAll();
        }
    
}
?> 