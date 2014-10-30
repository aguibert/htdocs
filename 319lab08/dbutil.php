<?php

// Constants for storing database credentials
////// DB319ALL
$dbUsername = "u319all";
$dbPassword = "024IjLaMj4dI";
$dbServer = "mysql.cs.iastate.edu"; 
$dbName   = "db319all";
///// Localhost
// $dbUsername = "root";
// $dbPassword = "";
// $dbServer   = "localhost";
// $dbName     = "test"; 
class DB
{
	static $conn = null;

	//connection to the database
	function getConnection(){
		if(is_resource(self::$conn))
			return self::$conn;

		global $dbUsername, $dbPassword, $dbServer, $dbName;
		self::$conn = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbName);
		if(mysqli_connect_errno()){
			die("Failed to connect to MYSQL: " . mysqli_connect_errno());
			return;
		}
		return self::$conn;
	}

	function query($queryStr){
		$conn = self::getConnection();
		$result = mysqli_query($conn, $queryStr);
		return $result;
	}

	function createUserOrLogin($username){
		$result = self::query("SELECT * FROM usernames WHERE username='".$username."'");
		if(!mysqli_fetch_array($result)){
			// User does not exist, create user and follow themselves
			self::query("INSERT INTO usernames VALUES ('".$username."')");
			self::query("INSERT INTO followers VALUES ('".$username."','".$username."')");
		}
	}

	function getFollowing($userName) {
		$result = self::query("SELECT username FROM followers where followername='".$username."'");
		while($row = mysqli_fetch_array($result)) {
			// TODO output formating for each row in html

		}
	}

	function getFollowers($userName) {
		$result = self::query("SELECT followername FROM followers where username='".$username."'");
		while($row = mysqli_fetch_array($result)) {
			// TODO output formating for each row in html

		}
	}

	//followerName will be set to follow userName
	function setFollower($userName, $followerName) {
		self::query("INSERT INTO followers VALUES ('".$userName."','".$followerName."')");
	}

	function getMessages($userName) {
		$result = self::query("SELECT message.* 
			FROM message join followers 
			WHERE followers.followername='".$userName."' 
				and message.username=followers.followername ORDER BY posttime DESC");

		while($row = mysqli_fetch_array($result)) {
			echo "<p><strong>".$row['username']."</strong>  ".$row['posttime']."<br>".$row['msg']."</p>";
		}
	}

	function postMessage($userName, $msg) {
		self::query("INSERT INTO message VALUES ('".$userName."','".$msg."',CURRENT_TIMESTAMP)");
	}
}
?>