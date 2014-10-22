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
			 "\nAuthor:\t".$book->getAuthor().
			 "\nBook ID:\t".$book->getID().
			 "\nCopy ID:\t".$book->getCopyID();
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
}

?>