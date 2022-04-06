<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/user_controller.php';

session_start();
session_unset();

// var_dump($row['iduser']);exit;

if (!empty($_POST['email']) && !empty($_POST['password']) != "") {
	$email = $_POST['email'];
	$password = $_POST['password'];


	$validateLogin = false;

	$result = (new UserController()) -> login($email,$password);
	if ($result == 1000) {
		$_SESSION['email'] = $email;
		header('Location: home.php');
	} else {
		$validateLogin = true;
	}
	echo json_encode($result);
}

?>

<!doctype html>
<html>

<head>
	<title>Login Page</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>

<body>
	<div class="form">
		<p>Login</p>
		<form action="" method="post">
			<div class="danger <?php if ($validateLogin == true) {
									echo 'show';
								} else {
									echo 'hidden';
								} ?>">
				<i class="uil uil-exclamation-triangle"></i>
				Sai tài khoản, mật khẩu
			</div>
			<input type="text" name="email" placeholder="email">
			<input type="password" name="password" placeholder="password">
			<button>Login</button>
			<p class="message">Not registered? <a href="#">Create an account</a></p>
		</form>
	</div>
</body>

</html>