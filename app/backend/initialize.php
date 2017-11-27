<?php
	session_start();
	
	// Include database configuration
	require_once(__DIR__ . '/config.php');
	// Include main app class
	require_once(__DIR__ . '/books-app.php');

	$app = new BooksApp();

	$uri = $_SERVER['REQUEST_URI'];
	$temp = explode('/', $uri);
	$uri = array_pop($temp);
	$uri_param = explode('?', $uri);
	if (count($uri_param) == 2) {
		$get = $uri_param[1];
		$get = explode('=', $get);
		$param = $get[0];
		$value = $get[1];
	}
	$page = get_page($uri_param);

	if (isset($value) && $value == "rate") {
		$page = "rate";
	}
	
	function get_page($uri_param = array()) {
		$file = $uri_param[0];
		$file = explode('.', $file);
		return $page = $file[0];
	}
?>
