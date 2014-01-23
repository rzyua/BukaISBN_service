<?php
include_once("config.php");

if(isset($_POST["login"]) and isset($_POST["password"])) {
	$conn = db_init();
	$user = check_credentials($conn, $_POST["login"],  $_POST["password"]);
	
	// Parse POST values
	if($user != null) {
		if(isset($_POST["what"])) {
			if(isset($_POST["val"]) and strlen($_POST["val"]) > 0) {
				switch($_POST["what"]) {
					case "books":
						search_books($conn, $user, $_POST["val"]);
						break;
					case "authors":
						search_authors($conn, $user, $_POST["val"]);
						break;
					case "publishers":
						search_publishers($conn, $user, $_POST["val"]);
						break;
					case "tags":
						search_tags($conn, $user, $_POST["val"]);
						break;
					case "users":
						search_users($conn, $user, $_POST["val"]);
						break;
					default:
						echo "Error: Invalid 'what' value.\n";
				}
			} else {
				echo "ERROR: Missing parameter 'val'.\n";
			}
		} else {
			echo "ERROR: Missing parameter 'what'.\n";
		}
	}else {
		echo "ERROR: Bad or missing credentials.\n";
	}
	
	// Close connection
	db_close($conn);
} else {
	echo "ERROR: Bad or missing credentials.\n";
}


function search_books($conn, $user, $val) {
	$val = mysqli_real_escape_string($conn, $val);
	$q = execute_query($conn, "SELECT
									b.id,
									b.isbn,
									b.title,
									b.year,
									b.other,
									a.id AS authorId,
									a.firstName AS authorFirstName,
									a.lastName AS authorLastName,
									p.id AS publisherId,
									p.name AS publisherName,
									b.createdDate,
									b.createdBy,
									b.modifiedDate,
									b.modifiedBy
								FROM book AS b
								LEFT JOIN publisher AS p
									ON p.id = b.publisherId
								LEFT JOIN authorToBook AS ab
									ON b.id = ab.bookId
								LEFT JOIN author AS a
									ON ab.authorId = a.id
								WHERE
									b.isbn LIKE '%".$val."%'
									OR b.title LIKE '%".$val."%'
									OR b.year LIKE '%".$val."%'
									OR b.other LIKE '%".$val."%'
									OR a.firstName LIKE '%".$val."%'
									OR a.lastName LIKE '%".$val."%'
								GROUP BY b.id
								ORDER BY b.title ASC;");
	echo_query($q);
}

function search_authors($conn, $user, $val) {
	$val = mysqli_real_escape_string($conn, $val);
	$q = execute_query($conn, "SELECT 
									id,
									firstName,
									lastName,
									createdDate,
									createdBy,
									modifiedDate,
									modifiedBy
								FROM author
								WHERE
									firstName LIKE '%".$val."%'
									OR lastName LIKE '%".$val."%'
								ORDER BY lastName ASC;");
	echo_query($q);
}

function search_publishers($conn, $user, $val) {
	$val = mysqli_real_escape_string($conn, $val);
	$q = execute_query($conn, "SELECT 
									id,
									name,
									createdDate,
									createdBy,
									modifiedDate,
									modifiedBy
								FROM publisher
								WHERE
									name LIKE '%".$val."%'
								ORDER BY name ASC;");
	echo_query($q);
}

function search_tags($conn, $user, $val) {
	$val = mysqli_real_escape_string($conn, $val);
	$q = execute_query($conn, "SELECT 
									id,
									name,
									createdDate,
									createdBy,
									modifiedDate,
									modifiedBy
								FROM tag
								WHERE
									name LIKE '%".$val."%'
								ORDER BY name ASC;");
	echo_query($q);
}

function search_users($conn, $user, $val) {
	if($user->isAdmin) {
		$val = mysqli_real_escape_string($conn, $val);
		$q = execute_query($conn, "SELECT 
										id,
										login,
										firstName,
										lastName,
										email,
										adminRole,
										createdDate,
										createdBy,
										modifiedDate,
										modifiedBy
									FROM users
									WHERE
										login LIKE '%".$val."%'
										OR firstName LIKE '%".$val."%'
										OR lastName LIKE '%".$val."%'
										OR email LIKE '%".$val."%'
									ORDER BY login ASC;");
		echo_query($q);
	} else {
		echo "ERROR: Bad or missing credentials.\n";
	}
}
?>
