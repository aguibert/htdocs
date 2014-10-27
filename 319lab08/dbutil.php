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
}
?>