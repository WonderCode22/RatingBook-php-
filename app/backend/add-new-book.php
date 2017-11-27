<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/initialize.php');
	
	$dashboard_page = '/app/dashboard.php';
	$add_newbook_page = '/app/add-book.php';

	if (isset($_POST['submit'])) {
		// Get provided user data
		$isbn = $_POST['isbn'];
		$book_title = $_POST['booktitle'];
		$book_author = $_POST['bookauthor'];
		$publish_year = $_POST['publishyear'];
		$publisher = $_POST['publisher'];
		$book_image = $_POST['bookimage'];
		$user_id = $app->user->user_id;
		$date_added = new DateTime();
		$date_added = $date_added->format('Y-m-d H:i:s');
		
		// Try to add the book
		if ($app->add_new_book($isbn, $book_title, $book_author, $publish_year, $publisher, $book_image, $user_id, $date_added)) {
			// Add message to session
			$_SESSION['add_book'] = 'success';
			header('Location: ' . $dashboard_page);
		} else {
			$_SESSION['add_book'] = 'failed';
			header('Location: ' . $add_newbook_page);
		}
	} else {
		header('Location: ' . $add_newbook_page);
	}
	
?>
