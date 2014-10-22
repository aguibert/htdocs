<?php
require_once('dbutil.php');

class User
{
	private $_username;
	private $_password;
	private $_email;
	private $_phone;
	private $_bIsLib;
	private $_first;
	private $_last;

	public function __construct($user, $pass, $email, $phone, $bIsLib, $first, $last){
		$this->_username = $user;
		$this->_password = $pass;
		$this->_email    = $email;
		$this->_phone    = $phone;
		$this->_bIsLib   = $bIsLib;
		$this->_first    = $first;
		$this->_last     = $last;
	}

	public function getUsername(){
		return $this->_username;
	}
	public function isLib(){
		return $this->_bIsLib;
	}

	public static function viewLoanHistory($userName){
		$conn = DB::getConnection();
		$result = mysqli_query($conn, "SELECT * from loanHistory where Groupnumber=10 and Username='".$userName."'");
		echo "<TR class='info'><TH>Copy ID</TH><TH>Due Date</TH><TH>Date Returned</TH><TR>";
		while($row = mysqli_fetch_array($result)){
			echo "<TR><TD><B>".$row['Copyid']."<B></TD><TD>".$row['Duedate']."</TD><TD>".$row['Returnedondate']."</TD></TR>";
		}
	}

	public static function createRentalRecord($userid, $copyid){
		$conn = DB::getConnection();
		$query = "INSERT INTO loanHistory (Groupnumber, Username, Copyid, Duedate) ".
		    "VALUES (10, '".$userid."', ".$copyid.", DATE_ADD(CURDATE(), INTERVAL 5 DAY))";
		mysqli_query($conn, $query);
	}

	public static function checkoutBook($userid, $copyid){
		self::createRentalRecord($userid, $copyid);
		Library::deleteCopyFromShelf($copyid);
	}

	public static function returnBook($userid, $copyid){
		$conn = DB::getConnection();

		// Update the loanHistory table
		$query = "UPDATE loanHistory SET Returnedondate=CURDATE() ".
			"WHERE Groupnumber=10 and Username='".$userid."' and Copyid=".$copyid;
		$updateCount = mysqli_query($conn, $query);
		if($updateCount != 1){
			echo "Expected 1 row to be effect, intead: ". $updateCount;
			return;
		}

		// Now put the book back on the shelf
		Library::addCopyToShelf($copyid);
	}

	public static function doesUserExist($username){
		$exists = false;
		$conn = DB::getConnection();

		$result = mysqli_query($conn, "SELECT * from users ".
			"where Groupnumber=10 and username='". $username ."'");
		if($row = mysqli_fetch_array($result))
			$exists = true;

		return $exists;
	}

	public static function checkUserAndPass($username, $pass){
		$success = false;
		$conn = DB::getConnection();

		$result = mysqli_query($conn, "SELECT * from users ".
			"where Groupnumber=10 and username='". $username ."' and password='". md5($pass) ."'");
		if($row = mysqli_fetch_array($result))
			$success = true;

		return $success;
	}

	public static function isLibrarian($uname){
		$conn = DB::getConnection();
		$bLib;

		$result = mysqli_query($conn, "SELECT Librarian from users ".
			"where Groupnumber=10 and username='". $uname ."'");
		if($row = mysqli_fetch_array($result))
			$bLib = $row['Librarian'];

		return $bLib;
	}

	public static function createUser($uname, $pwhash, $email, $phone, $isLib, $first, $last){
		if(self::doesUserExist($uname)){
			echo "User " . $uname . " already exists for group 10.<BR>";
			return;
		}
		$conn = DB::getConnection();
		// Group#, Username, password, email, phone, lib?, First, Last
		mysqli_query($conn, "INSERT INTO users ".
			"VALUES (10,'".$uname."','".$pwhash."','".$email."','".$phone."',".$isLib.",'".$first."','".$last."')");

		return new User($uname, $pwhash, $email, $phone, $isLib, $first, $last);
	}

	public static function getUser($uname){
		$user = null;
		$conn = DB::getConnection();
		$result = mysqli_query($conn, "SELECT * FROM users ".
			"where Groupnumber=10 and username='". $uname ."'");
		if($row = mysqli_fetch_array($result)){
			$user   = $row['Username'];
			$pass   = $row['Password'];
			$email  = $row['Email'];
			$phone  = $row['Phone'];
			$bIsLib = $row['Librarian'];
			$first  = $row['Firstname'];
			$last   = $row['Lastname'];
			$user = new User($user, $pass, $email, $phone, $bIsLib, $first, $last); 
		}

		return $user;
	}
}
?>