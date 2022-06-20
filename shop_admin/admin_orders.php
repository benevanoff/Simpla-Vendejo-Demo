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
  $query_res = $db->query('SELECT order_id, seen, 10conf, shipped FROM orders NATURAL JOIN payments WHERE shipped IS NULL AND 10conf IS NOT NULL');
  if (!$query_res) return;
  $ready_orders = ($query_res->fetch_all());

  $query_res = $db->query('SELECT order_id, seen, 10conf, shipped FROM orders NATURAL JOIN payments WHERE shipped IS NULL AND 10conf IS NULL');
  if (!$query_res) return;
  $unpaid_orders = ($query_res->fetch_all());

  $query_res = $db->query('SELECT order_id, seen, 10conf, shipped FROM orders NATURAL JOIN payments WHERE shipped IS NOT NULL');
  if (!$query_res) return;
  $history_orders = ($query_res->fetch_all());

  echo '
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <div class="container" style="padding:2%;">
    <h3 class="text-center">Ready Orders</h3>
    <div class="list-group center">';
    foreach ($ready_orders as $order) {
      echo '<a href=admin_order_detail.php?order_id='.$order[0].' class="list-group-item list-group-item-action"><center>'.$order[0].'</center></a>';
    }
    unset($order);
echo '</div>
  </div>
  <div class="container">
    <h3 class="text-center">Unpaid Orders</h3>
    <div class="list-group center">';
    foreach ($unpaid_orders as $order) {
      $badge = is_null($order[1]) ? '' : '<span class="badge badge-warning badge-pill">Pending</span>';
      echo '<a href=admin_order_detail.php?order_id='.$order[0].' class="list-group-item list-group-item-action"><center>'.$order[0].' '.$badge.'</center></a>';
    }
    unset($order);
echo '
    </div>
  </div>
  <div class="container">
    <h3 class="text-center">Shipped Orders</h3>
    <div class="list-group center">';
    foreach ($history_orders as $order) {
      echo '<a href=admin_order_detail.php?order_id='.$order[0].' class="list-group-item list-group-item-action"><center>'.$order[0].'</center></a>';
    }
    unset($order);
echo'</div></div>';
}