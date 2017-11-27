<?php 

class BooksApp {
	protected $connection;
	public $user;

	public function __construct() {
		$this->connection = $this->connect_to_db();
		if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
			$this->user = $_SESSION['user'];
		} else {
			$this->user = false;
		}
	}

	/**
	 * Simple database connection
	 * @return mixed $conn Database connection
	 */
	protected function connect_to_db() {
		$servername = DB_HOST;
		$username = DB_USER;
		$password = DB_PASSWORD;
		$database_name = DB_NAME;

		// Create connection
		$conn = new mysqli($servername, $username, $password, $database_name);

		// Check connection
		if ($conn->connect_error) {
			//die("Connection failed: " . $conn->connect_error);
			return false;
		}

		return $conn; 
	}

	/**
	 * Get 5 latest books
	 * @return array $books Array of book objects
	 */
	public function get_latest_books() {
		$sql = "SELECT books.*, books_avg.avg FROM books 
				LEFT JOIN (
					SELECT books_ratings.isbn, AVG(books_ratings.rating) as avg FROM books_ratings 
					GROUP BY books_ratings.isbn
				) AS books_avg 
				ON books.isbn = books_avg.isbn 
				ORDER BY books.registration_date DESC LIMIT 5";
		$result = $this->connection->query($sql);
		$books = array();

		if ($result->num_rows > 0) {
			while($row = $result->fetch_object()) {
			   $books[] = $row;
			}
		}

		return $books;
	}
	
	/**
	 * Get 5 top rated books
	 * @return array $books Array of book objects
	 */
	public function get_top_rated_books() {
		$sql = "SELECT rated_books.*, ratings.avg FROM 
				(SELECT books.* FROM books WHERE books.isbn IN 
				(SELECT isbn FROM books_ratings GROUP BY isbn ORDER BY AVG(rating) DESC)) AS rated_books, 
				(SELECT isbn, AVG(rating) AS avg FROM books_ratings GROUP BY isbn ORDER BY AVG(rating) DESC) AS ratings 
				WHERE rated_books.isbn = ratings.isbn LIMIT 5";

		$result = $this->connection->query($sql);
		$books = array();

		if ($result->num_rows > 0) {
			while($row = $result->fetch_object()) {
				$books[] = $row;
			}
		}

		return $books;
	}

	/**
	 * Get 5 most active raters
	 * @return array $raters Array of user objects
	 */
	public function get_active_raters() {
		$sql = "SELECT users.*, users_ratings.no_of_ratings 
		FROM users, (SELECT user_id, COUNT(user_id) AS no_of_ratings FROM books_ratings GROUP BY user_id) AS users_ratings 
		WHERE users.user_id = users_ratings.user_id 
		ORDER BY users_ratings.no_of_ratings DESC 
		LIMIT 5";

		$result = $this->connection->query($sql);
		$raters = array();

		if ($result->num_rows > 0) {
			while($row = $result->fetch_object()) {
				$raters[] = $row;
			}
		}

		return $raters;
	}

	/**
	 * Get 5 users with most books registered
	 * @return array $registrators Array of user objects
	 */
	public function get_top_registrators() {
		$sql = "SELECT users.*, users_books.no_of_books 
				FROM users, (SELECT registered_by, COUNT(registered_by) as no_of_books FROM books GROUP BY registered_by) AS users_books 
				WHERE users.user_id = users_books.registered_by 
				ORDER BY users_books.no_of_books DESC 
				LIMIT 5";

		$result = $this->connection->query($sql);
		$registrators = array();

		if ($result->num_rows > 0) {
			while($row = $result->fetch_object()) {
				$registrators[] = $row;
			}
		}

		return $registrators;
	}

	/**
	 * Try to log in the user
	 * @param string $username
	 * @param string $password
	 * @return bool|mixed User object or false
	 */
	public function login_user($username = "", $password = "") {
		// We use ? sign instead of variables
		$sql = "SELECT user_id, user_name, dob, city, country, user_email FROM users WHERE user_name = ? AND password = ?";
		// Prevent SQL injection with prepared statement
		$stmt = $this->connection->prepare($sql);
		$stmt->bind_param('ss', $username, $password);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			$user = $result->fetch_object();
			return $user;
		}
		return false;
	}

	/**
	 * Register a user
	 * @param string $username
	 * @param string $password
	 * @param string $date_of_birth
	 * @param string $city
	 * @param string $country
	 * @param string $email
	 * @return bool True or false
	 */
	public function register_user($username = "", $password = "", $date_of_birth = "", $city = "", $country = "", $email = "") {
		// We use ? sign instead of variables
		$sql = "INSERT INTO users (user_name, password, dob, city, country, user_email) VALUES (?,?,?,?,?,?);";

		// Prevent SQL injection with prepared statement
		$stmt = $this->connection->prepare($sql);
		$stmt->bind_param('ssssss', $username, $password, $date_of_birth, $city, $country, $email);
		return $stmt->execute();
	}

	/**
	 * Add new book to database
	 * @param string $isbn
	 * @param string $book_title
	 * @param string $book_author
	 * @param string $publish_year
	 * @param string $publisher
	 * @param string $book_image
	 * @param string $user_id
	 * @param string $date_added
	 * @return bool True or false
	 */
	public function add_new_book($isbn = "", $book_title = "", $book_author = "", $publish_year = "", $publisher = "", $book_image = "", $user_id = "", $date_added = "") {
		// We use ? sign instead of variables
		$sql = "INSERT INTO books (isbn, book_title, book_author, publish_year, publisher, book_image, registered_by, registration_date) VALUES (?,?,?,?,?,?,?,?)";
		// Prevent SQL injection with prepared statement
		$stmt = $this->connection->prepare($sql);
		$stmt->bind_param('ssssssis', $isbn, $book_title, $book_author, $publish_year, $publisher, $book_image, $user_id, $date_added);
		return $stmt->execute();
	}

	

	/**
	 * Get all user books
	 * @return array Array of books or an empty array
	 */
	public function get_user_books() {
		$user_id = $this->user->user_id;
		$sql = "SELECT isbn, book_title, book_author, publish_year, publisher, book_image FROM books WHERE registered_by = '$user_id' ORDER BY registration_date DESC";
		$books = array();
		if ($result = $this->connection->query($sql)) {
			while ($row = $result->fetch_object()) {
				$books[] = $row;
			}
		}

		return $books;
	}

	/**
	 * Check if logged user added the book
	 * @param string $isbn Book ISBN
	 * @return bool True or false
	 */
	public function check_if_users_book($isbn = "") {
		$user_id = $this->user->user_id;
		$sql = "SELECT * FROM books WHERE isbn = '$isbn' AND registered_by = '$user_id'";
		$result = $this->connection->query($sql);
		if ($result->num_rows) {
			return true;
		}
		return false;
	}

	/**
	 * Delete a book from database
	 * @param string $isbn
	 * @return bool
	 */
	public function delete_book($isbn = "") {
		// Check if book belongs to logged in user
		if (!$this->check_if_users_book($isbn)) {
			return false;
		}

		// First delete all ratings for this book
		// because of foreign key constraint
		if ($this->delete_book_ratings($isbn)) {
			$sql = "DELETE FROM books WHERE isbn = '$isbn'";
			if ($result = $this->connection->query($sql)) {
				return true;
			}
			return false;
		}
		return false;
	}

	/**
	 * Delete all reviews for a book
	 * @param string $isbn
	 * @return bool
	 */
	private function delete_book_ratings($isbn = "") {
		$user_id = $this->user->user_id;
		$sql = "DELETE FROM books_ratings WHERE isbn = '$isbn' AND user_id = '$user_id'";
		if ($result = $this->connection->query($sql)) {
			return true;
		}
		return false;
	}

	/**
	 * Get book by ISBN
	 * @return mixed|bool $book Book object or false
	 */
	public function get_book_by_isbn($isbn = "") {
		$sql = "SELECT isbn, book_title, book_author, publish_year, publisher, book_image, registered_by, registration_date FROM books WHERE isbn = ?";
		// Prevent SQL injection with prepared statement
		$stmt = $this->connection->prepare($sql);
		$stmt->bind_param('s', $isbn);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			$book = $result->fetch_object();
			$user_id = $book->registered_by;
			$isbn = $book->isbn;
			$user_name = $this->get_user_name_by_id($user_id);
			$rating_avg = $this->get_book_avarage_rating($isbn);

			$book->registered_by = $user_name;

			$book->rating_avg = $rating_avg;

			return $book;
		}
		return false;
	}

	/**
	 * Get books by input from search form
	 * @param string $book_title
	 * @param string $book_author
	 * @param string $publish_year
	 * @param string $publisher
	 * @param bool $is_exact_title
	 * @param bool $is_exact_author
	 * @return array $books
	 */
	public function get_searched_books($book_title = "", $book_author = "", $publish_year = "", $publisher = "", $is_exact_title = false, $is_exact_author = false) {

		if (!$is_exact_author) {
			$author_query = " LIKE '%" . $book_author . "%'";
		} else {
			$author_query = " = '" . $book_author . "'";
		}
		if (!$is_exact_title) {
			$title_query = " LIKE '%" . $book_title . "%'";
		} else {
			$title_query = " = '" . $book_title . "'";
		}
		$sql = "SELECT * FROM books WHERE book_title " . $title_query . " AND book_author " . $author_query . " AND publisher LIKE '%".$publisher."%' AND publish_year LIKE '%".$publish_year."%' ORDER BY publish_year DESC;";

		$books = array();

		if ($result = $this->connection->query($sql)) {
			while ($row = $result->fetch_object()) {
				$books[] = $row;
			}
		}
		
		return $books;
	}

	/**
	 * Edit a book
	 * @param string $isbn
	 * @param string $book_title
	 * @param string $book_author
	 * @param string $publish_year
	 * @param string $publisher
	 * @param string $book_image
	 * @param string $user_id
	 * @param string $date_added
	 * @return bool True or false
	 */
	public function edit_book($isbn = "", $book_title = "", $book_author = "", $publish_year = "", $publisher = "", $book_image = "") {
		// Check if book belongs to logged in user
		if (!$this->check_if_users_book($isbn)) {
			return false;
		}

		$sql = "UPDATE books SET book_title = ?, book_author = ?, publish_year = ?, publisher = ?, book_image = ? WHERE isbn = ?";
		$stmt = $this->connection->prepare($sql);
		$stmt->bind_param('ssssss', $book_title, $book_author, $publish_year, $publisher, $book_image, $isbn);
		return $stmt->execute();
	}


	/**
	 * Rate a book
	 * @param string $isbn
	 * @param string $user_id
	 * @param string $comment
	 * @param string $rating 
	 * @return bool True or false
	 */
	public function rate_book($isbn = "", $user_id = "", $comment = "", $rating = "") {

		if ($this->user_has_rated($isbn)) {
			$sql = "UPDATE books_ratings SET rating='".$rating."', comment='".$comment."' WHERE isbn='".$isbn."' AND user_id='".$user_id."';";
			return $this->connection->query($sql);
		}

		$sql = "INSERT INTO books_ratings (isbn, user_id, comment, rating) VALUES (?,?,?,?)";
		// Prevent SQL injection with prepared statement
		$stmt = $this->connection->prepare($sql);
		$stmt->bind_param('sisi', $isbn, $user_id, $comment, $rating);
		return $stmt->execute();
	}

	/**
	 * Check if user already rated a book
	 * @param string $isbn Book ISBN number
	 * @return bool True or false
	 */
	public function user_has_rated($isbn = "") {
		if (!$this->user) {
			// If user is not loggend in, return false
			return false;
		}
		$user_id = $this->user->user_id;
		$sql = "SELECT isbn FROM books_ratings WHERE isbn = '".$isbn."' AND user_id = '".$user_id."'";
		$result = $this->connection->query($sql);
		if ($result->num_rows > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Get all ratings for a book
	 * @param string $isbn Book ISBN number
	 * @return array $ratings Books ratings
	 */
	public function get_book_ratings($isbn = "") {
		$sql = "SELECT books_ratings.*, users.user_name
				FROM books_ratings
				INNER JOIN users
				ON books_ratings.user_id=users.user_id WHERE books_ratings.isbn = '$isbn'";

		$result = $this->connection->query($sql);
		$ratings = array();
		if ($result->num_rows > 0) {
			while($row = $result->fetch_object()) {
				$ratings[] = $row;
			}
		}
		return $ratings;
	}

	/**
	 * Get the count of books published each year
	 * @return $books_by_years array Array of years and counts of books
	 */
	public function get_books_by_years() {
		$sql = "SELECT publish_year, COUNT(publish_year) as count FROM books GROUP BY publish_year";

		$result = $this->connection->query($sql);
		$books_by_years = array();

		if ($result->num_rows > 0) {
			while($row = $result->fetch_object()) {
				$interval = 10;
				$year = $row->publish_year;
				$books_count = $row->count;
				$current_year = new DateTime();
				$current_year = $current_year->format('Y');
				$group = (floor($year / $interval) * $interval);
				if ($current_year > ($group + $interval - 1)) {
					$group_end = ($group + $interval - 1);
				} else {
					$group_end =  $current_year;
				}
				$age_group = $group . " - " . $group_end;
				if (isset($books_by_years[$age_group])) {
					$books_by_years[$age_group] += $books_count;
				} else {
					$books_by_years[$age_group] = $books_count;
				}
			}
		}

		return $books_by_years;
	}

	/**
	 * Get the count of books published each year
	 * @return $books_by_publisher array Array of publishers and counts of books
	 */
	public function get_books_by_publisher() {
		$sql = "SELECT publisher, COUNT(publisher) as count FROM books GROUP BY publisher ORDER BY count DESC LIMIT 0, 10";

		$result = $this->connection->query($sql);
		$books_by_publisher = array();

		if ($result->num_rows > 0) {
			while($row = $result->fetch_object()) {
				$books_by_publisher[$row->publisher] = $row->count;
			}
		}

		return $books_by_publisher;
	}

	/**
	 * Get the count of books published by author
	 * @return $books_by_author array Array of authors and counts of books
	 */
	public function get_books_by_author() {
		$sql = "SELECT book_author, COUNT(book_author) as count FROM books GROUP BY book_author ORDER BY count DESC LIMIT 0, 5";

		$result = $this->connection->query($sql);
		$books_by_author = array();

		if ($result->num_rows > 0) {
			while($row = $result->fetch_object()) {
				$books_by_author[$row->book_author] = $row->count;
			}
		}

		return $books_by_author;
	}

	/**
	 * Get user name by id
	 * @param string $id
	 * @return string|bool $user_name string or false
	 */
	public function get_user_name_by_id($id ="") {

		$sql = "SELECT user_name FROM users WHERE user_id = ".$id.";";

		if ($result = $this->connection->query($sql)) {
			$row = $result->fetch_object();
			$user_name = $row->user_name;
			return $user_name;
		}

		return false;
	}

	/**
	 * Get avarage rating of the book
	 * @param string $isbn
	 * @return int|bool $avg or false
	 */
	public function get_book_avarage_rating($isbn = "") {
		$sql = "SELECT AVG(rating) AS avg FROM books_ratings WHERE isbn = '".$isbn."'";
		$result = $this->connection->query($sql);
		if ($result->num_rows > 0) {
			$row = $result->fetch_object();
			$avg = round($row->avg, 0, PHP_ROUND_HALF_UP);
			return $avg;
		}

		return false;
	}

	public function get_users_by_age() {

		$sql = "SELECT dob FROM users order by dob DESC";

		$result = $this->connection->query($sql);
		$users_by_age = array();

		if ($result->num_rows > 0) {
			while($row = $result->fetch_object()) {
				$year = new DateTime($row->dob);
				$year = $year->format('Y');
				$current_year = new DateTime();
				$current_year = $current_year->format('Y');
				$age = $current_year - $year;
				$interval = 5;
				$group = (floor($age / $interval) * $interval);
				$age_group = $group . " - " . ($group + $interval - 1);
				if (isset($users_by_age[$age_group])) {
					$users_by_age[$age_group] += 1;
				} else {
					$users_by_age[$age_group] = 1;
				}
			}
		}

		return $users_by_age;
	}

	/**
	 * Get number of users by country
	 * @return array $users_by_country
	 */
	public function get_users_by_country() {

		$sql = "SELECT country, COUNT(country) as cnt FROM users GROUP BY country ORDER BY cnt DESC LIMIT 0, 10";

		$result = $this->connection->query($sql);
		$users_by_country = array();

		if ($result->num_rows > 0) {
			while($row = $result->fetch_object()) {
			$users_by_country[$row->country] = $row->cnt;
			}
		}

		return $users_by_country;
	}

	/**
	 * Get number of users by city
	 * @return array $users_by_city
	 */
	public function get_users_by_city() {

		$sql = "SELECT city, COUNT(city) as count FROM users GROUP BY city ORDER BY count DESC LIMIT 0, 10";

		$result = $this->connection->query($sql);
		$users_by_city = array();

		if ($result->num_rows > 0) {
			while($row = $result->fetch_object()) {
			$users_by_city[$row->city] = $row->count;
			}
		}

		return $users_by_city;
	}


	public function get_user_rating_of_book($isbn = "", $user_id= "") {

		$sql = "SELECT rating, comment FROM books_ratings WHERE user_id = ".$user_id." AND isbn = ".$isbn.";";
		if ($result = $this->connection->query($sql)) {
			return $result->fetch_object();
		}



		return false;

	}

	}