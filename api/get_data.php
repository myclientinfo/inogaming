<?php

$format = $_REQUEST['format'];
$id = $_REQUEST['id'];

include_once('api_config.php');

$data = new Data($id, 0);

$send = $data->data_info;
if($format == 'xml'){
	$xml = RSS::arrayIntoXML($send,'game');
	header ("Content-Type: text/xml");
	echo $xml;
} elseif($format == 'json') {
	header('X-JSON: ('.json_encode($send).')');
}
?>