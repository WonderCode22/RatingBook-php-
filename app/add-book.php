<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/backend/initialize.php');
	// Check if user is logged in
	if (!$app->user) {
		header('Location: /app/login.php');
		$_SESSION['forbiden_action'] = 'add new book';
	}

if (isset($_SESSION['add_book']) && !empty($_SESSION['add_book'])) {
		$add_book = $_SESSION['add_book'];
		unset($_SESSION['add_book']);
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Add book</title>
	<link rel="stylesheet" type="text/css" href="/app/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/app/css/style.css">
</head>
<body>
<div class="container">
	<div>
		<?php include(__DIR__ . '/partials/header.php'); ?>
	</div>

	<?php if (isset($add_book) && $add_book == "failed") : ?>
		
	<div class="alert alert-danger">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<p>ERROR! Seems like a book with the same ISBN already exists. Try again with a new ISBN.</p>
	</div>
	<?php endif; ?>

	<div>
		<h1>Add new book</h1>

		<form action="/app/backend/add-new-book.php" method="post">

			<div class="form-group">
				<label for="isbn">ISBN:</label>
				<input type="text" name="isbn" placeholder="ISBN" id="isbn" required class="form-control">
			</div>

			<div class="form-group">
				<label for="booktitle">Title:</label>
				<input type="text" name="booktitle" placeholder="Title" id="booktitle" required class="form-control">
			</div>

			<div class="form-group">
				<label for="bookauthor">Author:</label>
				<input type="text" name="bookauthor" id="bookauthor" placeholder="Author" required class="form-control">
			</div>

			<div class="form-group">
				<label for="publishyear">Publishing Year:</label>
				<input type="text" name="publishyear" placeholder="Year" id="publishyear" required class="form-control">
			</div>

			<div class="form-group">
				<label for="publisher">Publisher:</label>
				<input type="text" name="publisher" placeholder="Publisher" id="publisher" required class="form-control">
			</div>

			<div class="form-group">
				<label for="bookimage">Book Cover URL:</label>
				<input type="url" name="bookimage" placeholder="image URL" id="bookimage" required class="form-control">
			</div>

			
				<input type="submit" value="Add Book" name="submit" class="btn btn-primary">
			<br><br>

		</form>
	</div>
	</div>
	<script type="text/javascript" src="/app/js/jquery.js"></script>
	<script type="text/javascript" src="/app/js/bootstrap.min.js"></script>
</body>
</html>
