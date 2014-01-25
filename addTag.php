<?php
include_once("config.php");

if(isset($_POST["login"]) and isset($_POST["password"])) {
    $conn = db_init();
    $user = check_credentials($conn, $_POST["login"],  $_POST["password"]);
    
    if ($user != null and $user->isAdmin) {
        if(isset($_POST["newName"])) { // Mandatory values
            $q = execute_query($conn, "SELECT COUNT(*) FROM tag WHERE name = '" 
                    . mysqli_escape_string($conn, $_POST["newName"]). "';");
            $r = mysqli_fetch_array($q);
            if ($r[0] == 0) { // Author doesn't exist
                $q = execute_query(
                    $conn, 
                    "INSERT INTO tag (name, createdDate, createdBy, modifiedDate, modifiedBy) VALUES ("
                        . "'" . mysqli_real_escape_string($conn, $_POST["newName"]) . "',"
                        . "NOW()," . $user->id . ","
                        . "NOW()," . $user->id . ");"
                );
                echo mysqli_insert_id ( $conn ) . "\n";
            } else {
                echo "ERROR: Tag already exists.\n";
            }
        } else {
            echo "ERROR: Missing tag info.\n";
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
