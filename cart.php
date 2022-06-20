<?php
/* DB format
table: listings
+-------------+-------------+------+-----+---------+-------+
| Field       | Type        | Null | Key | Default | Extra |
+-------------+-------------+------+-----+---------+-------+
| prod_id     | int(11)     | YES  |     | NULL    |       |
| name        | varchar(35) | YES  |     | NULL    |       |
| description | text        | YES  |     | NULL    |       |
| price       | float       | YES  |     | NULL    |       |
+-------------+-------------+------+-----+---------+-------+
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
session_start();
$DATABASE_HOST = getenv("DB_HOST");
$DATABASE_USER = getenv("DB_USER");
$DATABASE_PASS = getenv("DB_PASSWD");
$DATABASE_NAME = getenv("DB_NAME");
// Try and connect using the info above.
$db = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
// If there is an error with the connection, stop the script and display the error.
}

$cart_entries = [];
$total_cost = 0;
if (isset($_SESSION["cart"])) {
  foreach($_SESSION["cart"] as $prod_id => $cart_item) {
      if ($stmt = $db->prepare('SELECT * FROM listings WHERE prod_id=?')) {
          $stmt->bind_param("i", $prod_id);
          $stmt->execute();
          $listing = $stmt->get_result()->fetch_all();
          $stmt->close();
          $entry = array("prod_id" => $prod_id, "quantity" => $cart_item["quantity"], "ppu" => $listing[0][3], "name" => $listing[0][1]);
          $total_cost += $listing[0][3]*$cart_item["quantity"];
          array_push($cart_entries, $entry);
      }
  } 
}

echo '
<html>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<div class=container style="padding-left:10%;padding-right:10%;">
  <div class="center">
    <table class="table">
    <thead>
    <tr>
      <th scope="col">Item</th>
      <th scope="col">Quantity</th>
      <th scope="col">Price per unit</th>
    </tr>
    </thead>
    <tbody>';
if (count($cart_entries) > 0) {
  foreach($cart_entries as $cart_entry) {
    echo '<tr>
        <th scope="row">'.$cart_entry["name"].'</th>
        <td>'. $cart_entry["quantity"] .'</td>
        <td>'.$cart_entry["ppu"].'</td>
     </tr>';
  }
}
echo '
<tr>
    <th scope="row">Total</th>
    <td>-</td>
    <td>'.$total_cost.' XMR</td>
</tr>
</tbody></table>

<form action="checkout.php">
    <button class="btn btn-primary">Checkout</button>
</form>
</div></div>
</html>
';