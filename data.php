<?php

include_once('config.php');

$data_id = Data::getDataIdFromTitle($_GET['data']);
$instance_id = isset($_GET['instance_id']) ? $_GET['instance_id'] : 0;

$data = new Data($data_id, 0);

include_once('inc.left.php');

$data_array = $data->data_info;

if(isset($_GET['instance_id']) && isset($data_array['instances'][$instance_id])){
	$instance_array = $data_array['instances'][$_GET['instance_id']];
	$itinerary_array = $instance_array['times'];
} else {
	$instance_array = array();
	$itinerary_array = array();
}

$colour_array = Site::getPlatformColours();
$owner_array = $data->getDataOwnerInfo($data_id);

$tpl_div_ins1_content = new Template('data');
$tpl_div_ins1_content->set('data_array', $data_array);
$tpl_div_ins1_content->set('instance_array', $instance_array);
$tpl_div_ins1_content->set('colour_array', $colour_array);
$tpl_div_ins1_content->set('owner_array', $owner_array);
$tpl_div_ins1_content->set('data_id', $data_id);
$tpl_div_ins1_content->set('instance_id', $instance_id);
$tpl_div_ins1 = new Template('left_div');
$tpl_div_ins1->set('div_title', strtolower($data_array['data_title']));
$tpl_div_ins1->set('div_id','normal');
$tpl_div_ins1->set('content', $tpl_div_ins1_content->fetch());


$tpl_google_c = new Template('google_maps');
$tpl_google_c->set('data_array', $data_array);
$tpl_google_c->set('instance_array', $instance_array);
$tpl_google = new Template('left_div');
$tpl_google->set('div_title', 'google map');
$tpl_google->set('div_id','google_map');
$tpl_google->set('content', $tpl_google_c->fetch());
//$GLOBALS['debug']->printr($instance_array);

$tpl_instances_c = new Template('instance_list');
$tpl_instances_c->set('data_array', $data_array);
$tpl_instances_c->set('instance_array', $data_array['instances']);
$tpl_instances = new Template('left_div');
$tpl_instances->set('div_title', 'events');
$tpl_instances->set('div_id','instances');
$tpl_instances->set('content', $tpl_instances_c->fetch());

if(!empty($instance_array)){
	$contact_array = $data->getContact($instance_id);
	if(!empty($contact_array)){
	    $tpl_contacts_c = new Template('contacts');
	    $tpl_contacts_c->set('data_array', $data_array);
	    $tpl_contacts_c->set('instance_array', $instance_array);
	    $tpl_contacts_c->set('contact_array', $contact_array);
	    $tpl_contacts = new Template('left_div');
	    $tpl_contacts->set('div_title', 'contacts');
	    $tpl_contacts->set('div_id','contacts');
	    $tpl_contacts->set('content', $tpl_contacts_c->fetch()); 
    }
} else {
	
}

//if(count($itinerary_array)>1){
    $tpl_itinerary_c = new Template('itinerary');
    $tpl_itinerary_c->set('itinerary_array', $itinerary_array);
    $tpl_itinerary_c->set('instance_array', $instance_array);
    $tpl_itinerary = new Template('left_div');
    $tpl_itinerary->set('div_title', 'full itinerary');
    $tpl_itinerary->set('div_id','itinerary');
    $tpl_itinerary->set('content', $tpl_itinerary_c->fetch());
//}

include_once('inc.getleft.php');

$right_bar .= $tpl_div_ins1->fetch();
if(!$instance_id) $right_bar .= $tpl_instances->fetch();
if(!empty($instance_array) && isset($instance_array['map']) && $instance_array['map']!='') $right_bar .= $tpl_google->fetch();
if($instance_id && !empty($contact_array)) $right_bar .= $tpl_contacts->fetch();
if($instance_id) $right_bar .= $tpl_itinerary->fetch();

$tpl_body->set('left_content', $left_bar);
$tpl_body->set('right_content', $right_bar);

$template->set('title', 'GamersEvents - '.$data_array['data_title']);
$template->set('body_attributes', '');
$template->set('script', '');
$template->set('body_content', $tpl_body->fetch());
$template->set('style', $tpl_style->fetch());

echo $template->fetch();
?>