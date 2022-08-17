<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!--[if IE]>
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
            <![endif]-->
    <title>APPLICATION ADMIN</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="../public/stylesheets/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="../public/stylesheets/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE  -->
    <link href="../public/stylesheets/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- MORRIS STYLE-->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <!-- CUSTOM STYLE  -->
    <link href="../public/stylesheets/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <!-- CORE JQUERY  -->
    <script src="../public/javascripts/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="../public/javascripts/bootstrap.js"></script>
    <!-- MORRIS SCRIPTS  -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.4.0/firebase.js"></script>
</head>

<body>
    <div class="navbar navbar-inverse set-radius-zero">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="statistics.php">
                    <img width="200" src="../public/images/ic_logo.png" />
                </a>
            </div>
            <div class="right-div">
                <a onclick="logout()" class="btn btn-info pull-right">LOG OUT</a>
                <p class="pull-right btn">Wellcome <strong id="email"></strong></p>
            </div>
        </div>
    </div>
    <!-- LOGO HEADER END-->
    <section class="menu-section">
        <div class="container">
            <div class="row ">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li><a href="statistics.php">THỐNG KÊ</a></li>
                            <li><a href="branch.php">CHI NHÁNH</a></li>
                            <li><a href="order.php">ĐƠN HÀNG</a></li>
                            <li><a href="category.php">THỂ LOẠI</a></li>
                            <li><a href="user.php">USERS</a></li>

                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->

    <!-- DATATABLE SCRIPTS  -->
    <script src="../public/javascripts/dataTables/jquery.dataTables.js"></script>
    <script src="../public/javascripts/dataTables/dataTables.bootstrap.js"></script>

    <!-- CUSTOM SCRIPTS  -->
    <script src="../public/javascripts/custom.js"></script>
    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyBj15wftaGCexBOsxTRNqgkYPIBDuD63iA",
            authDomain: "myreact-bce2e.firebaseapp.com",
            projectId: "myreact-bce2e",
            storageBucket: "myreact-bce2e.appspot.com",
            messagingSenderId: "526333838997",
            appId: "1:526333838997:web:c47de5baea83b4a0663d9c"
        };
        firebase.initializeApp(firebaseConfig);

        const email = localStorage.getItem("email");
        $.ajax({
            type: "GET",
            url: `../../api/user/getUserByEmail.php?email=${email}`,
            headers: {
                "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
            },
            success: (res) => {
                if (res["is_error"]) {
                    window.location.href = "login.php";
                } else {
                    if (localStorage.getItem("user") == null) localStorage.setItem("user", JSON.stringify(res));
                    $("#email").html(email);
                }
            }
        });

        function logout() {
            localStorage.clear();
            window.location.href = "login.php";
        }
    </script>
</body>

</html>