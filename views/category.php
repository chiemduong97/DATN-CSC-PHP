<?php
include_once '../controllers/category_controller.php';

$db = new CategoryController();

session_start();

if (empty($_SESSION['email'])) {
    echo '<center>Hãy đăng nhập...</center>';
    header('Refresh: 2; url= login.php');
    return;
}

$currentPage = 1;

if (!empty($_GET['page'])) {
    if($_GET['page'] != "")
    $currentPage = $_GET['page'];
}

$categories = $db->getCategoriesWithPage($currentPage);
$totalPage =  $db->getTotalPagesCategories();


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
</head>

<body>

    <?php
    include './header.php'
    ?>

    <section class="containerTable">
        <table id="customers">
            <thead>
                <tr>
                    <td scope="col" class="colFixed">#</td>                    
                    <th scope="col" width="50px">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col" width="50px">Image</th>
                    <th scope="col" class="colFixed">Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php if (!empty($categories)) : ?>
                    <?php foreach ($categories as $key => $category) : ?>
                        <tr>
                            <th scope="row"><?php echo $key + 1; ?></th>
                            <td><?php echo $category['id']; ?></td>
                            <td><?php echo $category['name']; ?></td>
                            <td ><img src=<?= $category['avatar']; ?> width="50" height="50" ></img></td>

                            <td class="tdAction">
                                <a class="btnEdit" href="#"><i class="uil uil-edit-alt"></i></a>
                                <a class="btnDelete" href="delete.php?id=<?= $category['id'] ?>" onclick="return confirm('Are you sure ?');">
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

</html>