<?php
/* DB format
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
  $success = $db->query('SELECT * FROM orders NATURAL JOIN payments WHERE shipped IS NOT NULL AND SEEN IS NOT NULL');
  if ($success) $recent_orders = $success->fetch_all();

    echo '
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <div style="display: flex;justify-content: center;align-items: center;">
        <form action="admin_orders.php" style="padding:10px;padding-top:3%;">
          <button type="submit" class="btn btn-primary">Manage Orders</button>
        </form>
        <form action="admin_listings.php" style="padding:10px;padding-top:3%;">
          <button type="submit" class="btn btn-primary">Manage Listings</button>
        </form>
    </div>';
  if ($success) {
echo '
<div class=container>
<div class="center">
<table class="table">
<thead>
  <tr>
    <th scope="col">Order ID</th>
    <th scope="col">Customer Name</th>
    <th scope="col">Contact</th>
    <th scope="col">Payment status</th>
  </tr>
</thead>
<tbody>';
foreach($recent_orders as $order) {
  if (!is_null($order[7])) { // if seen is not null
    $payment_status = is_null($order[8]) ? '<span class="badge badge-warning badge-pill">Pending</span>' : '<span class="badge badge-primary badge-pill">Paid</span>';
  } else {
    $payment_status = '<span class="badge badge-danger badge-pill">Unpaid</span>';
  }
  echo '<tr>
    <th scope="row">'. $order[0] .'</th>
    <td>'. $order[2] .'</td>
    <td>'. $order[4] .'</td>
    <td>'.$payment_status.'</td>
  </tr>';
}
unset($order);
echo '
</tbody></table>
</div></div>';
  } else echo "<center>No orders</center>";
} else {
    echo 'denied';
}
