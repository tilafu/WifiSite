<html>
    <head>
<?php 
   include_once './constants.php'; 
   include_once 'template/meta.php'; 
   include_once 'template/css.php';  
   
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
function code($len){
    $str = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($str),0, $len);
}

$customer_filter = filter_input(INPUT_POST, 'customer_filter'); 

if (isset($_REQUEST["btn_submit"])) {
    $code = filter_input(INPUT_POST, 'code'); 
    $phone= trim(filter_input(INPUT_POST, 'phone'));
    $amount = filter_input(INPUT_POST, 'amount');
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

      $literal = $days.' DAY';

    $sql = 'INSERT INTO `customers`
                  (phone, mpesa_code, amount, days, expiry_time, created_by, created_at) 
                      VALUE(?,?,?,?,DATE_ADD(NOW(),INTERVAL ? DAY),1, NOW())';
    if ($stmt = $mysqli->prepare($sql)) {
        //$expire_after = ($days>0) ? $days : $hours;
        $stmt->bind_param("ssisi",$phone,$code,$amount,$literal,$days);
        $stmt->execute();
        $stmt->close();
        echo '<script>'
                . 'alert("Customer '.$phone.' Added Successfully.\n'
                . 'Subscription: '.$days.' Days")'
           . '</script>';
    }     
 } 
?>
    </head>
    <body>
        <div class="main-body">
            <div class="page-wrapper">
               <div class="page-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-block">
                                    <form id="my_form_2" name="my_form" method="POST">
                                       <div class="form-group row">
                                           <div class="col-sm-5" style="font-weight: bolder">
                                               <select name="customer_filter" class="form-control form-control-sm" onchange="this.form.submit()">
                                                    <option value="all">All Customers</option>
                                                    <option <?php echo $customer_filter=='today' ? 'selected' : ''; ?> value="today">Today New Customers Only</option>
                                                    <option <?php echo $customer_filter=='month' ? 'selected' : ''; ?> value="month">This Month New Customers</option>
                                                    <option <?php echo $customer_filter=='active' ? 'selected' : ''; ?> value="active">Active Customers Only</option>
                                                </select>
                                            </div>
                                        </div>                                         
                                   </form>
                                    
                                    <div class="dt-responsive table-responsive">
                                        <table id="alt-pg-dt" class="table table-striped table-bordered nowrap" style="font-size: smaller">
                                                   <thead>
                                                      <tr>
                                                            <th>#</th>
                                                            <th class="text-center">Phone</th>
                                                            <!--
                                                            <th class="text-center">MPESA Code</th>
                                                            <th class="text-center">Amount</th>
                                                            -->
                                                            <th class="text-center">Time</th>
                                                            <th class="text-center">Start</th>
                                                            <th class="text-center">Expiry</th>
                                                            <th class="text-center">Status</th>
                                                      </tr>
                                                   </thead>
                                                   <tbody>
<?php 
$filter = '';
if (isset($_REQUEST["customer_filter"])) {    
    switch($customer_filter){
        case 'today': $filter = ' WHERE DATE(created_at) = DATE(CURRENT_DATE()) '; break;
        case 'month': $filter = ' WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE()) '; break;
        case 'month': $filter = ' WHERE YEAR(created_at) = YEAR(CURRENT_DATE()) '; break;
        case 'active': $filter = ' WHERE expiry_time > NOW() '; break;
        default: $filter = ''; break;
    }
    
}

    $sql = 
        'SELECT id, phone, device, mpesa_code, amount, days, created_at, expiry_time, NOW()
        FROM `customers` '.$filter.' ORDER BY id ASC';
    $counter = $ttl_amt = $ttl_active = 0;
    if ($stmt = $mysqli->prepare($sql)) {
          $stmt->execute();
          $stmt->bind_result($id, $phone, $device, $mpesa_code, $amount, $days, $created_at, $expiry_time, $now);
          while ($stmt->fetch()) { 
              $counter++;
              $ttl_amt+=$amount;
              if($device==0){
                $status = '<b style="color: navy;">pending</b>';
              }else{
                  if($expiry_time<=$now){
                      $status = '<b style="color: red;">expired</b>';
                  }else{
                      $status = '<b style="color: green;">active</b>';
                      $ttl_active++;
                  }
              }

             // $color = ($remaining<=0) ? 'red' : (($remaining<7) ? 'orange' : 'green');
              ?>
                                                    <tr>
                                                        <td><?php echo $counter; ?>.</td>
                                                        <td class="text-center"><?php echo $phone; ?></td>
                                                        <!--
                                                        <td class="text-center"><?php echo $mpesa_code; ?></td>
                                                        <td class="text-center"><?php echo number_format($amount); ?></td>
                                                        -->
                                                        <td class="text-center"><?php echo $days; ?></td>
                                                        <td class="text-center"><?php echo $created_at; ?></td>
                                                        <td class="text-center"><?php echo $expiry_time; ?></td>
                                                        <td class="text-center"><?php echo $status; ?></td>
                                                    </tr>
<?php  }
}
           
    $mysqli->close();
          ?>
                                                </tbody>
                                                <!--
                                                <tfoot>
                                                      <tr>
                                                            <th class="text-right" colspan="3">Total Amount Received</th>
                                                            <th class="text-center"><?php echo number_format($ttl_amt); ?></th>
                                                            <th class="text-right" colspan="3">Active Customers</th>
                                                            <th class="text-center"><?php echo number_format($ttl_active); ?></th>
                                                      </tr>
                                                </tfoot>
                                                -->
                                                </table>
                                             </div>
                                </div>
                            </div>
                        </div>

                     <div class="col-sm-4">
                        <div class="card">
                           <div class="card-block">
                               <b>Add New Customer Manually</b>
                               <hr />
            <form id="my_form" name="my_form" method="POST">
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">MPESA Code:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" placeholder="MPESA CODE"
                                   name="code" id="code" required="required" value="<?php echo 'QC'.code(8); ?>">
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Phone No.:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" placeholder="07..."
                                   name="phone" id="phone" required="required">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Amount</label>
                        <div class="col-sm-7">
                            <input type="number" class="form-control" placeholder="Amount"
                                   name="amount" id="amount" required="required">
                        </div>
                    </div> 
                <div class="form-group row">
                    <div class="col-sm-6">
                        <button type="button" name="cancel_changes" id="cancel_changes" class="btn btn-sm btn-danger waves-effect pull-left">Cancel</button>
                    </div>
                    <div class="col-sm-6">
                        <button type="submit" name="btn_submit" id="btn_submit" value="btn_submit" class="btn btn-sm btn-primary waves-effect waves-light pull-right">Submit Customer</button>
                    </div>
                </div>
            </form>
        </div>
                                            </div>
                        <div class="card">
                           <div class="card-block">
                               <b>Hotspot Package Rates</b>
                               <div>
                                   <table class="table table-striped table-condensed" style="font-size: smaller">
                                       <thead>
                                          <tr>
                                            <th>#</th>
                                            <th class="text-center">Package</th>
                                            <th class="text-center">Price (KShs.)</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                           <tr>
                                               <td>1.</td>
                                               <td class="text-center">Daily</td>
                                               <td class="text-center">20</td>
                                           </tr>   
                                           <tr>
                                               <td>2.</td>
                                               <td class="text-center">Weekly</td>
                                               <td class="text-center">120</td>
                                           </tr>   
                                           <tr>
                                               <td>2.</td>
                                               <td class="text-center">Monthly</td>
                                               <td class="text-center">500</td>
                                           </tr>   
                                       </tbody>
                                    </table>
                               </div>
                           </div>
                        </div>
                              </div>
                           </div>
        </div>
                           </div>
        </div>
        <?php include_once 'template/js.php'; ?>
    </body>
</html>
