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
    $prod_id = random_int(0, 999999);
    $extensions_allowed = ["png", "jpeg", "jpg"];
    $img_name = $_FILES["listing_img"]["name"];
    $file_extension = pathinfo($img_name)["extension"];
    if ($file_extension != "png") {
        echo "must be png";
        return;
    }
    $new_path = getcwd()."/../listing_imgs/".$prod_id.".".$file_extension;
    
    //if (in_array($file_extension, $extensions_allowed)) {
        $success = move_uploaded_file($_FILES["listing_img"]["tmp_name"], $new_path);
    //}

    $DATABASE_HOST = getenv("DB_HOST");
    $DATABASE_USER = getenv("DB_USER");
    $DATABASE_PASS = getenv("DB_PASSWD");
    $DATABASE_NAME = getenv("DB_NAME");
    // Try and connect using the info above.
    $db = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
    }

    $query = 'INSERT INTO listings (prod_id, name, description, price) VALUES (?, ?, ?, ?)';
    echo $query;
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param("issd", $prod_id, $_POST["product_name"], $_POST["product_description"], $_POST["price"]);
        $stmt->execute();
        $stmt->close();
    }
}
header('Location: admin_listings.php');
