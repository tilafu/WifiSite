<?php
header('Access-Control-Allow-Origin: *');
$phone = filter_input(INPUT_GET, 'phone');
$amount = filter_input(INPUT_GET, 'amount');

//$url = 'http://185.141.62.34/hotspot/customer-validate.php';
$url = 'http://192.168.12.72/wifi/hotspot/customer-validate.php?';

$myvars = 'phone=' . $phone 
        . '&amount=' . $amount;

$ch = curl_init( $url );
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);

echo $response;
