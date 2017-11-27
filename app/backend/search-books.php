<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/initialize.php');
	
	$search_page = '/app/search.php';
	if (isset($_GET['action']) && $_GET['action'] != "") {
		$search_page .= "?action=" . $_GET['action'];
	}
	$results = array();

	if (isset($_POST['isbn']) && !empty($_POST['isbn'])) {
		// Try to find book
		$isbn = $_POST['isbn'];
		if ($app->get_book_by_isbn($isbn)) {
			$search_results = $app->get_book_by_isbn($isbn);
			$results[] = $search_results;
			$_SESSION['search'] = $results;
		} else {
			$_SESSION['search'] = 'failed';
		}
		header('Location: ' . $search_page);exit;

	} else {
		if (isset($_POST['submit'])) {
			// Get provided user data
			$book_title = $_POST['book_title'];
			$is_exact_title = $_POST['book_title_checkbox'];
			$book_author = $_POST['book_author'];
			$is_exact_author = $_POST['book_author_checkbox'];
			$publish_year = $_POST['publish_year'];
			$publisher = $_POST['publisher'];
			
			// Try to find books
			if ($search_results = $app->get_searched_books($book_title, $book_author, $publish_year, $publisher, $is_exact_title, $is_exact_author)) {
				$_SESSION['search'] = $search_results;
				header('Location: ' . $search_page);
			} else {
				$_SESSION['search'] = 'failed';
				header('Location: ' . $search_page);exit;
			}
		}
	}

	header('Location: ' . $search_page);exit;
	
?>
