<?php
include_once '../controllers/product_controller.php';
include_once '../controllers/category_controller.php';
include_once '../controllers/branch_controller.php';

$db = new ProductController();
$dbCategory = new CategoryController();
$dbBranch = new BranchController();


session_start();

if (empty($_SESSION['email'])) {
    echo '<center>Hãy đăng nhập...</center>';
    header('Refresh: 2; url= login.php');
    return;
}

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
$totalPage =  $db->getTotalPages($categoryId, $branchId);

// $categories = $dbCategory->getAll();
$branches = $dbBranch->getAll();


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
</head>

<body>

    <?php
    include './header.php'
    ?>

    <section class="containerTable">
        <div class="selectCategory">
            <label for="category">Category</label>
            <select name="category" id="category">
                <?php if (!empty($categories)) : ?>
                    <?php foreach ($categories as $category) : ?>
                        <?php if ($category['id'] != $categoryId) : ?>
                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                        <?php else : ?>
                            <option value="<?= $category['id'] ?>" selected><?= $category['name'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <button class="btnFind" onclick="findByCategoryAndBranch()">Find</button>
        </div>

        <div class="selectBranch">
            <label for="branch">Branch</label>
            <select name="branch" id="branch">
                <?php if (!empty($branches)) : ?>
                    <?php foreach ($branches as $branch) : ?>
                        <?php if ($branch['id'] != $branchId) : ?>
                            <option value="<?= $branch['id'] ?>"><?= $branch['name'] ?></option>
                        <?php else : ?>
                            <option value="<?= $branch['id'] ?>" selected><?= $branch['name'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <button class="btnFind" onclick="findByCategoryAndBranch()">Find</button>
        </div>

        <table id="customers">
            <thead>
                <tr>
                    <td scope="col" width="50px">#</td>
                    <th scope="col" width="50px">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Image</th>
                    <th scope="col" class="colFixed">Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php if (!empty($products)) : ?>
                    <?php foreach ($products as $key => $product) : ?>
                        <tr>
                            <th scope="row"><?php echo $key + 1; ?></th>
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $product['price']; ?></td>
                            <td><?php echo $product['quantity']; ?></td>
                            <td><img src=<?= $product['avatar']; ?> width="50" height="50" /></td>
                            <td class="tdAction">
                                <a class="btnEdit"><i class="uil uil-edit-alt"></i></a>
                                <a class="btnDelete" onclick="remove(<?php echo $product['id']; ?>);">
                                    <i class="uil uil-times-circle"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td>Không có dữ liệu !!!</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>

        <?php
        include './pagination.php'
        ?>
    </section>

</body>

<script type="text/javascript">
    function findByCategoryAndBranch() {
        var category = document.getElementById('category').value;
        var branch = document.getElementById('branch').value;
        window.location.assign("http://192.168.1.4:8585/views/product.php?categoryId=" + category +
            "&branchId=" + branch);
    }

    function remove($id) {
        var url = 'http://192.168.1.4:8585/api/product/product_remove.php';
        var formData = new FormData();
        formData.append('id', $id);

        fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(function(response) {
                return response.text();
            })
            .then(function(body) {
                console.log(body);
            });
         window.location.reload();   
    }
</script>

</html>