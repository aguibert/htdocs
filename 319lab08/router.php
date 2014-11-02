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
	if($function == 'getfollowers') {
		DB::getFollowers($_GET['username']);
		return;
	}
	if($function == 'getfollowing') {
		DB::getFollowing($_GET['username']);
		return;
	}
	if($function == 'getlistoffollowable') {
		DB::getListFollowable($_GET['username']);
		return;
	}

	if($function == 'setFollow') {
		DB::setFollower($_GET['followable'], $_GET['username']);
		return;
	}
}
?>