<?php
require_once('dbutil.php');

if(isset($_GET['function'])){
	$function = $_GET['function'];

	if($function == 'getmessages') {
		DB::getMessages($_GET['username']);
		return;
	}

	if($function == 'postmessage') {
		DB::postMessage($_GET['username'],$_GET['msg']);
		return;
	}
}
?>