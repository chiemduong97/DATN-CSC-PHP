<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/category_service.php';

class CategoryController
{
    private $service;



    public function __construct()
    {
        $this->service = new CategoryService();
    }

    public function getCategoriesLevel_0()
    {
        return $this->service->getCategoriesLevel_0();
    }
    public function getCategoriesLevel_1($page, $limit, $category_id)
    {
        return $this->service->getCategoriesLevel_1($page, $limit, $category_id);
    }
    public function getByID($id)
    {
        return $this->service->getByID($id);
    }
    public function insertItem($name = "chua co ten", $avatar = "https://cdn.pixabay.com/photo/2014/04/02/10/25/man-303792_960_720.png")
    {
        $category = new Category();
        $category->name = $name;
        $category->avatar = $avatar;

        if ($this->service->checkName($id = 0, $name)) {
            return 1011;
        } else {
            return $this->service->insertItem($category);
        }
    }
    public function updateItem($id, $name, $avatar)
    {
        $category = new Category();
        $category->id = $id;
        $category->name = $name;
        $category->avatar = $avatar;

        if ($this->service->checkName($id, $name)) {
            return 1011;
        } else {
            return $this->service->updateItem($category);
        }
    }
    public function removeItem($id)
    {

        if ($this->service->checkRemove($id)) {
            return $this->service->removeItem($id);
        } else {
            return 1014;
        }
    }

    public function getTotalPagesCategories()
    {
        return $this->service->getTotalPagesCategories();
    }
}
