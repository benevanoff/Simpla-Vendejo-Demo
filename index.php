<?php
session_start();
if (isset($_POST["add_cart"])) {
  $added_prod = json_decode($_POST["add_cart"], true);
  // check if the product is already in our cart
  if (isset($_SESSION["cart"])) {
    $found = false; // increment if found, create new entry if not
    foreach($_SESSION["cart"] as $prod_id => &$cart_item) {
      if ($prod_id == $added_prod["prod_id"]) {
        $found = true;
        $cart_item["quantity"] = $cart_item["quantity"]+1;
        break;
      }
    }
    if (!$found) {
      $added_prod["quantity"] = 1;
      $id = $added_prod["prod_id"];
      unset($added_prod["prod_id"]);
      $_SESSION["cart"][$id] = $added_prod;
    }
  } else {
    $added_prod["quantity"] = 1;
    $id = $added_prod["prod_id"];
    unset($added_prod["prod_id"]);
    $_SESSION["cart"][$id] = $added_prod;
  }
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
$query = $db->query('SELECT * FROM listings');
if (!$query) {
  echo "No Listings";
  return;
}
$listings = $query->fetch_all();
echo '
<html>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<head>
    <h2><center>Simpla Vendejo</center></h2>
    <form action="cart.php">
      <button class="btn btn-primary" style="position:absolute;top:2%;right:10px;">View Cart</button>
    </form>
</head>
<body>
  <div id="listings_container" class="text-center">
  ';
foreach($listings as $listing) {
  echo '
    <div class="">
      <img src="../listing_imgs/'.$listing[0].'.png" alt="Product" width="250" height="200">
        <div>
          <h4>'.$listing[1].'</h4>
          <h4>'.$listing[3].' XMR</h4>
          <form method=post>
            <input type="hidden" name="add_cart" value=\'{"prod_id":'.$listing[0].', "name":"'.$listing[1].'", "price":'.$listing[3].'}\'>
            <button type="submit">Add to cart</button>
          </form>
        </div>
    </div>
  ';
}

echo '
  
  </div>
</body>
</html>
';