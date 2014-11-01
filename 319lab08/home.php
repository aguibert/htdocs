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
	<div class="row">
		<div class="col-md-2 col-md-offset-1">
			<div>
				<h2>Following</h2>
				<button id="follow-button" class="btn btn-primary btn-block btn-xs" data-toggle="modal" data-target="#follow-modal">Follow</button>
				<div id="section-following">
				</div>
			</div>
		</div>
		<div id="sections" class="col-md-6">
			<div id="section-head" class="jumbotron">
				<h1>Welcome <?php echo $user ?></h1>
			</div>
			<hr>
			<div id="section-post" class="input-group">
				<input id="message-text" type="text" placeholder="message" class="form-control"></input>
				<div class="input-group-btn">
					<button id="message-send-btn" type="button" class="btn btn-primary">Send</button>
				</div>
			</div>
			<hr>
			<div id="section-messages"></div>
		</div>
		<div class="col-md-2">
			<div>
				<h2>Followers</h2>
				<div id="section-followers">
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="follow-modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Follow Someone</h4>
				</div>
				<div class="modal-body">
					<div id="follow-list-area" class="list-group">
						
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<script>
$('#navbar-logout').click(function(){
	window.location = window.location.pathname.replace("home.php", ""); 
});
function getMessages(){
	var username = "<?php echo $_SESSION['user']; ?>";

	$.ajax({
		type :"GET",
		url	 :"router.php",
		data :{
			"function" : "getmessages", 
			"username"	: username
		},
		success :function(result) {
			$('#section-messages').html(result);
		}
	});
}

function getFollowers(){
	var username = "<?php echo $_SESSION['user']; ?>";

	$.ajax({
		type :"GET",
		url	 :"router.php",
		data :{
			"function" : "getfollowers", 
			"username"	: username
		},
		success :function(result) {
			$('#section-followers').html(result);
		}
	});
}

function getFollowing(){
	var username = "<?php echo $_SESSION['user']; ?>";

	$.ajax({
		type :"GET",
		url	 :"router.php",
		data :{
			"function" : "getfollowing", 
			"username"	: username
		},
		success :function(result) {
			$('#section-following').html(result);
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
			getMessages();
		}
	});
});

$('#follow-button').click(function() {
	var username = "<?php echo $_SESSION['user']; ?>";
	$.ajax({
		type:"GET",
		url:"router.php",
		data:{"function":"getlistoffollowable","username":username},
		success:function(result){
			$('#follow-list-area').html(result);
		}
	});
});


$(document).ready(function(){
	getMessages();
	getFollowers();
	getFollowing();
});
</script>
</html>
