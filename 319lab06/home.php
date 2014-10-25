<?php
require_once('objects/user.php');
require_once('objects/book.php');
require_once('objects/library.php');
session_start();
$user = unserialize($_SESSION['user']);
?>

<html>
<head>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel='stylesheet' type='text/css'>
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
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

		<div class="row student" style="display:none">
			<div class="col-md-10">
				<h2>Student use cases</h2>
				<input id="returnBookText" type="text" placeholder="Copyid"> <button id="returnBookBtn" type="button">Return a book</button><br>
			</div>
		</div>

		<div class="row teacher" style="display:none">
			<div class="col-md-3">
				<h2>Librarian use cases</h2>
				<input id="addBookText" type="text" placeholder="BookName,Author,qty"> <button id="addBookBtn" type="button">Add a book</button><br>
				<input id="viewUserHistory" type="text" placeholder="Username"> <button id="viewLoansBtn" type="button">View history</button><br>
			</div>
			<div class="col-md-7">
				<h2>Loan History</h2>
				<table id="historyTable" class="table table-condensed">
					<TR class='info'><TH >Copy ID</TH><TH >Due Date</TH><TH >Date Returned</TH><TR>
				</table>
			</div>
		</div>
		<br><br><br>
	</div>
	<!-- Modal for when a table cell is clicked -->
	<div id="mymodal" class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
	        <h4 class="modal-title">Modal title</h4>
	      </div>
	      <input id="modal-copyid" type="hidden" value="">
	      <div class="modal-body">
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	        <button id="deleteBookBtn" type="button" class="btn btn-danger teacher" style="display:none" data-dismiss="modal">Delete</button>
	        <button id="checkoutBookBtn" type="button" class="btn btn-primary student" style="display:none" data-dismiss="modal">Checkout</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</body>
<script>
function showModal(title, body, copyID){
    	$('#mymodal .modal-title').html(title);
    	$('#mymodal .modal-body').html(body);
    	$('#modal-copyid').val(copyID);
        $('#mymodal').modal('show');
}
function getBookInfo(copyID){
	$.ajax({
		type  : "GET",
		url   : "router.php",
		data  : {"function": "getBookInfo","copyID": copyID},
		success: function(result){
			showModal("Information for book " + copyID, result, copyID);
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
function removeBook(){
	var input = $("#modal-copyid").val();
	$.ajax({
		type : "GET",
		url  : "router.php",
		data : {"function":"removeBook","copyID":input.trim()},
		success : function(result){
			updateLib();
		}
	});
}
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
$('#checkoutBookBtn').click(function(){
	var input = $("#modal-copyid").val();
	var username = "<?php echo $user->getUsername() ?>";
	// TODO validate input on server side
	$.ajax({
		type : "GET",
		url  : "router.php",
		data : {"function":"checkoutBook","copyID":input.trim(),"userID":username},
		success : function(result){
			updateLib();
		}
	});
});
$('#returnBookBtn').click(function(){
	var input = $("#returnBookText").val();
	var username = "<?php echo $user->getUsername() ?>";
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
	if(<?php echo $user->isLib() ?>)
		$(".teacher").css("display","");
	else
		$(".student").css("display","");
	$('#deleteBookBtn').click(removeBook);
});
</script>
</html>
