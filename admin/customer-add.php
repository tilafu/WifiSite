<?php
header('Access-Control-Allow-Origin: *');
include_once './constants.php'; 
    
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
function code($len){
    $str = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($str),0, $len);
}

if (isset($_REQUEST["phone"])){
    $code = 'QC'.code(8); 
    $phone= filter_input(INPUT_POST, 'phone');
    $amount = filter_input(INPUT_POST, 'amount');
    
    switch (true) {
        case  ($amount <= 0): $days = 0; break;
        case  ($amount <= $package_daily): $days = ceil($amount/$package_daily); break;
        case  ($amount <= $package_weekly): $days = ceil($amount*7/$package_weekly); break;
        case  ($amount <= $package_monthly): $days = ceil($amount*31/$package_monthly); break;
        case  ($amount <= $package_quarterly): $days = ceil($amount*93/$package_quarterly); break;
        case  ($amount <= $package_halfly): $days = ceil($amount*183/$package_halfly); break;
        default : $days = ceil($amount*366/$package_annually); 
      }

    $interval = $days===0 ? 'HOUR' : 'DAY';
    $span = $days===0 ? 1 : $days;
    $literal = $span.' '.$interval;

    $sql = "
              INSERT INTO `customers`
                  (phone, mpesa_code, amount, days, expiry_time, created_by, created_at) 
                      VALUE(?,?,?,?,DATE_ADD(NOW(),INTERVAL ? ".$interval."),1, NOW())";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssisi",$phone,$code,$amount,$literal,$span);
        $stmt->execute();
        $stmt->close();
        echo '{'
                . '"status" : "success",'
                . '"mpesa_code" : "'.$code.'",'
                . '"phone" : "'.$phone.'",'
                . '"amount" : '.$amount.','
                . '"days" : '.$days.','
            . '}';
    }     
 } 
?>