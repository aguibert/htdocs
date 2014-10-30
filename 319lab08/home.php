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
		<div id="section-messages"></div>
		<hr>
		<div id="section-follow">
			<h2>People you follow will be shown here</h2>
		</div>
		<hr>
		<div id="section-followers">
			<h2>People who follow you will be shown here</h2>
		</div>
		<hr>
		<div id="section-post" class="input-group">
			<input id="message-text" type="text" placeholder="message" class="form-control"></input>
			<div class="input-group-btn">
				<button id="message-send-btn" type="button" class="btn btn-primary">Send</button>
			</div>
		</div>
	</div>
<script>
$('#navbar-logout').click(function(){
	window.location = window.location.pathname.replace("home.php", ""); 
});
function getMessages(){
	var username = "<?php echo $_SESSION['user']; ?>";

	$.ajax({
		type	:"GET",
		url		:"router.php",
		data	:{
					"function"	: "getmessages",
					"username"	: username
				},
		success :function(result) {
			$('#section-messages').html(result);
		}
	});
}

$('#message-send-btn').click(function() {
	var username = "<?php echo $_SESSION['user']; ?>";
	var messageText = $('#message-text').val();
	$.ajax({
		type:"GET",
		url:"router.php",
		data:{"function":"postmessage","username":username,"msg":messageText},
		success:function(result){
			console.log(result)
		}
	});
});


$(document).ready(function(){
	getMessages();
});
</script>
</html>
