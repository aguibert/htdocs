<?php
session_start();
$user = $_SESSION['user'];
?>

<html>
<head>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel='stylesheet' type='text/css'>
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-inverse" role="navigation">
		<ul class="nav navbar-nav navbar-right">
	        <li><button id="navbar-logout" type="button" class="btn btn-danger navbar-btn ">Logout</button></li>
			<li><h4 id="navbar-username" class="navbar-text" style="padding-right:1cm"><?php echo $user ?></h4></li>
		</ul>
	</nav>
	<div id="sections" class="col-md-8 col-md-offset-2">
		<div id="section-head" class="jumbotron">
			<h1>Welcome <?php echo $user ?></h1>
		</div>
		<hr>
		<div id="section-messages">
			<h2>Post messages here</h2>
		</div>
		<hr>
		<div id="section-follow">
			<h2>People you follow will be shown here</h2>
		</div>
		<hr>
		<div id="section-followers">
			<h2>People who follow you will be shown here</h2>
		</div>
		<hr>
		<div id="section-post">
			<h2>Post a new message here</h2>
		</div>
	</div>
<script>
$('#navbar-logout').click(function(){
	window.location = window.location.pathname.replace("home.php", ""); 
});
$(document).ready(function(){

});
</script>
</html>
