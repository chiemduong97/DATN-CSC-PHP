<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/product_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/category_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/branch_controller.php';

$db = new ProductController();
$dbCategory = new CategoryController();
$dbBranch = new BranchController();


// session_start();

// if (empty($_SESSION['email'])) {
//     echo '<center>Hãy đăng nhập...</center>';
//     header('Refresh: 2; url= login.php');
//     return;
// }

$currentPage = 1;
$categoryId = 1;
$branchId = 1;
$limit = 10;

if (!empty($_GET['categoryId'])) {
    if ($_GET['categoryId'] != "")
        $categoryId = $_GET['categoryId'];
}
if (!empty($_GET['branchId'])) {
    if ($_GET['branchId'] != "")
        $branchId = $_GET['branchId'];
}
if (!empty($_GET['page'])) {
    if ($_GET['page'] != "")
        $currentPage = $_GET['page'];
}

$products = $db->getProducts($categoryId, $branchId, $currentPage, $limit);
$totalPage =  $db->getTotalPages($categoryId, $branchId, $limit);

$categories1 = $dbCategory->getCategoriesLevel_0(1, 10);
$branches = $dbBranch->getAll();


?>

<?php
include('./includes/header.php');
include('./includes/navbar.php');
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Static Navigation</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
            <li class="breadcrumb-item active">Static Navigation</li>
        </ol>
    </div>

    <div>
        <div class="selectCategory1">
            <label for="category1">Danh mục 1</label>
            <select name="category1" id="category1">
                <?php if (!empty($categories1)) : ?>
                    <?php foreach ($categories1 as $category) : ?>
                        <?php if ($category['id'] != $categoryId) : ?>
                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                        <?php else : ?>
                            <option value="<?= $category['id'] ?>" selected><?= $category['name'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <a class="add-category-1">
                <i class="fa-solid fa-circle-plus"></i>
            </a>
        </div>


    </div>
</main>

<?php
include './includes/script.php';
include './includes/footer.php'
?>