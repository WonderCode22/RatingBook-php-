<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/backend/initialize.php');
	
	// Check for session messages
	if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
		$login = $_SESSION['login'];
		unset($_SESSION['login']);
	}

	if (isset($_SESSION['register']) && !empty($_SESSION['register'])) {
		$register = $_SESSION['register'];
		unset($_SESSION['register']);
	}

	if (isset($_SESSION['forbiden_action']) && !empty($_SESSION['forbiden_action'])) {
		$forbiden_action = $_SESSION['forbiden_action'];
		unset($_SESSION['forbiden_action']);
	}

	if ($app->user != false) {
		header('Location: /app/index.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="/app/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/app/css/style.css">
</head>
<body>
<div class="container">
	<div>
		<?php include(__DIR__ . '/partials/header.php'); ?>
	</div>

	<?php if (isset($login) && $login == "failed") : ?>
	<div class="alert alert-danger">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<p>Login failed. Try again.</p>
	</div>
	<?php endif; ?>

	<?php if (isset($register) && $register == "success") : ?>
	<div class="alert alert-success">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<p>Registration successful, you may now log in.</p>
	</div>
	<?php endif; ?>

	<?php if (isset($forbiden_action) && !empty($forbiden_action)) : ?>
	<div class="alert alert-warning">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<p>You must be logged in to <?php echo $forbiden_action; ?>.</p>
	</div>
	<?php endif; ?>

	<div>
		<h1>Login</h1>
		<form action="/app/backend/log-user.php" method="post">
			<div class="form-group">
				<label for="username">User Name:</label>
				<input type="text" id="username" name="username" placeholder="Username" required class="form-control">
			</div>	
			<div class="form-group">
				<label for="password">Password:</label>
				<input type="password" id="password" name="password" placeholder="Password" required class="form-control">
			</div>
			<div>
				<input type="submit" value="Login" name="submit" class="btn btn-primary">
			</div>
		</form>
	</div>
</div>
<script type="text/javascript" src="/app/js/jquery.js"></script>
	<script type="text/javascript" src="/app/js/bootstrap.min.js"></script>
</body>
</html>
