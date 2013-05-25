<?php

include_once('config.php');
$debug = new Debug(false);
$debug->debugStatus(true);

$auth = Auth::isLoggedIn();

if (!$auth) header('location:admin.html');

$data = new Data(0,0);

$data_array = $data->getGamesForOwner($_SESSION['owner_id']);



include_once('inc.left.php');

$javascript = new Javascript(true);

$tpl_admin_top_content = new Template('admin_top');
$tpl_admin_top_content->set('owner_array', isset($_SESSION['owner_info'])?$_SESSION['owner_info']:'');
$tpl_admin_top = new Template('left_div');
$tpl_admin_top->set('div_title','administration panel');
$tpl_admin_top->set('div_id','admin_top');
$tpl_admin_top->set('content', $tpl_admin_top_content->fetch());

$tpl_div_ins1_content = new Template('data_listing_full');
$tpl_div_ins1_content->set('data_array', $data_array);
$tpl_div_ins1 = new Template('left_div');
$tpl_div_ins1->set('div_title','your games');
$tpl_div_ins1->set('div_id','data_list');
$tpl_div_ins1->set('content', $tpl_div_ins1_content->fetch());

include_once('inc.getleft.php');

$right_bar .= $tpl_admin_top->fetch();
$right_bar .= $tpl_div_ins1->fetch();

$tpl_body->set('left_content', $left_bar);
$tpl_body->set('right_content', $right_bar);

$template->set('title', 'GamersEvents');
$template->set('script', $javascript->getJavascriptString());
$template->set('body_attributes', '');
$template->set('body_content', $tpl_body->fetch());
$template->set('style', $tpl_style->fetch());

echo $template->fetch();
?>