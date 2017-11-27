<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/initialize.php');
	
	$single_book_page = '/app/single-book.php?isbn='.$_POST['isbn'];

	if (isset($_POST['submit'])) {
		// Get provided user data
		$user_id = $app->user->user_id;
		$isbn = $_POST['isbn'];
		$comment = $_POST['comment'];
		$rating = $_POST['rating'];
		
		// Try to rate the book
		if ($app->rate_book($isbn, $user_id, $comment, $rating)) {
			// Add message to session
			$_SESSION['rate_book'] = 'success';
			header('Location: ' . $single_book_page);
		} else {
			$_SESSION['rate_book'] = 'failed';
			header('Location: ' . $single_book_page);
		}
	} else {
		header('Location: /app/index.php');
	}
	
?>
