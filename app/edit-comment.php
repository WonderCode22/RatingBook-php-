<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/backend/initialize.php');
	// Check if user is logged in
	if (!$app->user) {
		header('Location: /app/index.php');exit;
	}

	if (!isset($_GET['isbn']) || empty($_GET['isbn'])) {
		header('Location: /app/index.php');exit;
	}

	$isbn = $_GET['isbn'];
	$user_id = $app->user->user_id;

	// Check if user rated book before
	if (!$app->user_has_rated($isbn)) {
		header('Location: /app/index.php');exit;
	}
	
	$user_rating = $app->get_user_rating_of_book($isbn, $user_id);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit rating and comment</title>
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
			Rating edited successfully.
		</div>
		
		<?php endif; ?>
		
		<h3>Edit your comment</h3>
		<form action="/app/backend/rate-book.php" method="post" id="rating-form">
			<input type="hidden" name="isbn" value="<?php echo $isbn; ?>">
			<input id="rating" type="hidden" name="rating" value="<?php echo $user_rating->rating; ?>">
			<div class="form-group">
				<label for="comment">Comment:</label>
				<textarea class="form-control" rows="5" name="comment" form="rating-form" maxlength="2000"><?php echo $user_rating->comment; ?></textarea>
			</div>
				<p><b>Edit your rating:</b></p>
				<input id="input-rating" type="text" class="rating" data-size="sm" data-step="1" data-min="0" data-max="10" data-show-caption="false">
			<div>
				<input type="submit" value="Submit Rating" name="submit" class="btn btn-primary">
			</div>
		</form>
	</div>
	<script type="text/javascript" src="/app/js/jquery.js"></script>
	<script type="text/javascript" src="/app/js/bootstrap.min.js"></script>
	<script src="/app/js/star-rating.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		// initialize with defaults
		$("#input-rating").rating();
		$('#input-rating').rating('update', <?php echo $user_rating->rating; ?>);
		$('#input-rating').on('rating.change', function(event, value, caption) {
		    $("#rating").val(value);
		});
		$('#input-rating').on('rating.clear', function(event) {
		    $("#rating").val(0);
		});
	</script>
</body>
</html>
