<?php

include_once('config.php');
$data = new Data(0,0);

include_once('inc.left.php');

$full_recent = $data->getLatestReleases();
$full_recent = $data->allDataToDateData($full_recent);
krsort($full_recent);


$tpl_div_main_content_content = new Template('upcoming_releases');
$tpl_div_main_content_content->set('content', $full_recent);
$tpl_div_main_content_content->set('platform_colours', Site::getPlatformColours());
$tpl_div_main_content = new Template('left_div');
$tpl_div_main_content->set('div_id','main_content');
$tpl_div_main_content->set('div_title','recent releases');
$tpl_div_main_content->set('content', $tpl_div_main_content_content->fetch());

include_once('inc.getleft.php');



$tpl_body->set('left_content', $left_bar);
$tpl_body->set('right_content', $right_bar);

$template->set('title', 'GamersEvents');
$template->set('body_attributes', '');
$template->set('script', $javascript->getJavascriptString());
$template->set('body_content', $tpl_body->fetch());
$template->set('style', $tpl_style->fetch());

echo $template->fetch();

?>