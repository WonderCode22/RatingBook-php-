<h2>Top Raters</h2>
<?php if (!empty($active_raters)) : ?>
<table class="table">
	<tr>
		<th>Name</th>
		<th>City</th>
		<th>No. of ratings</th>
	</tr>
	<?php foreach($active_raters as $rater) : ?>
	<tr>
		<td class="col-md-6"><?php echo $rater->user_name; ?></td>
		<td class="col-md-5"><?php echo $rater->city; ?></td>
		<td class="col-md-1"><?php echo $rater->no_of_ratings; ?></td>
	<td>
	<?php endforeach; ?>
</table>
<?php else : ?>
	<div class="alert alert-warning">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<p>There are no users in the database.</p>
	</div>
<?php endif; ?>
