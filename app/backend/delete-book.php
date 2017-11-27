<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/initialize.php');
	
	// Check if user is logged in
	if (!$app->user) {
		$_SESSION['forbiden_action'] = 'delete a book';
		header('Location: /app/login.php');
		exit;
	}

	if (!isset($_GET['isbn']) || empty($_GET['isbn'])) {
		header('Location: /app/dashboard.php');
	}

	$isbn = $_GET['isbn'];

	if ($app->delete_book($isbn)) {
		$_SESSION['delete_book'] = 'success';
		header('Location: /app/dashboard.php');
		exit;
	} else {
		$_SESSION['delete_book'] = 'failed';
		header('Location: /app/dashboard.php');
		exit;
	}
?>
