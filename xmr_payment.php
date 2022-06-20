<?php
$DATABASE_HOST = getenv("DB_HOST");
$DATABASE_USER = getenv("DB_USER");
$DATABASE_PASS = getenv("DB_PASSWD");
$DATABASE_NAME = getenv("DB_NAME");
// Try and connect using the info above.
$db = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
// If there is an error with the connection, stop the script and display the error.
}
sleep(1);
if ($stmt = $db->prepare('SELECT * FROM orders WHERE order_id='. $_GET["order"])) {
    $stmt->execute();
    $order_res = $stmt->get_result()->fetch_all();
    $stmt->close();
}
$cart = json_decode($order_res[0][1], true);
$cart_total_xmr = 0;
foreach($cart as $cart_entry) {
    $cart_total_xmr += $cart_entry["price"] * $cart_entry["quantity"];
}
unset($cart_entry);

if ($stmt = $db->prepare('SELECT * FROM payments WHERE order_id='. $_GET["order"])) {
    $stmt->execute();
    $payment_res = $stmt->get_result()->fetch_all();
    $stmt->close();
}

$payment_addr = $payment_res[0][1];
$payment_string = "Please send ".$cart_total_xmr." XMR to ";

if (!count($payment_res)) echo "error";
else {
echo '
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<div style="padding-top:15%;">
    <center><h4 id="payment_string">'.$payment_string.'</h4></center>
    <center><p>'.$payment_addr.'</p></center>
    <img id="qr_img"style="display:block;margin:auto;" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='.$payment_addr.'">
    <noscript>
    <form method=get>
        <input type="hidden" name="order" value="'.$_GET["order"].'">
        <input type="hidden" name="check" value="true">
        <center><button type="submit">Check Payment Status</button><center>
    <form>
    </noscript>
</div>
<script src=https://code.jquery.com/jquery-3.6.0.min.js></script>
<script>
function updatePaymentString() {
    $("#payment_string").text("A payment has been found, your cart will be shipped shortly");
    $("#qr_img").attr("src","checkmark.svg");
}
$(function poll() {
    setTimeout(function() {
        $.ajax({
            url: "check_status.php",
            type: "POST",
            data: "order_id='.$_GET["order"].'",
            success: function(result) {
                var p_string = document.getElementById("payment_string").innerHTML;
                var invoice_amount = parseFloat(p_string.match(/[\d\.]+/));
                var parsed = jQuery.parseJSON(result);
                var seen = parsed["seen"];
                var confirmed = parsed["10conf"];
                if (seen >= invoice_amount) {
                    updatePaymentString();
                }
            },
            complete: poll
        });
    }, 
    10000);
});
</script>
';
}