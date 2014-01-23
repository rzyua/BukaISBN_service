<?php

// Configuration variables
define('HOST', 'localhost');
define('USER', 'bukaisbn');
define('PASS', 'japonczyk');
define('DB', 'bukaisbn');
define('DEBUG', true);

class User {
	public $id;
	public $login;
	public $firstName;
	public $lastName;
	public $email;
	public $isAdmin;
	public $createdDate;
	public $createdBy;
	public $modifiedDate;
	public $modifiedBy;
}

// Common functions
function execute_query($conn, $query)
{

    $r = mysqli_query($conn, $query);
    
    return $r;
}

function db_init()
{
	$r = mysqli_connect(HOST, USER, PASS, DB);

	if (mysqli_connect_errno($r))
	{
		echo "ERROR! Failed to connect to MySQL: " . mysqli_connect_error() ."\n";
	}
	
	return $r;
}

function db_close($conn)
{
	mysqli_close($conn);
}

function echo_query($q) {
	$i = FALSE;
	foreach( $q as $row ) {
		$verse = array();
		foreach($row as $key => $val) {
			if (strlen($val) == 0) $val = "NULL";
			array_push($verse, $key . "=" . $val);
		}
		echo implode("|", $verse) . "\n";
                $i = true;
	}
        
        if (!$i) echo "0\n";
}

function check_credentials ($conn, $login, $pass) {
	$q = execute_query($conn, "SELECT * FROM users WHERE login = '" . mysqli_real_escape_string($conn, $login) . "';");
	$user = null;
	if (mysqli_num_rows($q) == 1) {
		$r = mysqli_fetch_assoc($q);
		if(password_verify($pass, $r["password"])) {
			$user = new User;
			$user->id = $r["id"];
			$user->login = $r["login"];
			$user->firstName = $r["firstName"];
			$user->lastName = $r["lastName"];
			$user->email - $r["email"];
			$user->isAdmin = $r["adminRole"];
			$user->createdDate = $r["createdDate"];
			$user->createdBy = $r["createdBy"];
			$user->modifiedDate = $r["modifiedDate"];
			$user->modifiedBy = $r["modifiedBy"];
		}
	}
	return $user;
}

?>
