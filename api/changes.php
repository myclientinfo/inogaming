<?php

$format = isset($_REQUEST['format'])?$_REQUEST['format']:'xml';
$from = isset($_REQUEST['from'])?$_REQUEST['from']:false;
$to = isset($_REQUEST['to'])?$_REQUEST['to']:false;
$data_id = isset($_REQUEST['data_id'])?$_REQUEST['data_id']:false;
$limit = isset($_REQUEST['limit'])?$_REQUEST['limit']:false;

include_once('api_config.php');

$data = new Data(0, 0);

$data_listing = $data->getRssChanges($data_id, $from, $to, $limit);
$title = 'All';

foreach($data_listing as &$d){
    $d['description'] = $d['text'].' updated'.(!$data_id? ' for '.$d['title']:'');
    $d['link'] = 'http://www.vurp.com/games/'.Site::createLinkFromTitle($d['title']).'/';
    if($data_id) $title = $d['title'];
}
$title = htmlentities($title);
if($format == 'xml'){
	$xml = RSS::arrayIntoXML($data_listing,'item','item');
	header ("Content-Type: text/xml");
	?>
<?php echo '<?xml version="1.0"?>' ?>
<?php echo '<rss version="2.0"><channel>
	<title>Vurp Recent Changes - '.$title.'</title>
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