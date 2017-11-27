<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/backend/initialize.php');
	if (!isset($_GET['isbn']) || empty($_GET['isbn'])) {
		header('Location: /app/index.php');exit;
	}
	$isbn = $_GET['isbn'];
	$rate_error = array();
	// Check if user is logged in
	if (!$app->user) {
		$rate_error['login'] = true;
	}
	if ($app->user_has_rated($isbn)) {
		$rate_error['rated'] = true;
	}

	if (isset($_SESSION['rate_book']) && !empty($_SESSION['rate_book'])) {
		$rate_book = $_SESSION['rate_book'];
		unset($_SESSION['rate_book']);
	}

	$book = $app->get_book_by_isbn($isbn);

	$book_ratings = $app->get_book_ratings($isbn);

	
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $book->book_title ?></title>
	<link rel="stylesheet" type="text/css" href="/app/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/app/css/style.css">
	<link rel="stylesheet" type="text/css" href="/app/css/star-rating.min.css">
</head>
<body>
	<div class="container">
		<div>
			<?php include(__DIR__ . '/partials/header.php'); ?>
		</div>

		<?php if (isset($rate_book) && $rate_book == "success") : ?>
		
		<div class="alert alert-success">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			Book rated successfully.
		</div>
		
		<?php endif; ?>
		
		<div>

		<?php if ($book) : ?>

			<h2><?php echo $book->book_title; ?></h2>

			<table>
			<tr>
				<th class="col-md-5">
				</th>

				<th>
				</th>
			</tr>
			<tr>
				<td>
					<img src="<?php echo $book->book_image; ?>" width="120" class="img-thumbnail" />
				</td>
				<td>
				<br>
					<b>ISBN:</b> <?php echo $book->isbn; ?><br>
					<i><b>by</b> <?php echo $book->book_author; ?></i><br>
					<b>published in</b> <?php echo $book->publish_year; ?><br>
					<b>publisher:</b> <?php echo $book->publisher; ?><br>
					<b>registered by:</b> <?php echo $book->registered_by; ?><br>
					<b>registration date and time:</b> <?php echo $book->registration_date; ?><br>
					<b>Avarage rating:</b> <input id="read-rating" type="text" class="rating" data-size="sm" data-step="1" data-min="0" data-max="10" data-display-only="true">
				</td>
			</tr>
			</table>

			<?php if (!isset($rate_error['login'])) : ?>

				<?php if (isset($rate_error['rated'])) : ?>
				
				<div class="alert alert-info">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					You have already rated this book.
				</div>
				
				<?php else : ?>
			
				<h3>Rate this book</h3>
				<form action="/app/backend/rate-book.php" method="post" id="rating-form">
					<input type="hidden" name="isbn" value="<?php echo $book->isbn; ?>">
					<input id="rating" type="hidden" name="rating" value="0">
					<div class="form-group">
							<label for="comment">Comment:</label>
							<textarea class="form-control" rows="5" name="comment" form="rating-form" placeholder="Your thoughts on the book..." maxlength="2000"></textarea>
					</div>
						<p><b>Rating:</b></p>
						<input id="input-rating" type="text" class="rating" data-size="sm" data-step="1" data-min="0" data-max="10" data-show-caption="false">
					<div>
						<input type="submit" id="submit-rating" value="Submit Rating" name="submit" class="btn btn-primary">
					</div>
				</form>

				<?php endif; ?>

			<?php else : ?>
			
				<div class="alert alert-info">
					<a href="/app/login.php">Login</a> to rate this book.
				</div>

			<?php endif; ?>

			<?php if (!empty($book_ratings)) : ?>
				<br>
				<ul class="list-group">

				<?php $num = 1; ?>
				<?php if ($app->user) {
					$user_name = $app->user->user_name; 
					} else $user_name = "" ?>
				<?php foreach ($book_ratings as $rating) : ?>
					<li class="list-group-item">
						<?php echo $num; ?> | &nbsp;<b class="comment_title">User &nbsp;</b> <?php echo $rating->user_name; ?>,<br><br>&nbsp; &nbsp; &nbsp;
						<b class="comment_title">Rating&nbsp;</b> <img src="/app/img/rating/<?php echo $rating->rating; ?>.jpg" class="img-thumbnail" width="100" >
						<br>&nbsp; &nbsp; &nbsp;&nbsp;

<div class="panel panel-default">
<div class="panel-heading">
						<b class="comment_title">&nbsp;Comment</b> 
</div>
<div class="panel-body">
						<?php echo $rating->comment; ?>
						
						<?php if ($user_name == $rating->user_name) : ?>
						<a href="/app/edit-comment.php?isbn=<?php echo $book->isbn; ?>"><button class="btn btn-primary btn-xs">Edit</button></a>
						<?php endif; ?>
</div>
</div>
					</li>
					<?php $num = $num + 1; ?>
				<?php endforeach; ?>
				</ul>
			
			<?php endif; ?>
		<?php else : ?>
			
			<div class="alert alert-danger">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<p>ERROR! book with ISBN: <?php echo $isbn ?> not found in the database.</p>
			</div>
		
		<?php endif; ?>
		</div>
	</div>
	<script type="text/javascript" src="/app/js/jquery.js"></script>
	<script type="text/javascript" src="/app/js/bootstrap.min.js"></script>
	<script src="/app/js/star-rating.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		// initialize with defaults
		$("#read-rating").rating();
		$('#read-rating').rating('update', <?php echo $book->rating_avg; ?>);
		$("#input-rating").rating();
		$('#input-rating').on('rating.change', function(event, value, caption) {
		    $("#rating").val(value);
		});
		$('#input-rating').on('rating.clear', function(event) {
		    $("#rating").val(0);
		});
	</script>
</body>
</html>
