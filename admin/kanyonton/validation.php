<?php 

header("Content-Type:application/json"); 

if (!isset($_GET["token"]))
{
echo "Technical error";
exit();
}



if ($_GET["token"]!='yourPU_RstrongPasswordSample$')
{
echo "Invalid authorization";
exit();
}



/* 
here you need to parse the json format 
and do your business logic e.g. 
you can use the Bill Reference number 
or mobile phone of a customer 
to search for a matching record on your database. 

Consumer Key:	Gl9lwmjwcUqHzksuObGTrUEN9Q5G7kTt
Consumer Secret:	0IRTcP2yplH322fF
shortcode1: 601349


Shortcode 1	601349
Initiator Name (Shortcode 1)	apitest349
Security Credential (Shortcode 1)	Safaricom111!
Shortcode 2	600000
Test MSISDN	254708374149
ExpiryDate	2019-10-11T13:45:28+03:00
Lipa Na Mpesa Online Shortcode:	174379
Lipa Na Mpesa Online Passkey:
bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919

bYVsjZJh5xUIupjLDoiMjYpy9Ls1wpOb90j3BhK0NoXNUcfWZhRxyrH+9oopYl3gxw6CChd/Satjpb4BnF7KSUqbkI4gbi93ARnLYCHewImZFJcL+w/mC9wBF/Q5134oGh+/zFkVOsC/5Lm6By0csU3o242In8z4vjuAtfvmz4HBP2+/akblRGlfO6dtN32yyyB3nZb2QI7NqUyiuH+3Clg4kbRWaqS6+f/AmKMQ1WN0jhiR+qbSk3ELBPdk2txH4FLC/Z0veleZclxXkKfmV7e5EYyrASYXqWjG61LiRQcjwu0UvYXroU3VOkoQ6L2S0snCu5ZIS5lgvS7sD1/4HA==

*/ 

/* 
Reject an Mpesa transaction 
by replying with the below code 
*/ 

echo '{"ResultCode":1, "ResultDesc":"Failed", "ThirdPartyTransID": 0}'; 

/* 
Accept an Mpesa transaction 
by replying with the below code 
*/ 

echo '{"ResultCode":0, "ResultDesc":"Success", "ThirdPartyTransID": 0}';
 
?>