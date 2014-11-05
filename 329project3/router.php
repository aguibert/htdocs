<?php
require_once('objects/library.php');
require_once('objects/user.php');

if(isset($_GET['function'])){
	$function = $_GET['function'];

	if($function === 'showLib'){
		Library::showLib();
		return;
	}
	if($function === 'addBook'){
		Library::addBook($_GET['title'], $_GET['author'], $_GET['qty']);
		return;
	}
	if($function === 'removeBook'){
		Library::deleteCopy($_GET['copyID']);
		return;
	}
	if($function == 'getBookInfo'){
		$book = Library::getBook($_GET['copyID']);
		echo "Title:\t\t".$book->getTitle().
			 "<BR>Author:\t".$book->getAuthor().
			 "<BR>Book ID:\t".$book->getID().
			 "<BR>Copy ID:\t".$book->getCopyID();
	 	return;
	}
	if($function == 'checkoutBook'){
		User::checkoutBook($_GET['userID'], $_GET['copyID']);
		return;
	}
	if($function == 'returnBook'){
		User::returnBook($_GET['userID'], $_GET['copyID']);
		return;
	}
	if($function == 'viewLoans'){
		$userName = $_GET['user'];
		$exact = $_GET['exact'];
		User::viewLoanHistory($userName, $exact);
		return;
	}
	if($function == 'viewCheckOut'){
		$userName = $_GET['userID'];
		User::viewCheckedOutBook($userName);
		return;
	}
	if($function == 'validate'){
		$bookName = $_GET['bookName'];
		$author   = $_GET['author'];
		$qty      = $_GET['qty'];
		if(!ctype_alnum($bookName)){
			echo "Invalid bookname: " .$bookName;
			return;
		}
		if(!ctype_alnum($author)){
			echo "Invalid author: " .$author; 
			return;
		}
		if(!ctype_digit($qty)){
			echo "Invalid qty: " .$qty;
			return;
		}
		echo "PASSED";
		return;
	}	
}
?>