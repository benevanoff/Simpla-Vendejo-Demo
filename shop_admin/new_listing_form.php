<?php
session_start();
echo '
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<div class="container" style="padding-top:5%;">
<form class="center" method=post action="create_listing.php" enctype="multipart/form-data">
  <div style="display: flex;">
     <div class="form-group">
      <label>Product Name</label>
      <input type="text" class="form-control" id="productNameField" name="product_name" placeholder="name">
    </div>
    <div class="form-group">
      <label>Price (XMR)</label>
      <input type="text" class="form-control" id="priceField" name="price" placeholder="x.xx">
    </div>
  </div>
  <div class="form-group">
      <input type="file" name="listing_img">
  </div>
  <div class="form-group">
    <label for="description">Product Description</label>
    <textarea class="form-control" name="product_description"></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Save</button>
</form>
</div>
';