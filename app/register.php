<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/backend/initialize.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<link rel="stylesheet" type="text/css" href="/app/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/app/css/style.css">
</head>
<body>
<div class="container">
	<div>
		<?php include(__DIR__ . '/partials/header.php'); ?>
	</div>
	<?php if (isset($register) && $register == "failed") : ?>
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<p>Registration failed. Try again.</p>
	<?php endif; ?>
	<div>
		<h1>Register</h1>
		<form action="/app/backend/register-user.php" method="post">
			<div class="form-group">
				<label for="username">User name:</label>
				<input type="text" name="username" placeholder="Username" id="username" required class="form-control">
			</div>
			<div class="form-group">
				<label for="password">Password:</label>
				<input type="text" name="password" placeholder="Password" id="password" required class="form-control">
			</div>
			<div class="form-group">
				<label for="dateofbirth">Date of Birth:</label>
				<input type="date" name="dateofbirth" id="dateofbirth" required class="form-control" min="1900-01-02" max="2016-10-31">
			</div>
			<label for="country">Location:</label>
			<div class="form-control">
				<select name="country" class="countries" id="countryId">
				<option value="">Select Country</option>
				</select>
				<select name="state" class="states" id="stateId">
				<option value="">Select State</option>
				</select>
				<select name="city" class="cities" id="cityId">
				<option value="">Select City</option>
				</select>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
				<script src="http://iamrohit.in/lab/js/location.js"></script>
			</div>
			<br>
			<div class="form-group">
				<label for="email">E-mail:</label>
				<input type="email" name="email" placeholder="example@example.com" id="email" required class="form-control">
			</div>
			<div>
				<input type="submit" value="Register" name="submit" class="btn btn-primary">
			</div>
		</form>
	</div>
</div>
<script type="text/javascript" src="/app/js/jquery.js"></script>
	<script type="text/javascript" src="/app/js/bootstrap.min.js"></script>
</body>
</html>
