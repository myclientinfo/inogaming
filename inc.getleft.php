<?php
$tpl_body->set('menu_content', $tpl_div_menu->fetch());
$tpl_body->set('top', $tpl_div_search->fetch());

//$right_bar .= $tpl_div_search->fetch();
$admin_array = array('/admin_edit.php','/admin_edit.html','/admin.php','/admin.html');
$left_bar = '';
$right_bar ='';
$left_bar .= $tpl_div_changes->fetch();
if(!in_array($_SERVER['PHP_SELF'], $admin_array)){
	//$left_bar .= $tpl_div_left_ad->fetch();
}
//$left_bar .= $tpl_div_latest->fetch();
$left_bar .= $tpl_div_upcoming_data->fetch();
//$left_bar .= $tpl_vurp->fetch();
//$left_bar .= $tpl_div_rss->fetch();

if(isset($tpl_div_main_content)) $right_bar .= $tpl_div_main_content->fetch();
?>