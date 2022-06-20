<?php
session_start();
if (isset($_GET["order_id"]) && isset($_SESSION["admin"]) && $_SESSION["admin"]) {
    $DATABASE_HOST = getenv("DB_HOST");
    $DATABASE_USER = getenv("DB_USER");
    $DATABASE_PASS = getenv("DB_PASSWD");
    $DATABASE_NAME = getenv("DB_NAME");
    // Try and connect using the info above.
    $db = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if ( mysqli_connect_errno() ) {
      // If there is an error with the connection, stop the script and display the error.
    }
    if ($stmt = $db->prepare('SELECT * FROM orders WHERE order_id="'.$_GET["order_id"].'"')) {
        $stmt->execute();
        $order_result = ($stmt->get_result()->fetch_all())[0];
        $stmt->close();
    }
    $order_cart = json_decode($order_result[1], true);
    
    echo '
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <div class="container" style="padding:6%;">
      <div class="list-group center">';
      foreach ($order_cart as $cart_item) {
        echo '<button class="list-group-item list-group-item-action"><center>'.$cart_item["name"].' <span class="badge badge-info badge-pill">'.$cart_item["price"].' XMR</span>';
        if ($cart_item["quantity"] > 1) echo ' <span class="badge badge-primary badge-pill"> x'.$cart_item["quantity"].'</span>';
        echo '</center></button>';
      }
      unset($cart_item);
  echo '</div>
    <h4 class="text-center" style="padding-top:1%;">'.$order_result[2].'; '.$order_result[4].'</h4>
    <p class="text-center">'.$order_result[3].'</p>
    <form method="POST" action="mark_shipped.php">
      <input type="hidden" name="order_id" value="'.$order_result[0].'">
      <center><button type="submit">Mark Shipped</button></center>
    </form>
    </div>
    ';
}