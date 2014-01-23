<?php
include_once("config.php");

if(isset($_POST["login"]) and isset($_POST["password"])) {
    $conn = db_init();
    $user = check_credentials($conn, $_POST["login"], $_POST["password"]);
    // Parse POST values
    if($user != null) {
        if(isset($_POST["what"])) {
            switch($_POST["what"]) {
                case "books":
                    get_books($conn, $user);
                    break;
                case "bookById":
                    get_book_by_id($conn, $user, $_POST["val"]);
                    break;
                case "bookByIsbn":
                    get_book_by_isbn($conn, $user, $_POST["val"]);
                case "authors":
                    get_authors($conn, $user);
                    break;
                case "authorById":
                    get_author_by_id($conn, $user, $_POST["val"]);
                    break;
                case "authorsByBookId":
                    get_authors_by_book_id($conn, $user, $_POST["val"]);
                    break;
                                case "publishers":
                    get_publishers($conn, $user);
                    break;
                case "publisherById":
                    get_publisher_by_id($conn, $user, $_POST["val"]);
                    break;
                case "publisherByBookId":
                    get_publisher_by_book_id($conn, $user, $_POST["val"]);
                    break;
                case "tags":
                    get_tags($conn, $user);
                    break;
                case "tagById":
                    get_tag_by_id($conn, $user, $_POST["val"]);
                    break;
                case "tagsByBookId":
                    get_tag_by_id($conn, $user, $_POST["val"]);
                    break;
                case "users":
                    get_users($conn, $user);
                    break;
                case "userById":
                    get_user_by_id($conn, $user, $_POST["val"]);
                    break;
                default:
                    echo "Error: Invalid 'what' value.\n";
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


function get_books($conn, $user) {
    $q = execute_query($conn, "SELECT * FROM book");
    echo_query($q);
}

function get_book_by_id($conn, $user, $val) {
    if($val != null) {
        $q = execute_query($conn, "SELECT * FROM book WHERE id = " 
                                . mysqli_real_escape_string($conn, $val));
        echo_query($q);
    } else {
        echo "ERROR: Missing parameter 'val'.\n";
    }
}

function get_book_by_isbn($conn, $user, $val) {
    if($val != null) {
        $q = execute_query($conn, "SELECT * FROM book WHERE isbn = " 
                                . mysqli_real_escape_string($conn, $val));
        echo_query($q);
    } else {
        echo "ERROR: Missing parameter 'val'.\n";
    }
}

function get_authors($conn, $user) {
    $q = execute_query($conn, "SELECT * FROM author");
    echo_query($q);
}

function get_author_by_id($conn, $user, $val) {
    if($val != null) {
        $q = execute_query($conn, "SELECT * FROM author WHERE id = " 
                            . mysqli_real_escape_string($conn, $val));
        echo_query($q);
    } else {
        echo "ERROR: Missing parameter 'val'.\n";
    }
}

function get_authors_by_book_id($conn, $user, $val) {
    if($val != null) {
        $q = execute_query($conn, "SELECT a.id,a.firstName,a.lastName,"
                                    . "a.createdDate,a.createdBy,a.modifiedDate,"
                                    . "a.modifiedBy "
                                    . "FROM authorToBook AS atb "
                                    . "INNER JOIN author AS a "
                                    . "ON a.id = atb.authorId "
                                    . "WHERE atb.bookId = "
                                    . mysqli_real_escape_string($conn, $val));
        echo_query($q);
    } else {
        echo "ERROR: Missing parameter 'val'.\n";
    }
}

function get_publishers($conn, $user) {
    $q = execute_query($conn, "SELECT * FROM publisher");
    echo_query($q);
}

function get_publisher_by_id($conn, $user, $val) {
    if($val != null) {
        $q = execute_query($conn, "SELECT * FROM publisher WHERE id = " 
                            . mysqli_real_escape_string($conn, $val));
        echo_query($q);
    } else {
        echo "ERROR: Missing parameter 'val'.\n";
    }
}

function get_publisher_by_book_id($conn, $user, $val) {
    if($val != null) {
        $q = execute_query($conn, "SELECT p.id,p.name,b.id AS bookId "
                        . "FROM book AS b "
                        . "INNER JOIN publisher AS p "
                            . "ON b.publisherId = p.id "
                        . "WHERE b.id = "
                        . mysqli_real_escape_string($conn, $val));
        echo_query($q); 
    } else {
        echo "ERROR: Missing parameter 'val'.\n";
    }
}

function get_tags($conn, $user) {
    $q = execute_query($conn, "SELECT * FROM tag");
    echo_query($q);
}

function get_tag_by_id($conn, $user, $val) {
    if($val != null) {
        $q = execute_query($conn, "SELECT * FROM tag WHERE id = " 
                    . mysqli_real_escape_string($conn, $val));
        echo_query($q);
    } else {
        echo "ERROR: Missing parameter 'val'.\n";
    }
}

function get_tags_by_book_id($conn, $user, $val) {
    if($val != null) {
        $q = execute_query($conn, "SELECT t.id,t.name,b.id AS bookId "
                        . "FROM book AS b "
                        . "INNER JOIN tag AS t "
                            . "ON t.id = p.id "
                        . "WHERE b.id = "
                        . mysqli_real_escape_string($conn, $val));
        echo_query($q); 
    } else {
        echo "ERROR: Missing parameter 'val'.\n";
    }
}

function get_users($conn, $user) {
    if($user->isAdmin) {
        $q = execute_query($conn, "SELECT id, login, firstName, lastName,
                    adminRole, createdDate, createdBy,
                    modifiedDate, modifiedBy FROM users");
        echo_query($q);    
    } else {
        echo "ERROR: Bad or missing credentials.\n";
    }
}

function get_user_by_id($conn, $user, $val) {
    if($val != null) {
        $query = null;
        if ($user->isAdmin or $user->id == $val) $query = "SELECT id, 
                    login, firstName, lastName, adminRole,
                    createdDate, createdBy, modifiedDate,
                    modifiedBy FROM users WHERE id = ";
        else $query = "SELECT id,firstName,lastName  FROM users WHERE id = ";
        $q = execute_query($conn, $query . mysqli_real_escape_string($conn, $val));
        echo_query($q);
    } else {
        echo "ERROR: Missing parameter 'val'.\n";
    }
}
?>
