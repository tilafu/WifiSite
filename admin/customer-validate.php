<?php
header('Access-Control-Allow-Origin: *');
include './constants.php';  

$phone_number = filter_input(INPUT_POST, 'phone');  
$total_amount = filter_input(INPUT_POST, 'amount'); 

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$sql = <<<SQL
        SELECT c.`id`,c.`phone`,c.`device`
        FROM `customers` c
        WHERE c.`expiry_time`>NOW() AND c.`phone`=?
        ORDER BY c.`id` DESC 
        LIMIT 1;
SQL;
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s",$phone_number);
    $stmt->execute();
    $stmt->bind_result($id,$phone,$device);
    $allow = 0;
    while ($stmt->fetch()) { 
        $allow = 1;
    }
    $stmt->close();
}   

$mysqli->close();    

$response = '{"allow" : '.$allow.'}';
print $response;

//push stk
if(!$allow){
    include './kanyonton/push.php';
}




