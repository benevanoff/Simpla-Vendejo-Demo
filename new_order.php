<?php
session_start();
/* DB Format
table: orders
+---------------+--------------+------+-----+---------+-------+
| Field         | Type         | Null | Key | Default | Extra |
+---------------+--------------+------+-----+---------+-------+
| order_id      | int(11)      | YES  |     | NULL    |       |
| details       | json         | YES  |     | NULL    |       |
| customer_name | varchar(50)  | YES  |     | NULL    |       |
| shipping_addr | varchar(100) | YES  |     | NULL    |       |
| contact       | varchar(30)  | YES  |     | NULL    |       |
| shipped       | date         | YES  |     | NULL    |       |
+---------------+--------------+------+-----+---------+-------+
*/
$DATABASE_HOST = getenv("DB_HOST");
$DATABASE_USER = getenv("DB_USER");
$DATABASE_PASS = getenv("DB_PASSWD");
$DATABASE_NAME = getenv("DB_NAME");
// Try and connect using the info above.
$db = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
// If there is an error with the connection, stop the script and display the error.
}

$order_id = random_int(1, 99999);
$cart = json_encode($_SESSION["cart"]);
if ($stmt = $db->prepare('INSERT INTO orders (order_id, details, customer_name, shipping_addr, contact) VALUES ('.$order_id.', \''.$cart.'\', "'.$_POST["first_name"].' '.$_POST["last_name"] .'", "'.$_POST["shipping_addr"].'", "'.$_POST["contact"].'")')) {
    $stmt->execute();
    $stmt->close();
}
unset($_SESSION["cart"]);

$api_url = 'http://localhost:5000/create_invoice';
$data = array('order_id' => $order_id);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($api_url, false, $context);
if ($result === FALSE) { /* Handle error */ }

header('Location: http://localhost/xmr_payment.php?order='.$order_id);