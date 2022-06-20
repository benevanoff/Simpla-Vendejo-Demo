<?php
/* DB format
table: auth
+----------+-------------+------+-----+---------+-------+
| Field    | Type        | Null | Key | Default | Extra |
+----------+-------------+------+-----+---------+-------+
| username | varchar(35) | YES  |     | NULL    |       |
| password | varchar(64) | YES  |     | NULL    |       |
+----------+-------------+------+-----+---------+-------+

*/
session_start();
$salt = getenv("LOGIN_SALT");
if (isset($_POST['username']) && isset($_POST['pass'])) {
    $DATABASE_HOST = getenv("DB_HOST");
    $DATABASE_USER = getenv("DB_USER");
    $DATABASE_PASS = getenv("DB_PASSWD");
    $DATABASE_NAME = getenv("DB_NAME");
  // Try and connect using the info above.
  $db = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
  if ( mysqli_connect_errno() ) {
  // If there is an error with the connection, stop the script and display the error.
  }

  if ($stmt = $db->prepare('SELECT * FROM auth WHERE username="'.$_POST['username'].'"')) {
    $stmt->execute();
    $users = $stmt->get_result()->fetch_all();
    $stmt->close();
  }
  foreach ($users as $user) {
    if ($user[0] == $_POST['username'] && hash("sha256", $_POST['pass'].$salt) == $user[1]) {
      $_SESSION['admin'] = true;
      header('Location: admin_dash.php');
    }
  }
}
echo '
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<div class="container" style="padding-top:5%;">
<form class="center" method=post>
  <div class="form-group">
    <label>Username</label>
    <input type="text" class="form-control" id="usernameInput" name="username" placeholder="username">
  </div>
  <div class="form-group">
    <label for="pwd">Password</label>
    <input type="password" name="pass" class="form-control" id="passwordInput" placeholder="password">
  </div>
  <button type="submit" class="btn btn-primary">Login</button>
</form>
</div>
';