<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/product_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/category_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/branch_controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/category_model.php';

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
$categories2 = $dbCategory->getCategoriesLevel_1(1, 10, 0);
$branches = $dbBranch->getAll();

$temp = array(
    "id" => null,
    "name" => "Chọn danh mục"
);
array_push($categories1, $temp);

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
                            <?php if ($category['id'] == null) : ?>
                                <option value="<?= $category['id'] ?>" selected><?= $category['name'] ?></option>
                            <?php else : ?>
                                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                            <?php endif; ?>
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

        <div class="selectCategory2">
            <label for="category2">Danh mục 2</label>
            <select name="category2" id="category2">
                <?php if (!empty($categories2)) : ?>
                    <?php foreach ($categories2 as $category) : ?>
                        <option value="<?= $category['id'] ?>" selected><?= $category['name'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <a class="add-category-1">
                <i class="fa-solid fa-circle-plus"></i>
            </a>
        </div>

        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <div class="container-form">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Title</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Content</label>
                <textarea class="form-control content-area" id="exampleFormControlTextarea1" rows="5"></textarea>
            </div>
        </div>
    </div>
</main>

<?php
include './includes/script.php';
include './includes/footer.php'
?>
<script src="./js/ckeditor5/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.getElementById('exampleFormControlTextarea1'))
        .catch(error => {
            console.log(error);
        })
    // CKEDITOR.replace('exampleFormControlTextarea1');
</script>

<script type="text/javascript">
    // function findByCategoryAndBranch() {
    //     var category = document.getElementById('category').value;
    //     var branch = document.getElementById('branch').value;
    //     window.location.assign("http://192.168.1.4:8585/views/product.php?categoryId=" + category +
    //         "&branchId=" + branch);
    // }

    // function remove($id) {
    //     var url = 'http://192.168.1.4:8585/api/product/product_remove.php';
    //     var formData = new FormData();
    //     formData.append('id', $id);

    //     fetch(url, {
    //             method: 'POST',
    //             body: formData
    //         })
    //         .then(function(response) {
    //             return response.text();
    //         })
    //         .then(function(body) {
    //             console.log(body);
    //         });
    //      window.location.reload();   
    // }
</script>