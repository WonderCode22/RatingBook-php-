<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/backend/initialize.php');
	// Check if user is logged in
	if (!$app->user) {
		$_SESSION['forbiden_action'] = 'edit a book';
		header('Location: /app/login.php');exit;
	}

	if (!isset($_GET['isbn']) || empty($_GET['isbn'])) {
		header('Location: /app/dashboard.php');exit;
	}

	$isbn = $_GET['isbn'];

	// Check if book belongs to logged in user
	if (!$app->check_if_users_book($isbn)) {
		$_SESSION['edit'] = 'failed';
		header('Location: /app/dashboard.php');exit;
	}
	
	$book = $app->get_book_by_isbn($isbn);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit book</title>
	<link rel="stylesheet" type="text/css" href="/app/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/app/css/style.css">
	<script type="text/javascript" src="/app/js/jquery.js"></script>
	<script type="text/javascript" src="/app/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div>
			<?php include(__DIR__ . '/partials/header.php'); ?>
		</div>
		
			<h1>Edit book</h1>
			<div class="alert alert-info">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			NOTE: ISBN can not be changed. If you need to change the ISBN, first delete the book, then add it again with the new ISBN.
			</div>
			<form action="/app/backend/edit-user-book.php" method="post">
			
				<input type="hidden" name="isbn" value="<?php echo $book->isbn; ?>">

				<div class="form-group">
					<label for="booktitle">Title:</label>
					<input type="text" name="booktitle" placeholder="Title" id="booktitle" value="<?php echo $book->book_title; ?>" required class="form-control">
				</div>

				<div class="form-group">
					<label for="bookauthor">Author:</label>
					<input type="text" name="bookauthor" id="bookauthor" placeholder="Author" value="<?php echo $book->book_author; ?>" required class="form-control">
				</div>

				<div class="form-group">
					<label for="publishyear">Publishing Year:</label>
					<input type="text" name="publishyear" placeholder="Year" id="publishyear" value="<?php echo $book->publish_year; ?>" required class="form-control">
				</div>

				<div class="form-group">
					<label for="publisher">Publisher:</label>
					<input type="text" name="publisher" placeholder="Publisher" id="publisher" value="<?php echo $book->publisher; ?>" required class="form-control">
				</div>

				<div class="form-group">
					<label for="bookimage">Book Cover URL:</label>
					<input type="url" name="bookimage" placeholder="image URL" id="bookimage" value="<?php echo $book->book_image; ?>" required class="form-control">
				</div>

			
					<input type="submit" value="Save book" name="submit" class="btn btn-primary">
				

			</form>
		
	</div>
</body>
</html>
