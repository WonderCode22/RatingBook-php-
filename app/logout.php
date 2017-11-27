<?php
	session_start();
	$homepage = '/app/index.php';
	$_SESSION['logout'] = 'logout';
	if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
		unset($_SESSION['user']);
		
		header('Location: ' . $homepage);
	}
?>
