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

    public function getCategoriesLevel_1_admin()
    {
        return $this->service->getCategoriesLevel_1_admin();
    }

    public function getCategoriesLevel_1($category_id)
    {
        return $this->service->getCategoriesLevel_1($category_id);
    }
    public function getByID($id)
    {
        return $this->service->getByID($id);
    }
    public function insertItem($name, $avatar,$category_id)
    {
        $category = new Category();
        $category->name = $name;
        $category->avatar = $avatar;
        $category->id = $category_id;

        if ($this->service->checkName($id = 0, $name)) {
            return 1011;
        } else {
            return $this->service->insertItem($category);
        }
    }
    public function updateItem($id,$name,$avatar,$category_id)
    {
        $category = new Category();
        $category->id = $id;
        $category->name = $name;
        $category->avatar = $avatar;
        $category->category_id = $category_id;
        if ($id == $category_id) return 1022;

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

    public function getAll($page, $limit)
    {
        return $this->service->getAll($page, $limit);
    }

    public function getTotalPageSearch($query)
    {
        return $this->service->getTotalPageSearch($query);
    }

    public function search($query,$page, $limit)
    {
        return $this->service->search($query,$page, $limit);
    }
}
