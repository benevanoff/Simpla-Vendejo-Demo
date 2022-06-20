<?php
$api_url = 'http://localhost:5000/payment_status/'.$_POST["order_id"];

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'GET',
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($api_url, false, $context);
if ($result === FALSE) { /* Handle error */ }
else echo $result;