<?php

include_once('config.php');
$data = new Data(0,0);

include_once('inc.left.php');

$tpl_div_main_content_content = new Template('contact');
$tpl_div_main_content_content->set('content', '');
$tpl_div_main_content = new Template('left_div');
$tpl_div_main_content->set('div_id','main_content');
$tpl_div_main_content->set('div_title','contact us');
$tpl_div_main_content->set('content', $tpl_div_main_content_content->fetch());

include_once('inc.getleft.php');



$tpl_body->set('left_content', $left_bar);
$tpl_body->set('right_content', $right_bar);

$template->set('title', 'GamersEvents');
$template->set('body_attributes', '');
$template->set('script', '');
$template->set('body_content', $tpl_body->fetch());
$template->set('style', $tpl_style->fetch());

//$debug->printr($data->getDataByDate($getloc));

echo $template->fetch();

?>