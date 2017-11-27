<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/backend/initialize.php');

	// Check for session messages
	if (isset($_SESSION['add_book']) && !empty($_SESSION['add_book'])) {
		$add_book = $_SESSION['add_book'];
		unset($_SESSION['add_book']);
	}
	if (isset($_SESSION['delete_book']) && !empty($_SESSION['delete_book'])) {
		$delete_book = $_SESSION['delete_book'];
		unset($_SESSION['delete_book']);
	}
	if (isset($_SESSION['edit_book']) && !empty($_SESSION['edit_book'])) {
		$edit_book = $_SESSION['edit_book'];
		unset($_SESSION['edit_book']);
	}

	// Check if user is logged in
	if (!$app->user) {
		header('Location: /app/login.php');
		$_SESSION['forbiden_action'] = 'see your dashboard';
	}

	$user_books = $app->get_user_books();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Dashboard for <?php echo $app->user->user_name; ?></title>
	<link rel="stylesheet" type="text/css" href="/app/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/app/css/style.css">
	<script type="text/javascript" src="/app/js/jquery.js"></script>
	<script type="text/javascript" src="/app/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
	<?php include(__DIR__ . '/partials/header.php'); ?>
	<?php if (isset($add_book) && $add_book == "success") : ?>
		
	<div class="alert alert-success">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<p>Book added successfully.</p>
	</div>
	<?php endif; ?>
	
	<?php if (isset($delete_book) && $delete_book == "success") : ?>
	<div class="alert alert-success">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<p>Book deleted successfully.</p>
	</div>
	<?php endif; ?>
	
	<?php if (isset($edit_book) && $edit_book == "success") : ?>
	<div class="alert alert-success">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<p>Book edited successfully.</p>
	</div>
	<?php endif; ?>
	<?php if (isset($delete_book) && $delete_book == "failed") : ?>
	<div class="alert alert-danger">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<p>Delete error! There was an error. Try again.</p>
	</div>
	<?php endif; ?>
	<?php if (isset($edit_book) && $edit_book == "failed") : ?>
	<div class="alert alert-danger">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<p>Edit error! There was an error. Try again.</p>
	</div>
	<?php endif; ?>
	
	<h1>My Books</h1>
	<?php if (!empty($user_books)) : ?>
	<ul class="list-group">
		<?php foreach($user_books as $book) : ?>
			<li class="list-group-item">
			<div>

				<img src="<?php echo $book->book_image; ?>" width="80" class="img-thumbnail" style="display: inline-block;" />

				<div style="display: inline-block;">

					<a class="rate-book" href="/app/single-book.php?isbn=<?php echo $book->isbn; ?>" >
							&nbsp;	&nbsp; 
					<?php echo " ISBN: " . $book->isbn . " | Title: " . $book->book_title . " | Author: " . $book->book_author." |  Published in ".$book->publish_year." |  Publisher ".$book->publisher; ?>

					</a>   <br><br>
						&nbsp;	&nbsp;
		
					<a class="edit-book" href="/app/edit-book.php?isbn=<?php echo $book->isbn; ?>"><button class="btn btn-info btn-xs">Edit </button></a> <a class="delete-book" href="/app/backend/delete-book.php?isbn=<?php echo $book->isbn; ?>"><button class="btn btn-warning btn-xs">Delete</button></a>
					<a class="rate-book" href="/app/single-book.php?isbn=<?php echo $book->isbn; ?>"><button type="button" class="btn btn-primary btn-xs">Rate</button></a>
				
				</div>
			</div>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php else : ?>
	<div class="alert alert-warning">
	
		You did not add any books yet. <a href="/app/add-book.php">Add new book</a>
	</div>
	<?php endif; ?>

	
	<script>
		$( document ).ready(function() {
			$(".delete-book").each(function(index) {
				$(this).click(function(evt) {
					var response = confirm("This book will be deleted");
					if (!response) evt.preventDefault();
				});
			});
		});
	</script>
</div>

</body>
</html>
