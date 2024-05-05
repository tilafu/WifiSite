<?php
if (!$request=file_get_contents('php://input')){
	echo "Invalid input";
	exit();
}

class Db{
    public $host = 'localhost';
    public $name = 'tipspesa';
    public $user = 'root';
    public $pass = 'Mmxsp65#';
}

$db = new Db();                            
$mysqli = new mysqli($db->host, $db->user, $db->pass, $db->name);

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$sql = <<<SQL
        INSERT INTO `dump` (`request`) VALUES(?);
SQL;
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s",$request);
    $stmt->execute();
    $stmt->close();
}   

/*
{
    "Body":{
        "stkCallback":{
            "MerchantRequestID":"8607-59197564-1",
            "CheckoutRequestID":"ws_CO_180820201059100735",
            "ResultCode":0,
            "ResultDesc":"The service request is processed successfully.",
            "CallbackMetadata":{
                "Item":[
                    {"Name":"Amount","Value":1.00},
                    {"Name":"MpesaReceiptNumber","Value":"OHI7PU1YN9"},
                    {"Name":"Balance"},
                    {"Name":"TransactionDate","Value":20200818105927},
                    {"Name":"PhoneNumber","Value":254723111920}
                ]
            }
        }
    }
}
*/
$request_array = json_decode($request, true);
$Body = $request_array['Body'];
$stkCallback = $Body['stkCallback'];

$MerchantRequestID = $stkCallback['MerchantRequestID'];
$CheckoutRequestID = $stkCallback['CheckoutRequestID'];
$ResultCode = $stkCallback['ResultCode'];
$ResultDesc = $stkCallback['ResultDesc'];

$CallbackMetadata = $stkCallback['CallbackMetadata'];
$Items = ($CallbackMetadata['Item']);
foreach($Items as $item){
    switch($item['Name']){
        case 'Amount': $amount = $item['Value']; break;
        case 'MpesaReceiptNumber': $MpesaReceiptNumber = $item['Value']; break;
        case 'TransactionDate': $TransactionDate = date("Y-m-d H:i:s",strtotime($item['Value'])); break;
        case 'PhoneNumber': $PhoneNumber = $item['Value']; break;
    }
}

$sql = <<<SQL
        INSERT INTO `mpesa_responses` VALUES(null,?,?,?,?,?,?,?,?,NOW());
SQL;
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("ssisdsss",$MerchantRequestID,$CheckoutRequestID,$ResultCode,$ResultDesc,
                                $amount,$MpesaReceiptNumber,$TransactionDate,$PhoneNumber);
    $stmt->execute();
    $stmt->close();
}   

$mysqli->close();     
        