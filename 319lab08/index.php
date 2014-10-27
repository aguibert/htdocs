<?php
session_start();	
require_once('dbutil.php');

if(isset($_POST['username'])){
	DB::createUserOrLogin($_POST['username']);
	$_SESSION['user'] = $_POST['username'];
	header('Location: home.php');
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
	Username<br><input name="username" type="text" placeholder="Username"><br><br>
	<input type="submit" class="btn btn-success" value="Login">
</fieldset>
</form>
</div>

</body>
<script>
</script>
</html>