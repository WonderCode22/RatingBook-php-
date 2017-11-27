<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/initialize.php');
	
	$login_page = '/app/login.php';
	$homepage = '/app/index.php';

	if (isset($_POST['submit'])) {
		// Get provided username and password
		$username = $_POST['username'];
		$password = $_POST['password'];

		// Try to log in the user
		if ($user = $app->login_user($username, $password)) {
			// Add message to session
			$_SESSION['login'] = 'success';
			$_SESSION['user'] = $user;
			header('Location: ' . $homepage);
		} else {
			$_SESSION['login'] = 'failed';
			header('Location: ' . $login_page);
		}
	} else {
		header('Location: ' . $login_page);
	}
	
?>
