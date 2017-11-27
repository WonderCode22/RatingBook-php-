<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/backend/initialize.php');
	// TODO Check if user is logged in ???

	// Check for session messages
	if (isset($_SESSION['search']) && !empty($_SESSION['search'])) {
		$search_results = $_SESSION['search'];
		unset($_SESSION['search']);
	}

	// Form action
	$form_action = "/app/backend/search-books.php";
	if (isset($_GET['action']) && $_GET['action'] != "") {
		$form_action .= "?action=" . $_GET['action'];
	}
?>

<!DOCTYPE html>
<html>
<head>

	<title>
	<?php if (isset($_GET['action']) && ($_GET['action'] == "rate")) : ?>
	<?php echo "Rate Book"?>
	<?php else : ?>
	<?php echo "Search"?>
	<?php endif; ?>
	</title>
	<script type="text/javascript" src="/app/js/jquery.js"></script>
	<script type="text/javascript" src="/app/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/app/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/app/css/style.css">
</head>
<div class="container">
<body>
	<div>
		<?php include(__DIR__ . '/partials/header.php'); ?>
	</div>

	<div>
		<h2>
		<?php if (isset($_GET['action']) &&$_GET['action'] == "rate") : ?>
	<?php echo "Search a Book to Rate"?>
	<?php else : ?>
	<?php echo "Search"?>
	<?php endif; ?>
</h2>
		<form action="<?php echo $form_action; ?>" method="post">

			<div class="form-group">
				<label for="isbn">ISBN:</label>
				<input type="text" name="isbn" placeholder="ISBN" id="isbn" class="form-control">
			</div>

			<div class="form-group">
				<label for="booktitle">Title:</label>
				<input type="text" name="book_title" placeholder="Title" id="book_title" class="form-control">
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="book_title_checkbox"> Exact Match Only
				</label>
			</div>
			

			<div class="form-group">
				<label for="bookauthor">Author:</label>
				<input type="text" name="book_author" placeholder="Author" id="book_author" class="form-control">
			</div>

			<div class="checkbox">
				<label>
					<input type="checkbox" name="book_author_checkbox"> Exact Match Only
				</label>
			</div>
			

			<div class="form-group">
				<label for="publishyear">Publishing Year:</label>
				<input type="text" name="publish_year" placeholder="Year" id="publish_year" class="form-control">
			</div>

			<div class="form-group">
				<label for="publisher">Publisher:</label>
				<input type="text" name="publisher" placeholder="Publisher" id="publisher" class="form-control">
			</div>

		
				<input type="submit" value="Search" name="submit" class="btn btn-primary">
			<br><br>

		</form>

		<div>
			<?php if (isset($search_results) && $search_results == "failed") : ?>
			<div class="alert alert-warning">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<h3>No books found. Please try a different search.</h3>
			</div>
			<?php endif; ?>
		
		</div>

<?php if ((isset($search_results) && $search_results !== "failed")) : ?>
	<h2>Search results</h2>
	<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<p>Click title to rate book, and see all the ratings.</p>
	</div>
<table class="table">
	<tr>
		<th class="col-md-2"> 	&nbsp;Cover</th>
		<th>Title</th>
		<th>Author</th>
		<th>Publish Year</th>
	</tr>
	<?php foreach ($search_results as $book) : ?>
	<tr>
		<td><img src="<?php echo $book->book_image; ?>" width="80" class="img-thumbnail" /></td>
		<td><br><br><a class="rate-book" href="/app/single-book.php?isbn=<?php echo $book->isbn; ?>"><?php echo $book->book_title; ?></a>	
		
		<?php if($app->check_if_users_book($book->isbn)) : ?>
		<?php echo " | &nbsp;&nbsp;<a class='edit-book' href='/app/edit-book.php?isbn=".$book->isbn."'><button class='btn btn-info btn-xs'>Edit </button></a> <a class='delete-book' href='/app/backend/delete-book.php?isbn=".$book->isbn."'><button class='btn btn-warning btn-xs'>Delete</button></a>"; ?> 
		<?php endif; ?></td>

	
		<td><br><br><?php echo $book->book_author; ?></td>
		<td><br><br><?php echo $book->publish_year; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php endif; ?>

<script>
  var count=0;
  $(document).ready(function(){

        $("form").submit(function(){

           $("input[type=text]").each(function(){

               if($(this).val()=="")count++
       });
      var isAllowToSubmit=(count<5);
      if(!isAllowToSubmit) alert("Please input at least one field")
      count=0;  
      return isAllowToSubmit;
        }); 
     });
    </script>
    </div>
</body>
</html>

<?php
