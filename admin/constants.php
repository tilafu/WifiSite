<?php
//Safaricom LNM 
$consumer_key = 'fJ81KlT8ar7FYBQrRXxYNXBD48bBBsYA';
$consumer_secret = 'kOcJajPubkTd63oF';
$Business_Code = '174379';
$Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
$Type_of_Transaction = 'CustomerPayBillOnline';
$Token_URL = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
$LNM_Online_URL = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
$OnlinePayment = 'https://mydomain.com/path';
$CallBackURL = 'https://mydomain.com/path';
$Time_Stamp = date("Ymdhis");
$transaction_description = 'Hotspot Daily Package';
$password = base64_encode($Business_Code . $Passkey . $Time_Stamp);
$credentials = base64_encode($consumer_key . ':' . $consumer_secret);

//database
$db_host = 'localhost';
$db_name = 'hotspot';
$db_user = 'dennis';
$db_pass = 'Mvprx8';

//Packages
$package_daily=20;
$package_weekly=120;
$package_monthly=500;
$package_quarterly=1200;
$package_halfly=2000;
$package_annually=3500;

