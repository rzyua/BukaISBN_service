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
                $query = "INSERT INTO book "
                        . "(title,isbn,publisherId,year,other,createdDate,"
                        . "createdBy,modifiedDate,modifiedBy) "
                        . "VALUES ("
                        . "'" . mysqli_real_escape_string($conn,$_POST["newTitle"]) . "',"
                        . "'" . mysqli_real_escape_string($conn,$_POST["newIsbn"]) . "',";
                // Publisher Id
                if(isset($_POST["newPublisherId"]))
                    $query .= "'" . mysqli_real_escape_string($conn,$_POST["newTitle"]) . "',";
                else
                    $query .= "NULL,";
                // Year
                if(isset($_POST["newYear"]))
                    $query .= "'" . mysqli_real_escape_string($conn,$_POST["newYear"]) . "',";
                else
                    $query .= "NULL,";
                // Other
                if(isset($_POST["newOther"])) {
                    $query .= "'" . mysqli_real_escape_string($conn,$_POST["newOther"]) . "',";
                }
                else {
                    $query .= "NULL,";
                }
                // created/modified
                $query .= "NOW()," . $user->id . ",NOW()," . $user->id . ");";
                
                // Execute the query
                $q = execute_query($conn, $query);
                
                // Get the insterted book Id
                $newBookId = mysqli_insert_id ( $conn );
                echo $newBookId . "\n";
                
                // If the newBookId is 0 then an error occured
                // Do not continue in this situation
                if ($newBookId != 0) {
                    // Try to add authorToBook records
                    $it = 1;
                    while(isset($_POST["newAuthor" . $it])) {
                        $query = "SELECT COUNT(*) FROM authorToBook "
                                . "WHERE authorId = "
                                . mysqli_real_escape_string($conn, $_POST["newAuthor" . $it])
                                . " AND bookId = "
                                . $newBookId . ";";
                        $q = execute_query($conn, $query);
                        $r = mysqli_fetch_array($q);
                        if ($r[0] == 0) {
                            $query = "INSERT INTO authorToBook ("
                                . "authorId, bookId, createdDate,"
                                . "createdBy, modifiedDate, modifiedBy) "
                                . "VALUES ("
                                . mysqli_real_escape_string($conn,$_POST["newAuthor" . $it]) . ","
                                . $newBookId . ","
                                . "NOW()," . $user->id . ",NOW()," . $user->id . ");";
                            $q = execute_query($conn, $query);
                            //Debug
                            //echo $it . ":" . mysqli_insert_id ( $conn ) . "\n";
                        }
                        $it++;
                    }
                    
                    // Try to add tagToBook records
                    $it = 1;
                    while(isset($_POST["newTag" . $it])) {
                        $query = "SELECT COUNT(*) FROM tagToBook "
                                . "WHERE tagId = "
                                . mysqli_real_escape_string($conn, $_POST["newTag" . $it])
                                . " AND bookId = "
                                . $newBookId . ";";
                        $q = execute_query($conn, $query);
                        $r = mysqli_fetch_array($q);
                        if ($r[0] == 0) {
                            $query = "INSERT INTO tagToBook ("
                                . "tagId, bookId, createdDate,"
                                . "createdBy, modifiedDate, modifiedBy) "
                                . "VALUES ("
                                . mysqli_real_escape_string($conn,$_POST["newTag" . $it]) . ","
                                . $newBookId . ","
                                . "NOW()," . $user->id . ",NOW()," . $user->id . ");";
                            $q = execute_query($conn, $query);
                            //Debug
                            //echo $it . ":" . mysqli_insert_id ( $conn ) . "\n";
                        }
                        $it++;
                    }
                }
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
