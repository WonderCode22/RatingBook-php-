<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/initialize.php');
	
	$dashboard_page = '/app/dashboard.php';
	$edit_book_page = '/app/edit-book.php';

	if (isset($_POST['submit'])) {
		// Get provided user data
		$isbn = $_POST['isbn'];
		$book_title = $_POST['booktitle'];
		$book_author = $_POST['bookauthor'];
		$publish_year = $_POST['publishyear'];
		$publisher = $_POST['publisher'];
		$book_image = $_POST['bookimage'];
		
		// Try to edit the book
		if ($app->edit_book($isbn, $book_title, $book_author, $publish_year, $publisher, $book_image)) {
			// Add message to session
			$_SESSION['edit_book'] = 'success';
			header('Location: ' . $dashboard_page);
		} else {
			$_SESSION['edit_book'] = 'failed';
			header('Location: ' . $edit_book_page);
		}
	} else {
		header('Location: ' . $edit_book_page);
	}
	
?>
