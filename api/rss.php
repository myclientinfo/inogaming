<?php

$format = isset($_REQUEST['format'])?$_REQUEST['format']:'xml';
$from = isset($_REQUEST['from'])?$_REQUEST['from']:'';
$to = isset($_REQUEST['to'])?$_REQUEST['to']:'';
$platform = isset($_REQUEST['platform'])?$_REQUEST['platform']:false;
$order_by = isset($_REQUEST['order_by'])?$_REQUEST['order_by']:'start_time ASC';

//Time constants
$to = strtolower($to);
$from = strtolower($from);

switch($from){
    case 'now':
    case 'today': $from = date('Y-m-d'); break;
    case 'one_month': $from = date('Y-m-d', time() - (60*60*24*30)); break;
    case 'one_week': $from = date('Y-m-d', time() - (60*60*24*7)); break;
    case 'this_month': $from = date('Y-m-01'); break;
    case 'next_month': $mktime = mktime(0, 0, 0, date('n')+1, 1, date('Y'));
					    $from = date('Y-m-01', $mktime); 
    					break;
}

switch($to){
    case 'now':
    case 'today': $to = date('Y-m-d'); break;
    case 'one_month': $to = date('Y-m-d', time() + (60*60*24*30)); break;
    case 'one_week': $to = date('Y-m-d', time() + (60*60*24*7)); break;
    case 'this_month': $to = date('Y-m-t'); break;
    case 'next_month': $mktime = mktime(0, 0, 0, date('n')+1,  1, date('Y')); 
					    $to = date('Y-m-t', $mktime); 
					    break;
}

include_once('api_config.php');
$platform_array = Data::getFullCategories();
$data = new Data(0, 0);

$data_listing = $data->getAllData('', $from, $to, false, $order_by, '', false, ' t.start_time != "0000-00-00 00:00:00" '.($platform?' AND i.category_id = '.$platform:''));
$wanted_array = array('data_title','start_time','platform', 'description');
foreach($data_listing as &$d){

    $d['title'] = $d['data_title'];
    $d['release_date'] = str_replace(' 00:00:00','',$d['start_time']);
    //$d['description'] = $d['data_description'];
    $d['link'] = 'http://www.vurp.com/games/'.Site::createLinkFromTitle($d['title']).'/'.$d['platform'].'.html';

    //foreach($d as &$f){
    //    if(!in_array($f,$wanted_array)) unset($d[$f]);
    //}
    unset($d['end_time']);
    unset($d['start_time']);
    unset($d['data_title']);
    unset($d['data_html']);
    unset($d['data_description']);
    unset($d['instance_html']);
    unset($d['instance_id']);
    unset($d['instance_description']);
    unset($d['instance_publisher']);
    unset($d['instance_developer']);
    unset($d['instance_title']);
    unset($d['city']);
    unset($d['state']);

}
if($format == 'xml'){
	$xml = RSS::arrayIntoXML($data_listing,'item','item');
	header ("content-type: text/xml");
	?>
<?php echo '<?xml version="1.0"?>' ?>
<?php
	echo '<rss version="2.0"><channel>
	<title>Vurp RSS Feed</title>
		<link>http://www.vurp.com</link>
		<description>Video Game Upcoming Release Program</description>
		<language>en-us</language>
		<copyright>2007</copyright>
		<lastBuildDate>';
	echo date('r');
	echo '</lastBuildDate>
		<webMaster>matt@vurp.com</webMaster>'.str_replace('&','&amp;',$xml).'</channel></rss>';
} elseif($format == 'json') {
	header('X-JSON: ('.json_encode($data_listing).')');
}

?>