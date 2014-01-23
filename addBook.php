<?php
include_once("config.php");

if(isset($_POST["login"]) and isset($_POST["password"])) {
	$conn = db_init();
	$user = check_credentials($conn, $_POST["login"],  $_POST["password"]);
	
	if ($user != null and $user->isAdmin) {
		if(isset($_POST["newTitle"]) and isset($_POST["newIsbn"])) { // Mandatory values
			$q = execute_query($conn, "SELECT COUNT(*) FROM book WHERE isbn = '" 
					. mysqli_escape_string($conn, $_POST["newIsbn"]) . "';");
			$r = mysqli_fetch_array($q);
			if ($r[0] == 0) { // Book doesn't exist
				// Build the query, take multiple authors into account
				echo mysqli_insert_id ( $conn ) . "\n";
			} else {
				echo "ERROR: Book already exists.\n";
			}
		} else {
			echo "ERROR: Missing user info.\n";
		}
	} else {
		echo "ERROR: Bad or missing credentials.\n";
	}
	
	// Close connection
	db_close($conn);
} else {
	echo "ERROR: Bad or missing credentials.\n";
}

?>
