<?php
include_once("config.php");

if(isset($_POST["login"]) and isset($_POST["password"])) {
	$conn = db_init();
	$user = check_credentials($conn, $_POST["login"],  $_POST["password"]);
	
	if ($user != null) {
		echo "OK\n";
	} else {
		echo "NOPE.\n";
	}
	
	// Close connection
	db_close($conn);
} else {
	echo "ERROR: Bad or missing credentials.\n";
}

?>
