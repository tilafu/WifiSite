<?php
header('Access-Control-Allow-Origin: *');
include_once '../constants.php';

$phone_number =  filter_input(INPUT_POST, 'phone');
$total_amount = filter_input(INPUT_POST, 'amount');
$account_reference = filter_input(INPUT_POST, 'phone');

$curl_Tranfer = curl_init();
curl_setopt($curl_Tranfer, CURLOPT_URL, $Token_URL);
$credentials = base64_encode($consumer_key . ':' . $consumer_secret);
curl_setopt($curl_Tranfer, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials));
curl_setopt($curl_Tranfer, CURLOPT_HEADER, false);
curl_setopt($curl_Tranfer, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_Tranfer, CURLOPT_SSL_VERIFYPEER, false);
$curl_Tranfer_response = curl_exec($curl_Tranfer);
$token = json_decode($curl_Tranfer_response)->access_token;

$ch = curl_init($LNM_Online_URL);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer '.$token,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{
    "BusinessShortCode": '.$Business_Code.',
    "Password": "'.$password.'",
    "Timestamp": "'.$Time_Stamp.'",
    "TransactionType": "'.$Type_of_Transaction.'",
    "Amount": '.$total_amount.',
    "PartyA": '.$phone_number.',
    "PartyB": '.$Business_Code.',
    "PhoneNumber": '.$phone_number.',
    "CallBackURL": "'.$CallBackURL.'",
    "AccountReference":"'.$account_reference.'",
    "TransactionDesc":"'.$transaction_description.'"
  }');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response     = curl_exec($ch);
curl_close($ch);

echo $response;

?>