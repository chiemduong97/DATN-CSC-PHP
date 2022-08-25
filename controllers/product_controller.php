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

    public function getProductsRecent($page, $limit, $user_id)
    {
        return $this->service->getProductsRecent($page, $limit, $user_id);
    }

    public function getProductsHighLight($page, $limit)
    {
        return $this->service->getProductsHighLight($page, $limit);
    }

    public function getProductsNew($page, $limit)
    {
        return $this->service->getProductsNew($page, $limit);
    }

    public function getProducts($category_id, $page, $limit)
    {
        return $this->service->getProducts($category_id, $page, $limit);
    }
    public function getProductsSearch($page, $limit, $query)
    {
        return $this->service->getProductsSearch($page, $limit, $query);
    }

    public function getTotalPagesRecent($limit, $user_id)
    {
        return $this->service->getTotalPagesRecent($limit, $user_id);
    }

    public function getTotalPagesHighLight($limit)
    {
        return $this->service->getTotalPagesHighLight($limit);
    }

    public function getTotalPagesNew($limit)
    {
        return $this->service->getTotalPagesNew($limit);
    }

    public function getTotalPages($category_id, $limit)
    {
        return $this->service->getTotalPages($category_id, $limit);
    }

    public function getTotalPagesSearch($limit, $query)
    {
        return $this->service->getTotalPagesSearch($limit, $query);
    }

    public function warehouse($product_id,$quantity,$email)
    {
        return $this->service->insertWarehouse($product_id,$quantity,$email);
    }
}
