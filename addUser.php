<?php
include_once("config.php");

if(isset($_POST["login"]) and isset($_POST["password"])) {
	$conn = db_init();
	$user = check_credentials($conn, $_POST["login"],  $_POST["password"]);
	
	if ($user != null and $user->isAdmin) {
		if(isset($_POST["newLogin"]) and isset($_POST["newPassword"]) and isset($_POST["newFirstName"])) { // Mandatory values
			$q = execute_query($conn, "SELECT COUNT(*) FROM users WHERE login = '" 
						. mysqli_escape_string($conn, $_POST["newLogin"]) . "';");
			$r = mysqli_fetch_array($q);
			if ($r[0] == 0) { // User doesn't exist
				$newUser = new User;
				$newUser->login = "'" . mysqli_real_escape_string($conn, $_POST["newLogin"]) . "'";
				$newUser->firstName = "'" . mysqli_real_escape_string($conn, $_POST["newFirstName"]) . "'";
				// Optional values
				if(isset($_POST["newLastName"])) $newUser->lastName = "'" . mysqli_real_escape_string($conn, $_POST["newLastName"]) . "'";
				else $newUser->lastName = "NULL";
				if(isset($_POST["newEmail"])) $newUser->email = "'" . mysqli_real_escape_string($conn, $_POST["newEmail"]) . "'";
				else $newUser->email = "NULL";
				if(isset($_POST["newAdminRole"]) and $_POST["newAdminRole"] == 1) $newUser->isAdmin = 1;
				else $newUser->isAdmin = 0;
				
				
				// Hash the password
				$newUser->password = "'" . password_hash($_POST["newPassword"], PASSWORD_BCRYPT) . "'";
				$q = execute_query (
					$conn, 
					"INSERT INTO users (login, password, firstName, lastName, email, adminRole, createdDate, createdBy, modifiedDate, modifiedBy) VALUES ("
						. $newUser->login . ","
						. $newUser->password . ","
						. $newUser->firstName . ","
						. $newUser->lastName . ","
						. $newUser->email . ","
						. $newUser->isAdmin . ","
						. "NOW()," . $user->id . ","
						. "NOW()," . $user->id . ");"
				);
				echo mysqli_insert_id ( $conn ) . "\n";
			} else {
				echo "ERROR: User already exists.\n";
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
