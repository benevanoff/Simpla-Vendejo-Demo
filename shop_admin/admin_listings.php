<?php
/*
DB Format
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

  $query = $db->query('SELECT * FROM listings');
  echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">';
if ($query) {
$listings = $query->fetch_all();
echo '
<div class=container style="padding:10%;">
<div class="center">
<table class="table">
<thead>
  <tr>
    <th scope="col">Prod ID</th>
    <th scope="col">Name</th>
    <th scope="col">Description</th>
    <th scope="col">Price</th>
  </tr>
</thead>
<tbody>';
foreach($listings as $listing) {
  echo '<tr>
    <th scope="row">'. $listing[0] .'</th>
    <td>'. $listing[1] .'</td>
    <td>'.$listing[2].'</td>
    <td>'.$listing[3].' XMR</td>
    <td> <form method=post action="delete_listing.php"> <input type="hidden" name="product" value="'.$listing[0].'"> <button type="submit" class="btn btn-danger"> Delete </button> </form> </td>
  </tr>';
}

echo '
  </tbody></table>
  </div></div>';
}
echo '
  <div style="display: flex;justify-content: center;align-items: center;">
        <form action="new_listing_form.php">
          <button type="submit" class="btn btn-primary">New Listing</button>
        </form>
  </div>
';
}