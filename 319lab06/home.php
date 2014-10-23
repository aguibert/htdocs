<?php
require_once('objects/user.php');
require_once('objects/book.php');
require_once('objects/library.php');
session_start();
$user = unserialize($_SESSION['user']);
?>

<html>
<head>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">	
</head>
<body>
	<nav class="navbar navbar-default" role="navigation">
		<p class="navbar-text navbar-right" style="padding-right:1cm"><?php echo $user->getUsername() ?></p>
	</nav>

	<div class="col-md-offset-1">
		<div class="row">
			<div class="col-md-10">
				<h1>Library</h1>
				<table id="lib" class="table table-bordered">
				</table>
			</div>
		</div>

		<div id="studentUseCases" class="row">
			<div class="col-md-10">
				<input id="checkoutBookText" type="text" placeholder="Copyid"> <button id="checkoutBookBtn" type="button">Checkout a book</button><br>
				<input id="returnBookText" type="text" placeholder="Copyid"> <button id="returnBookBtn" type="button">Return a book</button><br>
			</div>
		</div>

		<div id="useCases" class="row">
			<div class="col-md-3">
				<h2>Librarian use cases</h2>
				<input id="addBookText" type="text" placeholder="BookName,Author,qty"> <button id="addBookBtn" type="button">Add a book</button><br>
				<input id="rmvBookText" type="text" placeholder="Bookid"> <button id="rmvBookBtn" type="button">Remove book</button><br>
				<input id="viewUserHistory" type="text" placeholder="Username"> <button id="viewLoansBtn" type="button">View history</button><br>
			</div>
			<div class="col-md-7">
				<h2>Loan History</h2>
				<table id="historyTable" class="table table-condensed">
					<TR class='info'><TH colspan='3'>Copy ID</TH><TH colspan='3'>Due Date</TH><TH colspan='3'>Date Returned</TH><TR>
				</table>
			</div>
		</div>
		<br><br><br>
	</div>
</body>
<script>
function getBookInfo(copyID){
	$.ajax({
		type  : "GET",
		url   : "router.php",
		data  : {"function": "getBookInfo","copyID": copyID},
		success: function(result){
			alert(result);
		}
	});
}

function updateLib(){
	$.ajax({
		type : "GET",
		url  : "router.php",
		data : {"function":"showLib"},
		success : function(result){
			$("#lib").html(result);
			$('.book').click(function(){
				getBookInfo($(this).html());
			})
		} 
	});
};
$('#viewLoansBtn').click(function(){
	var input = $('#viewUserHistory').val();

	$.ajax({
		type : "GET",
		url	 : "router.php",
		data : {"function" :"viewLoans", "user"	:input},
		success	: function(result){
			$('#historyTable').html(result);
		}
	});
	$('#viewUserHistory').val("");
});
$('#addBookBtn').click(function(){
	var input = $("#addBookText").val();
/*	var reg = /^[a-zA-Z0-9 ]+[=]{1}[ ]*[0-9]{1,2}$/;
	if(!reg.test(input)){
		alert("Please enter in the form bookName=qty");
		return;
	}
	var res = input.split("=");
	lib.addBook(res[0].trim(), Number(res[1].trim()));
	lib.showLib();*/
	var res = input.split(",");
	$.ajax({
		type : "GET",
		url  : "router.php",
		data : {"function":"addBook","title":res[0],"author":res[1],"qty":res[2]},
		success : function(result){
			updateLib();
		}
	});
	$("#addBookText").val("");
});
$('#rmvBookBtn').click(function(){
	var input = $("#rmvBookText").val();
	/*
	var reg = /^[a-zA-Z0-9 ]+$/;
	if(!reg.test(input)){
		alert("Please enter a valid book name");
		return;
	}
	lib.removeBook(input.trim());
	lib.showLib();
	*/
	$.ajax({
		type : "GET",
		url  : "router.php",
		data : {"function":"removeBook","copyID":input.trim()},
		success : function(result){
			updateLib();
		}
	});
	$("#addBookText").val("");
});
$('#checkoutBookBtn').click(function(){
	var input = $("#checkoutBookText").val();
	var username = "<?php echo $user->isLib() ?>";
	// TODO validate input on server side
	$.ajax({
		type : "GET",
		url  : "router.php",
		data : {"function":"checkoutBook","copyID":input.trim(),"userID":username},
		success : function(result){
			updateLib();
		}
	});
	$("#checkoutBookText").val("");
});
$('#returnBookBtn').click(function(){
	var input = $("#returnBookText").val();
	var username = "<?php echo $user->isLib() ?>";
	$.ajax({
		type : "GET",
		url  : "router.php",
		data : {"function":"returnBook","copyID":input.trim(),"userID":username},
		success : function(result){
			updateLib();
		}
	});
	$("#returnBookText").val("");
})
$(document).ready(function(){
	updateLib();
	if(<?php echo $user->isLib() ?> == 0)
		$("#studentUseCases").css("display","none");
	else
		$("#useCases").css("display","none");
});
</script>
</html>
