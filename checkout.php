<?php
session_start();
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
echo '
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<div class="container">
<form class="center" method=post action="new_order.php">
  <div style="display:flex;">
  <div class="form-group">
    <label>First Name</label>
    <input type="text" class="form-control" id="firstNameInput" name="first_name" placeholder="John">
  </div>
  <div class="form-group"> 
    <label>Last Name</label>
    <input type="text" class="form-control" id="lastNameInput" name="last_name" placeholder="Smith">
  </div>
  </div>
  <div class="form-group">
    <label for="pwd">Shipping Address</label>
    <input type="text" name="shipping_addr" class="form-control" id="addressInput" placeholder="ex: 504 Michigan Ave Chicago, IL 60611">
  </div>
  <div class="form-group">
    <label for="email">Contact email</label>
    <input style="width:30%;"type="text" name="contact" class="form-control" id="addressInput" placeholder="jj@mail.net">
  </div>
  <button type="submit" class="btn btn-primary">Continue to payment</button>
</form>
</div>
';