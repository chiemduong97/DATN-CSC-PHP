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
    if ($_GET['page'] != "")
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
        <div class="btnNew">
            <a type="button" class="btn" onclick="newForm()"><i class="uil uil-plus"></i></a>
        </div>
        <table id="customers">

            <thead>
                <tr>
                    <td scope="col" class="colFixed">#</td>
                    <th scope="col" width="50px">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col" width="50px">Avatar</th>
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
                            <td><img src=<?= $category['avatar']; ?> width="50" height="50"></img></td>

                            <td class="tdAction">
                                <a class="btnEdit" onclick="openForm('<?= $category['id']; ?>','<?= $category['name']; ?>','<?= $category['avatar']; ?>');">
                                    <i class="uil uil-edit-alt"></i></a>
                                <a class="btnDelete" onclick="remove(<?php echo $category['id']; ?>);">
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

    <div>
        <div class="form-popup" id="myForm">
            <form action="" class="form-container">
                <h1 id="titleForm">Update</h1>

                <input type="hidden" name="id" id="idCategory">

                <label for="nameUpdate"><b>Name</b></label>
                <input type="text" placeholder="Enter Name" name="name" id="nameUpdate" required>

                <div>
                    <label for="img-file"><b>Avatar</b></label>
                </div>
                <div style="margin-bottom : 10px">
                    <label for="img-file"><img id="img-view" width="50" height="50" src="" /></label>
                    <input onchange="onChangeFile()" type="file" placeholder="Enter Avatar" name="avatar" id="img-file" />
                </div>

                <button type="button" class="btn" onclick="update()">Ok</button>
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
            </form>
        </div>

    </div>

</body>

<script type="text/javascript">
    const viewImg = document.getElementById('img-view');
    const nameUpdate = document.getElementById('nameUpdate');
    const idCate = document.getElementById("idCategory");
    const titleForm = document.getElementById("titleForm");

    var firstId, firstName, firstAvatar;

    const onChangeFile = () => {
        const file = document.getElementById('img-file').files[0];
        const reader = new FileReader();
        reader.onload = e => {
            viewImg.src = e.target.result;
        }
        reader.readAsDataURL(file);

    }

    function remove($id) {
        if (!confirm('Are you sure ?')) {
            return;
        }

        var url = 'http://127.0.0.1:8686/api/category/category_remove.php';
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


    function update() {
        var text = "Hello word !!!";

        var id = idCate.value;
        var name = nameUpdate.value;
        var img = viewImg.src;

        // update if data change
        if (name != firstName || img != firstAvatar) {
            // update if have id
            if (id) {
                var url = 'http://127.0.0.1:8686/api/category/category_update.php';
                fetch(url, {
                        method: 'POST',
                        body: JSON.stringify({
                            id: id,
                            name: name,
                            avatar: img
                        })
                    })
                    .then(function(response) {
                        return response.text();
                    })
                    .then(function(body) {
                        const obj = JSON.parse(body);
                        if (obj.code == 1000) {
                            text = "Update successfully !!!";
                            window.location.reload();
                        } else if (obj.code == 1011) {
                            text = "Name already exists !!!";
                        } else {
                            text = "An error occurred, please try again later !!!"
                            window.location.reload();
                        }
                        alert(text);
                    });
            } else {
                var url = 'http://127.0.0.1:8686/api/category/category_insert.php';
                fetch(url, {
                        method: 'POST',
                        body: JSON.stringify({
                            id: id,
                            name: name,
                            avatar: img
                        })
                    })
                    .then(function(response) {
                        return response.text();
                    })
                    .then(function(body) {
                        const obj = JSON.parse(body);
                        if (obj.code == 1000) {
                            text = "Create successfully !!!";
                            window.location.reload();
                        } else if (obj.code == 1011) {
                            text = "Name already exists !!!";
                        } else {
                            text = "An error occurred, please try again later !!!"
                            window.location.reload();
                        }
                        alert(text);
                    });
            }
        } else {
            text = "No change !!!"
        }

    }

    function newForm() {
        document.getElementById("myForm").style.display = "block";
        document.querySelector(".containerTable").classList.add('blur');

        titleForm.innerHTML = "New category"
        firstId = idCate.value = null;
        firstAvatar = viewImg.src = null;
        firstName = nameUpdate.value = null;

    }

    function openForm($id, $name, $avatar) {
        document.getElementById("myForm").style.display = "block";
        document.querySelector(".containerTable").classList.add('blur');

        titleForm.innerHTML = "Update category"
        firstId = idCate.value = $id;
        firstAvatar = viewImg.src = $avatar;
        firstName = nameUpdate.value = $name;
    }

    function closeForm() {
        document.getElementById("myForm").style.display = "none";
        document.querySelector(".containerTable").classList.remove('blur');

    }
</script>


</html>