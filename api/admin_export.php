<?php

include_once('api_config.php');
include_once('../class.auth.php');
$auth = Auth::isLoggedIn();
if (!$auth) header("HTTP/1.0 404 Not Found");

$data = new Data(0,0);
header("Pragma: ");
header("Cache-Control: ");
header("Content-type: application/download");
header('Content-Disposition: attachment; filename=vurp_'.date('Y-m-d').'.csv');
$data_array = Data::getExportForOwner($_SESSION['owner_id']);
?>
Vurp Id, Title, Platform, Release Date, Price
<?php
foreach($data_array as $d){
?><?php echo $d['vurp_id']?>,"<?php echo $d['title']?>",<?php echo $d['platform']?>,<?php echo Site::getTimeString($d['start_time'],$d['end_time'],false,true)?>,<?php echo isset($d['price']) && $d['price'] !='' ? $d['price'] : $d['t_price']?>

<?php
}
?>