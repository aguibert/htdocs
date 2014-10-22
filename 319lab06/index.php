<?php
include 'objects/user.php';
session_start();	

if(isset($_POST['username'], $_POST['password'])){
	$user = $_POST['username'];
	$pass = $_POST['password'];
	if(User::checkUserAndPass($user, $pass)){
		$_SESSION['username'] = $user;
		$_SESSION['bIsLib'] = User::isLibrarian($user);
		header('Location: home.php');
	} else
		echo "<script>alert('Username or password incorrect!')</script>";
}
?>

<html>
<head>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">	
<br>
</head>
<body>

<div id="login" class="col-md-8 col-md-offset-2">
<form action="" method="post">
<fieldset>
<h1>Login</h1>
	Username<br><input name="username" type="text" placeholder="Username"><br>
	Password<br><input name="password" type="text" placeholder="******"><br><br>
	<input type="submit" class="btn btn-success" value="Login">
</fieldset>
</form>
<a href="register.php">Need to register?</a>
</div>

</body>
<script>
</script>
</html>