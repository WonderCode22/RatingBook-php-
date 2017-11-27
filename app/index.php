<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/backend/initialize.php');

	if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
		$login = $_SESSION['login'];
		unset($_SESSION['login']);
	}

	if (isset($_SESSION['logout']) && !empty($_SESSION['logout'])) {
		$logout = $_SESSION['logout'];
		unset($_SESSION['logout']);
	}

	$latest_books = $app->get_latest_books();
	$top_rated_books = $app->get_top_rated_books();
	$active_raters = $app->get_active_raters();
	$top_registrators = $app->get_top_registrators();
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>RateYourBook.com</title>
	<link rel="stylesheet" type="text/css" href="/app/css/bootstrap.min.css">
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<link rel="stylesheet" type="text/css" href="/app/css/style.css">
</head>
<body>
	<div class="container">
		<div>
			<?php include(__DIR__ . '/partials/header.php'); ?>
		</div>
		<div>
			<?php include(__DIR__ . '/partials/latest_books.php'); ?>
			<?php include(__DIR__ . '/partials/top_rated_books.php'); ?>
			<?php include(__DIR__ . '/partials/active_raters.php'); ?>
			<?php include(__DIR__ . '/partials/top_registrators.php'); ?>
		</div>
	<script type="text/javascript" src="/app/js/jquery.js"></script>
	<script type="text/javascript" src="/app/js/bootstrap.min.js"></script>
</body>
</html>
