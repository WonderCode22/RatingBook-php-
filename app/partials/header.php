<div>
<img src="/app/img/book.jpg" class="img-thumbnail" width="100%">
</div>
<br>
<ul class="nav nav-tabs">
	<li <?php echo (isset($page) && $page == "index") ? 'class="active"' : ''  ?>><a href="/app/index.php"><h4>Home</h4></a></li>
	<li <?php echo (isset($page) && $page == "search") ? 'class="active"' : ''  ?>><a href="/app/search.php"><h4>Search Books</h4></a></li>

<?php if ($app->user) : ?>
	<li <?php echo (isset($page) && $page == "add-book") ? 'class="active"' : ''  ?>><a href="/app/add-book.php"><h4>Add New Book</h4></a></li>
	<li <?php echo (isset($page) && $page == "rate") ? 'class="active"' : ''  ?>><a href="/app/search.php?action=rate"><h4>Rate a Book</h4></a></li>
	<li <?php echo (isset($page) && $page == "dashboard") ? 'class="active"' : ''  ?>><a href="/app/dashboard.php"><h4>My Books</h4></a></li>
	<li <?php echo (isset($page) && $page == "statistics") ? 'class="active"' : ''  ?>><a href="/app/statistics.php"><h4>Statistics</h4></a></li>
	<li><a href="/app/logout.php"><h4>Logout</h4></a></li>
	
</ul>
<br>
<?php else : ?>
	<li <?php echo (isset($page) && $page == "statistics") ? 'class="active"' : ''  ?>><a href="/app/statistics.php"><h4>Statistics</h4></a></li>
	<li <?php echo (isset($page) && $page == "login") ? 'class="active"' : ''  ?>><a href="/app/login.php"><h4>Login</h4></a></li>
	<li <?php echo (isset($page) && $page == "register") ? 'class="active"' : ''  ?>><a href="/app/register.php"><h4>Register</h4></a></li>

</ul>
<br>
<?php endif; ?>
<?php if (isset($login) && $login == "success") : ?>

	<div class="alert alert-success">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<p>Login Successful.</p>
	</div>
<?php endif; ?>
<?php if (isset($logout) && $logout == "logout") : ?>

	<div class="alert alert-success">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<p>Logout Successful.</p>
	</div>
<?php endif; ?>
<?php if ($app->user) : ?>
	<div class="well well-sm">
		Welcome <?php echo $app->user->user_name; ?> from <?php echo $app->user->city . ", " . $app->user->country; ?>
	</div>
<?php endif; ?>	