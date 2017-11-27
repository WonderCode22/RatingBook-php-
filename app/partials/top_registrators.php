<h2>Top Registers</h2>
<?php if (!empty($top_registrators)) : ?>
<table class="table">
	<tr>
		<th>Name</th>
		<th>City</th>
		<th>No. of books registered</th>
	</tr>
	<?php foreach($top_registrators as $user) : ?>
	<tr>
		<td class="col-md-6"><?php echo $user->user_name; ?></td>
		<td class="col-md-5"><?php echo $user->city; ?></td>
		<td class="col-md-1"><?php echo $user->no_of_books; ?></td>
	<td>
	<?php endforeach; ?>
</table>
<?php else : ?>
	<div class="alert alert-danger">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<p>There are no users in the database.</p>
	</div>
<?php endif; ?>
