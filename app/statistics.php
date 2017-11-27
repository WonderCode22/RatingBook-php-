<?php
	// Initialize the books app
	// $app = new BooksApp();
	require_once(__DIR__ . '/backend/initialize.php');

	if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
		$login = $_SESSION['login'];
		unset($_SESSION['login']);
	}

	$books_by_year = $app->get_books_by_years();
	$books_by_publisher = $app->get_books_by_publisher();
	$users_by_age = $app->get_users_by_age();
	$users_by_country = $app->get_users_by_country();
	$users_by_city = $app->get_users_by_city();
	$books_by_author = $app->get_books_by_author();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Statistics</title>
	<link rel="stylesheet" type="text/css" href="/app/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/app/css/style.css">
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript" src="/app/js/jquery.js"></script>
	<script type="text/javascript" src="/app/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div>
			<?php include(__DIR__ . '/partials/header.php'); ?>
		</div>
		<div>
			<h2>Statistics</h2>

				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#books-per-year">Books per decade</a></li>
					<li><a data-toggle="tab" href="#books-per-publisher">Books by top 10 publishers</a></li>
					<li><a data-toggle="tab" href="#books-per-author">Books by top 5 authors</a></li>
					<li><a data-toggle="tab" href="#users-by-age">Users by age group</a></li>
					<li><a data-toggle="tab" href="#users-by-country">Users by Country</a></li>
					<li><a data-toggle="tab" href="#users-by-city">Users by City</a></li>
				</ul>

				<div class="tab-content">

					<div id="books-per-year" class="tab-pane active">
					<br>
					<div class="form-group">
					<label for="books-decade-type">Select chart type:</label>
						<select id="books-decade-type" name="chart-type" class="form-control">
							<option value="bar">Bar</option>
							<option value="line">Line</option>
							<option value="pie">Pie</option>
						</select>
						</div>
						<div id="books-decade" style="width: 100%; height: 500px;"></div>
					</div>

					<div id="books-per-publisher" class="tab-pane">
					<br>
					<div class="form-group">
					<label for="publishers-books-type">Select chart type:</label>
						<select id="publishers-books-type" name="chart-type" class="form-control">
							<option value="bar">Bar</option>
							<option value="line">Line</option>
							<option value="pie">Pie</option>
						</select>
						</div>
						<div id="publishers-books" style="width: 100%; height: 500px;"></div>
					</div>

					<div id="books-per-author" class="tab-pane">
					<br>
					<div class="form-group">
					<label for="authors-books-type">Select chart type:</label>
						<select id="authors-books-type" name="chart-type" class="form-control">
							<option value="bar">Bar</option>
							<option value="line">Line</option>
							<option value="pie">Pie</option>
						</select>
						</div>
						<div id="authors-books" style="width: 100%; height: 500px;"></div>
					</div>


					<div id="users-by-age" class="tab-pane">
						<br>
						<div class="form-group">
							<label for="users-age-group-type">Select chart type:</label>
							<select id="users-age-group-type" name="chart-type" class="form-control">
								<option value="bar">Bar</option>
								<option value="line">Line</option>
								<option value="pie">Pie</option>
							</select>
						</div>
						<div id="users-age-group" style="width: 100%; height: 500px;"></div>
					</div>

					<div id="users-by-country" class="tab-pane">
						<br>
						<div class="form-group">
						<label for="countries-users-type">Select chart type:</label>
						<select id="countries-users-type" name="chart-type" class="form-control">
							<option value="bar">Bar</option>
							<option value="line">Line</option>
							<option value="pie">Pie</option>
						</select>
						</div>
						<div id="countries-users" style="width: 100%; height: 500px;"></div>
					</div>

					<div id="users-by-city" class="tab-pane">
						<br>
						<div class="form-group">
						<label for="cities-users-type">Select chart type:</label>
						<select id="cities-users-type" name="chart-type" class="form-control">
							<option value="bar">Bar</option>
							<option value="line">Line</option>
							<option value="pie">Pie</option>
						</select>
						</div>
						<div id="cities-users" style="width: 100%; height: 500px;"></div>
					</div>

				</div>
			</div>
	
			<script type="text/javascript">
				google.charts.load('current', {'packages':['corechart']});
				
				google.charts.setOnLoadCallback(drawUsersAgeGroup);
				$("#users-age-group-type").change(function() {
					drawUsersAgeGroup(this.value);
				});
				function drawUsersAgeGroup(type) {
					var container = document.getElementById('users-age-group');

					var data = new google.visualization.arrayToDataTable([
						['Age group', 'Number of users'],
						<?php foreach($users_by_age as $age => $count) : ?>
						['<?php echo $age; ?>', <?php echo $count; ?>],
						<?php endforeach; ?>
					]);

					var options = {
					  title: 'Number of users per age group',
					  bar: {groupWidth: "60%"},
					  legend: { position: "right" },
					  pieSliceText: 'label',
					};

					switch(type) {
						case 'line':
							var chart = new google.visualization.LineChart(container);
							break;
						case 'pie':
							var chart = new google.visualization.PieChart(container);
							break;
						default:
							// bar
							var chart = new google.visualization.ColumnChart(container);
					}
					
					chart.draw(data, options);
				}

				google.charts.setOnLoadCallback(drawPublishersBooks);
				$("#publishers-books-type").change(function() {
					drawPublishersBooks(this.value);
				});
				function drawPublishersBooks(type) {
					var container = document.getElementById('publishers-books');

					var data = new google.visualization.arrayToDataTable([
						['Publisher', 'Number of books published'],
						<?php foreach($books_by_publisher as $publisher => $count) : ?>
						[<?php echo json_encode($publisher); ?>, <?php echo $count; ?>],
						<?php endforeach; ?>
					]);

					var options = {
					  title: 'Top 10 publishers by number of books',
					  bar: {groupWidth: "60%"},
					  legend: { position: "right" },
					  pieSliceText: 'label',
					};

					switch(type) {
						case 'line':
							var chart = new google.visualization.LineChart(container);
							break;
						case 'pie':
							var chart = new google.visualization.PieChart(container);
							break;
						default:
							// bar
							var chart = new google.visualization.ColumnChart(container);
					}
					
					chart.draw(data, options);
				}

				google.charts.setOnLoadCallback(drawAuthorsBooks);
				$("#authors-books-type").change(function() {
					drawAuthorsBooks(this.value);
				});
				function drawAuthorsBooks(type) {
					var container = document.getElementById('authors-books');

					var data = new google.visualization.arrayToDataTable([
						['Author', 'Number of books'],
						<?php foreach($books_by_author as $author => $count) : ?>
						[<?php echo json_encode($author); ?>, <?php echo $count; ?>],
						<?php endforeach; ?>
					]);

					var options = {
					  title: 'Top 5 authors by number of books',
					  bar: {groupWidth: "60%"},
					  legend: { position: "right" },
					  pieSliceText: 'label',
					};

					switch(type) {
						case 'line':
							var chart = new google.visualization.LineChart(container);
							break;
						case 'pie':
							var chart = new google.visualization.PieChart(container);
							break;
						default:
							// bar
							var chart = new google.visualization.ColumnChart(container);
					}
					
					chart.draw(data, options);
				}

				google.charts.setOnLoadCallback(drawBooksDecade);
				$("#books-decade-type").change(function() {
					drawBooksDecade(this.value);
				});
				function drawBooksDecade(type) {
					var container = document.getElementById('books-decade');

					var data = new google.visualization.arrayToDataTable([
						['Decade', 'Number of books published'],
						<?php foreach($books_by_year as $year => $count) : ?>
						['<?php echo $year; ?>.', <?php echo $count; ?>],
						<?php endforeach; ?>
					]);

					var options = {
					  title: 'Number of books per decade',
					  bar: {groupWidth: "60%"},
					  legend: { position: "right" },
					  pieSliceText: 'label',
					};

					switch(type) {
						case 'line':
							var chart = new google.visualization.LineChart(container);
							break;
						case 'pie':
							var chart = new google.visualization.PieChart(container);
							break;
						default:
							// bar
							var chart = new google.visualization.ColumnChart(container);
					}
					
					chart.draw(data, options);
				}

				google.charts.setOnLoadCallback(drawCountriesUsers);
				$("#countries-users-type").change(function() {
					drawCountriesUsers(this.value);
				});
				function drawCountriesUsers(type) {
					var container = document.getElementById('countries-users');

					var data = new google.visualization.arrayToDataTable([
						['Country', 'Number of users'],
						<?php foreach($users_by_country as $country => $count) : ?>
						['<?php echo $country; ?>', <?php echo $count; ?>],
						<?php endforeach; ?>
					]);

					var options = {
					  title: 'Top 10 countries by number of users',
					  bar: {groupWidth: "60%"},
					  legend: { position: "right" },
					  pieSliceText: 'label',
					};

					switch(type) {
						case 'line':
							var chart = new google.visualization.LineChart(container);
							break;
						case 'pie':
							var chart = new google.visualization.PieChart(container);
							break;
						default:
							// bar
							var chart = new google.visualization.ColumnChart(container);
					}
					
					chart.draw(data, options);
				}

				google.charts.setOnLoadCallback(drawCitiesUsers);
				$("#cities-users-type").change(function() {
					drawCitiesUsers(this.value);
				});
				function drawCitiesUsers(type) {
					var container = document.getElementById('cities-users');

					var data = new google.visualization.arrayToDataTable([
						['City', 'Number of users'],
						<?php foreach($users_by_city as $city => $count) : ?>
						['<?php echo $city; ?>', <?php echo $count; ?>],
						<?php endforeach; ?>
					]);

					var options = {
					  title: 'Top 10 cities by number of users',
					  bar: {groupWidth: "60%"},
					  legend: { position: "right" },
					  pieSliceText: 'label',
					};

					switch(type) {
						case 'line':
							var chart = new google.visualization.LineChart(container);
							break;
						case 'pie':
							var chart = new google.visualization.PieChart(container);
							break;
						default:
							// bar
							var chart = new google.visualization.ColumnChart(container);
					}
					
					chart.draw(data, options);
				}

				$(window).resize(function(){
					drawCharts();
				});
				function drawCharts() {
					drawUsersAgeGroup();
					drawPublishersBooks();
					drawBooksDecade();
					drawCountriesUsers();
					drawCitiesUsers();
					drawAuthorsBooks();
				}
				$(".nav-tabs a").click(function() {
					setTimeout(function(){
						drawCharts();
					}, 1);
				});

			</script>
		</div>
	</body>
</html>
