<?php
$from = filter_input(INPUT_POST, 'from'); 
$message = filter_input(INPUT_POST, 'message'); 

if($from==='MPESA'){
    $phone = $from;
    $parts = explode(" ", $message);
    $code = $parts[0];
    $amount = 20;
    $days = 0;
    
    switch (true) {
        //case  ($amount < $package_daily): $hours = floor($amount*24/$package_daily); $interval='HOUR'; break;        
        case  ($amount == $package_daily): $days = floor($amount/$package_daily); break;
        case  ($amount <= $package_weekly): $days = floor($amount*7/$package_weekly); break;
        case  ($amount <= $package_monthly): $days = floor($amount*31/$package_monthly); break;
        case  ($amount <= $package_quarterly): $days = floor($amount*93/$package_quarterly); break;
        case  ($amount <= $package_halfly): $days = floor($amount*183/$package_halfly); break;
        default : $days = floor($amount*366/$package_annually); 
      }   

    $sql = 'INSERT INTO `customers`
                  (phone, mpesa_code, amount, days, expiry_time, created_by, created_at) 
                      VALUE(?,?,?,?,DATE_ADD(NOW(),INTERVAL ? DAY),1, NOW())';
    if ($stmt = $mysqli->prepare($sql)) {
        //$expire_after = ($days>0) ? $days : $hours;
        $stmt->bind_param("ssiii",$phone,$code,$amount,$days,$days);
        $stmt->execute();
        $stmt->close();
    }   
    $mysqli->close;
}
