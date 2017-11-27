<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/initialize.php');

	$register_page = '/app/register.php';
	$login_page = '/app/login.php';

	if (isset($_POST['submit'])) {
		// Get provided user data
		$username = $_POST['username'];
		$password = $_POST['password'];
		$date_of_birth = $_POST['dateofbirth'];
		$city = $_POST['city'];
		$country = $_POST['country'];
		$email = $_POST['email'];


		// Try to rgister the user
		if ($app->register_user($username, $password, $date_of_birth, $city, $country, $email)) {
			// Add message to session
			$_SESSION['register'] = 'success';
			header('Location: ' . $login_page);
		} else {
			$_SESSION['register'] = 'failed';
			header('Location: ' . $register_page);
		}
	} else {
		header('Location: ' . $register_page);
	}
	
?>
