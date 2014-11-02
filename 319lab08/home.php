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
				<h2>You Are Following</h2>
				<hr>
				<div id="section-following">
				</div>
				<hr>
				<button id="follow-button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#follow-modal">Follow</button>
			</div>
		</div>
		<div id="sections" class="col-md-6">
			<div id="section-head" class="jumbotron">
				<h1 class='text-center'>Welcome <?php echo $user ?></h1>
			</div>
			<hr>
			<div id="section-post" class="input-group">
				<input id="message-text" type="text" placeholder="Type a message here" class="form-control"></input>
				<div class="input-group-btn">
					<button id="message-send-btn" type="button" class="btn btn-primary">Send</button>
				</div>
			</div>
			<hr>
			<div id="section-messages" style="overflow-y:auto;height:500px"></div>
		</div>
		<div class="col-md-2">
			<div>
				<h2>Your Followers</h2>
				<hr>
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
						<div align="center">
							<img src="images/spinner.gif" />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<script>
var gFollowers;
$('#navbar-logout').click(function(){
	window.location = window.location.pathname.replace("home.php", ""); 
});
function addMessage(username, posttime, msg){
    $(" \
    	<div class='panel panel-primary'> \
    		<div class='panel-heading'> \
    			<strong>" + username + "</strong>   \
    			<div class='pull-right'>" + (new Date(posttime)).toLocaleString() + "</div> \
    		</div> \
    		<div class='panel-body' style='word-wrap:break-word'>" + msg + "</div> \
    	</div> \
    ").hide().prependTo('#section-messages').fadeIn("slow").slideDown();
}
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
			var messages = JSON.parse(result);
			for(i = messages.length-1; i >= 0; i--){
				addMessage(messages[i]['username'], messages[i]['posttime'], messages[i]['msg'])
			}
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
			var followers = JSON.parse(result);
			gFollowers = followers;
			var newHTML = "";
			for(person in followers){
				newHTML += "<h5>" + followers[person] + "</h5>";
			}
			$('#section-followers').html(newHTML);
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
			var following = JSON.parse(result);
			var newHTML = "";
			for(i in following)
				newHTML += "<h5>"+following[i]+"</h5>";
			$('#section-following').html(newHTML);
		}
	});
}

function postMessageToDB(username, messageText){
	$.ajax({
		type:"GET",
		url:"router.php",
		data:{"function":"postmessage","username":username,"msg":messageText}
	});
}

$('#follow-button').click(function() {
	var username = "<?php echo $_SESSION['user']; ?>";
	$.ajax({
		type:"GET",
		url:"router.php",
		data:{"function":"getlistoffollowable","username":username},
		success:function(result){
			$('#follow-list-area').html(result);

			$('.list-group-item').click(function() {
				var followable = $(this).clone().children().remove().end().text();

				$.ajax({
					type :"GET",
					url	 :"router.php",
					data :{
						"function" 		: "setFollow", 
						"followable"	: followable,
						"username"		: username 
					},
					success :function(result) {
						$('#follow-modal').modal('toggle');
						getMessages();
						getFollowing();
					}
				});
			});			
		}
	});
});


$(document).ready(function(){
	var username = "<?php echo $_SESSION['user']; ?>";
	socket = new WebSocket("ws://127.0.0.1:8000/se319lab08");

	function sendPayload(action, data){
	   var payload = {};
	   payload.action = action; 
	   payload.data = data; 
	   socket.send(JSON.stringify(payload));
	}

	getMessages();
	getFollowers();
	getFollowing();

	$('#message-send-btn').click(function() {
		var data = {};
		data.username = username;
		data.timestamp = Date.now();
		data.messageText = $('#message-text').val();
		data.followers = gFollowers; 
		sendPayload('postMessage', data);
		postMessageToDB(data.username, data.messageText);
		$('#message-text').val("");
	});

	socket.onopen = function (msg) {
		var data = {};
		data.username = username;
		sendPayload('PairClient', data);
	}
	socket.onmessage = function (msg) {
		console.log("got data: " + msg.data);
		var msgData = JSON.parse(msg.data).data;
		addMessage(msgData.username, msgData.timestamp, msgData.messageText);
	};
});
</script>
</html>
