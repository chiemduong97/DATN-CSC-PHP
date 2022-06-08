<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/services/product_service.php';

class ProductController
{
    private $service;

    public function __construct()
    {
        $this->service = new ProductService();
    }

    public function getByID($id)
    {
        return $this->service->getByID($id);
    }
    public function insertItem($name, $avatar, $description, $price, $category_id)
    {
        $product = new Product();
        $product->name = $name;
        $product->avatar = $avatar;
        $product->description = $description;
        $product->price = $price;
        $product->category_id = $category_id;

        if ($this->service->checkName($name)) {
            return 1011;
        } else {
            return $this->service->insertItem($product, $category_id);
        }
    }
    public function updateItem($id, $name, $avatar, $description, $price, $category_id)
    {
        $product = new Product();
        $product->id = $id;
        $product->name = $name;
        $product->avatar = $avatar;
        $product->description = $description;
        $product->price = $price;
        $product->category_id = $category_id;

        if ($this->service->checkName($name)) {
            return 1011;
        } else {
            return $this->service->updateItem($product, $category_id);
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

    public function getProducts($category_id, $branch, $page, $limit)
    {
        return $this->service->getProducts($category_id, $branch, $page, $limit);
    }
    public function getTotalPages($category_id, $branch, $limit)
    {
        return $this->service->getTotalPages($category_id, $branch, $limit);
    }
}
