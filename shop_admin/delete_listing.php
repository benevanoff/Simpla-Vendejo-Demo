<?php
/* DB format
table: listings
+-------------+--------------+------+-----+---------+-------+
| Field       | Type         | Null | Key | Default | Extra |
+-------------+--------------+------+-----+---------+-------+
| prod_id     | int(11)      | YES  |     | NULL    |       |
| name        | varchar(35)  | YES  |     | NULL    |       |
| description | text         | YES  |     | NULL    |       |
| price       | float        | YES  |     | NULL    |       |
| img_name    | varchar(100) | YES  |     | NULL    |       |
+-------------+--------------+------+-----+---------+-------+
*/
session_start();
if (isset($_SESSION['admin']) && $_SESSION['admin']) {
    $DATABASE_HOST = getenv("DB_HOST");
    $DATABASE_USER = getenv("DB_USER");
    $DATABASE_PASS = getenv("DB_PASSWD");
    $DATABASE_NAME = getenv("DB_NAME");
    // Try and connect using the info above.
    $db = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
    }

    if (isset($_POST["product"])) {
        if ($stmt = $db->prepare('DELETE FROM listings WHERE prod_id=?')) {
            $stmt->bind_param($_POST["product"]);
            $stmt->execute();
            $stmt->close();
        }
    }
}
header('Location: admin_listings.php');