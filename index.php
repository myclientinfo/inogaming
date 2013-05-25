<?php

include_once('config.php');

$platform = isset($_GET['platform'])?$_GET['platform']:'';

$category_array = Data::getFullCategories();
$platform_id = Site::searchArray(Data::getFullCategories(), 'category_short', $platform, 'category_id');



$start_day_of_week = Site::getTime('start_this_week');

$random_instance = Data::getRandomInstance();
$data_id = $random_instance['data_id'];
$instance_id = $random_instance['instance_id'];

$data = new Data($data_id,$instance_id);
$colour_array = Site::getPlatformColours();
$owner_array = $data->getDataOwnerInfo($data_id);

include_once('inc.left.php');

$data_array = $data->data_info;

$tpl_div_main_content_content = new Template('upcoming_data_main');
$tpl_div_main_content_content->set('content', $data_listing);
$tpl_div_main_content = new Template('left_div');

$tpl_div_main_content->set('div_id','main_content');
$tpl_div_main_content->set('div_title','upcoming events');
$tpl_div_main_content->set('content', $tpl_div_main_content_content->fetch());

$tpl_div_ins1_content = new Template('data');
$tpl_div_ins1_content->set('data_array', $data_array);
$tpl_div_ins1_content->set('instance_array', $data->instance_info);
$tpl_div_ins1_content->set('colour_array', $colour_array);
$tpl_div_ins1_content->set('owner_array', $owner_array);
$tpl_div_ins1_content->set('data_id', $data_id);
$tpl_div_ins1_content->set('instance_id', $instance_id);
$tpl_div_ins1 = new Template('left_div');
$tpl_div_ins1->set('div_title', strtolower($data_array['data_title']));
$tpl_div_ins1->set('div_id','normal');
$tpl_div_ins1->set('content', $tpl_div_ins1_content->fetch());

include_once('inc.getleft.php');

if(!isset($_GET['platform'])) $right_bar .= $tpl_div_ins1->fetch();

$tpl_body->set('left_content', $left_bar);
$tpl_body->set('right_content', $right_bar);

$template->set('title', 'GamersEvents.com');
$template->set('body_attributes', '');
$template->set('script', $javascript->getJavascriptString());
$template->set('body_content', $tpl_body->fetch());
$template->set('style', $tpl_style->fetch());

echo $template->fetch();

?>