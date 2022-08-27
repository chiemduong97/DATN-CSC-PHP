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
    <div class="container">
        <div class="card card-container">
            <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form class="form-signin" method="post">
                <span id="reauth-email" class="reauth-email"></span>
                <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
                <div style="color: red;" id="err" ></div>
                <button id="login" class="btn btn-lg btn-primary btn-block btn-signin" type="button">Sign in</button>
            </form>
        </div>
    </div>
    <script>
        const fetchAPI = async (url,option) => {
            const res = await fetch(url,option);
            return res.json();
        }
        $("#login").on('click', function(){
            login();
        });
        const login = async () => {
            const email = $("#inputEmail").val();
            const password = $("#inputPassword").val();
            const url = "../../api/user/login_admin.php";
            const data = new URLSearchParams();
            data.append("email",email);
            data.append("password",password);
            const options = {
                method:'post',
                headers:{'Content-Type':'application/x-www-form-urlencoded'},
                body:data
            };
            try{
                const response = await fetchAPI(url,options);
                console.log(response);
                if(!response.is_error){
                    window.location.href = '../views/statistics.php';
                    localStorage.setItem("accessToken",response.data.access_token);
                    localStorage.setItem("email",email);
                }
                else{
                    $.ajax({
                        url: '../../config/errorcode.php',
                        type: 'POST',
                        data: {code:response.code},
                        success: (res) => {
                            $("#err").text(res);
                        }
                    });
                }
            }
            catch(err){
                console.log(err);
            }
        }

        const email = localStorage.getItem("email");
        $.ajax({
            type: "GET",
            url:`../../api/user/getUserByEmail.php?email=${email}`,
            headers: {"Authorization": 'Bearer ' + localStorage.getItem('accessToken')},
            success:(res) => {
                if(!res["is_error"]){
                    window.location.href = "statistics.php";
                }
            }
        });
        
    </script>
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->

    <!-- DATATABLE SCRIPTS  -->
    <script src="../public/javascripts/dataTables/jquery.dataTables.js"></script>
    <script src="../public/javascripts/dataTables/dataTables.bootstrap.js"></script>
   
    <!-- CUSTOM SCRIPTS  -->
    <script src="../public/javascripts/custom.js"></script>
    <style>

        body, html {
            height: 100%;
            background-repeat: no-repeat;
            background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));
        }

        .card-container.card {
            max-width: 350px;
            padding: 40px 40px;
        }

        .btn {
            font-weight: 700;
            height: 36px;
            -moz-user-select: none;
            -webkit-user-select: none;
            user-select: none;
            cursor: default;
        }

        .card {
            background-color: #F7F7F7;
            padding: 20px 25px 30px;
            margin: 0 auto 25px;
            margin-top: 50px;
            -moz-border-radius: 2px;
            -webkit-border-radius: 2px;
            border-radius: 2px;
            -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        }

        .profile-img-card {
            width: 96px;
            height: 96px;
            margin: 0 auto 10px;
            display: block;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
        }

        .profile-name-card {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0 0;
            min-height: 1em;
        }

        .reauth-email {
            display: block;
            color: #404040;
            line-height: 2;
            margin-bottom: 10px;
            font-size: 14px;
            text-align: center;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        .form-signin #inputEmail,
        .form-signin #inputPassword {
            direction: ltr;
            height: 44px;
            font-size: 16px;
        }

        .form-signin input[type=email],
        .form-signin input[type=password],
        .form-signin input[type=text],
        .form-signin button {
            width: 100%;
            display: block;
            margin-bottom: 10px;
            z-index: 1;
            position: relative;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        .form-signin .form-control:focus {
            border-color: rgb(104, 145, 162);
            outline: 0;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
        }

        .btn.btn-signin {
            /*background-color: #4d90fe; */
            background-color: rgb(104, 145, 162);
            /* background-color: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));*/
            padding: 0px;
            font-weight: 700;
            font-size: 14px;
            height: 36px;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            border: none;
            -o-transition: all 0.218s;
            -moz-transition: all 0.218s;
            -webkit-transition: all 0.218s;
            transition: all 0.218s;
        }

            .btn.btn-signin:hover,
            .btn.btn-signin:active,
            .btn.btn-signin:focus {
                background-color: rgb(12, 97, 33);
            }

        .forgot-password {
            color: rgb(104, 145, 162);
        }

            .forgot-password:hover,
            .forgot-password:active,
            .forgot-password:focus {
                color: rgb(12, 97, 33);
            }
    </style>
</body>
</html>



