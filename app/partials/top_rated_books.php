<h2>Top Rated Books</h2>
<?php if (!empty($top_rated_books)) : ?>
<table class="table home-books">
	<tr>
		<th class="col-md-1">Cover</th>
		<th class="col-md-5">Title</th>
		<th class="col-md-4">Author</th>
		<th class="col-md-1 text-center">Year</th>
		<th class="col-md-1 text-center">Average Rating</th>
	</tr>
	<?php foreach($top_rated_books as $book) : ?>
	<tr>
		<td><img src="<?php echo $book->book_image; ?>" width="50" class="img-thumbnail" /></td>
		<td>
			<a class="rate-book" href="/app/single-book.php?isbn=<?php echo $book->isbn; ?>"><?php echo $book->book_title; ?></a>
		</td>
		<td><?php echo $book->book_author; ?></td>
		<td class="text-center"><?php echo $book->publish_year; ?></td>
		<td class="text-center">

		<img src="/app/img/rating/<?php echo round($book->avg, 0, PHP_ROUND_HALF_UP); ?>.jpg" class="img-thumbnail" width="300" >

		</td>
	<?php endforeach; ?>
</table>
<?php else : ?>
	<div class="alert alert-danger">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<p>There are no ratings in the database.</p>
	</div>
<?php endif; ?>
