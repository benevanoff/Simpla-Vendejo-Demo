<?php
session_start();
if (!isset($_POST["order_id"]) || !isset($_SESSION["admin"]) || !$_SESSION["admin"]) {
    echo "error";
    return;
}

$DATABASE_HOST = getenv("DB_HOST");
$DATABASE_USER = getenv("DB_USER");
$DATABASE_PASS = getenv("DB_PASSWD");
$DATABASE_NAME = getenv("DB_NAME");
// Try and connect using the info above.
$db = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
// If there is an error with the connection, stop the script and display the error.
}

$stmt = $db->prepare('UPDATE orders SET shipped=current_date() WHERE order_id=?');
if ($stmt) {
    $stmt->bind_param("i", $_POST["order_id"]);
    $stmt->execute();
    $stmt->close();
}

header('Location: admin_orders.php');