<?php

include_once('config.php');
$debug = new Debug(false);
$debug->debugStatus(true);

$is_admin = true;

$auth = Auth::isLoggedIn();

if (!$auth) {
	if(isset($_POST['username'])){

		$result = Auth::verifyOwner();
		if($result)Auth::setLocalCookie($result['owner_id']);
		$msg = (!$result?'?un='.$_POST['username']:'');
		header('location:'.$_SERVER['PHP_SELF'].$msg);
	}
} else {
	if(isset($_GET['logOut'])) {
		//write login form
		Auth::logOut();
		$auth = false;
		header('location:'.$_SERVER['PHP_SELF']);
	}
}

$loc = isset($_GET['location'])?$_GET['location']:0;
$data_id = isset($_GET['data_id'])?$_GET['data_id']:(isset($_POST['data_id'])?$_POST['data_id']:0);
$instance_id = isset($_GET['instance_id'])?$_GET['instance_id']:(isset($_POST['instance_id'])?$_POST['instance_id']:0);

if($data_id != 0){
	$edit_type = 'd';
	$id = $data_id;
} elseif($instance_id != 0) {
	$edit_type = 'i';
	$id = $instance_id;
} else {
	$edit_type = 'd';
	$id = 0;
}

$location = new Location($loc);

//Time Handling
$start_day_of_week = Site::getTime('start_this_week');

$data = new Data($data_id, $instance_id);

$msg = array();
if(isset($_POST['main_form'])){

    if($edit_type == 'd'){

		if($_POST['id']==0){
        	$data_id = $data->formCreateData();
        	foreach($_POST['platform'] as $platform_id => $v) $data->formCreateInstance($data_id, $platform_id);
        	header('location:?');
        } else {
        	$keys_inst = array_keys($data->data_info['instances']);
        	$keys_post = array_keys($_POST['platform']);
        	$keys_diff = array_diff($keys_post, $keys_inst);
        	$data->formUpdateData();
        	if(!empty($keys_diff)){
        		foreach($keys_diff as $platform_id) $data->formCreateInstance($data_id, $platform_id);
        		$msg[] = 'Availability on a new platform has been added, but important information including release date could not be automated. Please edit this platform information as soon as possible to provide relevant information.';
        	}

        }

    } else {
    	if($_POST['remove_platform']){
    		$data->deleteInstance($id);
        } else {
        	$data->formUpdateData();
        	header('location:?');
        }
    }
}
if($auth){
	$data_array = $data->getAllData('', '', '', 'd.data_id', 'data_title ASC', '', false, 'owner_id = '.$_SESSION['owner_id']);
} else {
    $data_array = array();
}

$javascript = new Javascript($is_admin);

include_once('inc.left.php');

if($edit_type == 'd') $info = $data->data_info;
elseif($edit_type == 'i') $info = $data->instance_info;
else $info = array('title'=>'','price'=>'', 'release_date' => date('Y-m-d H:i:s'), 'description'=>'', 'website'=>'http://', 'image'=>'');

$tpl_admin_top_content = new Template('admin_top');
$tpl_admin_top_content->set('owner_array', isset($_SESSION['owner_info'])?$_SESSION['owner_info']:'');
$tpl_admin_top = new Template('left_div');
$tpl_admin_top->set('div_title','administration panel');
$tpl_admin_top->set('div_id','admin_top');
$tpl_admin_top->set('content', $tpl_admin_top_content->fetch());

$tpl_div_ins1_content = new Template('data_listing');
$tpl_div_ins1_content->set('data_array', $data_array);
$tpl_div_ins1 = new Template('left_div');
$tpl_div_ins1->set('div_title','your games');
$tpl_div_ins1->set('div_id','data_list');
$tpl_div_ins1->set('content', $tpl_div_ins1_content->fetch());

$tpl_select_data_content = new Template('data_listing');
$tpl_select_data_content->set('data_array', $data_array);
$tpl_select_data = new Template('left_div');
$tpl_select_data->set('div_title','your games');
$tpl_select_data->set('div_id','data_list');
$tpl_select_data->set('content', $tpl_div_ins1_content->fetch());

$tpl_login_content = new Template('login_form');
$tpl_login = new Template('left_div');
$tpl_login->set('div_title','please login');
$tpl_login->set('div_id','login');
$tpl_login->set('content', $tpl_login_content->fetch());

$tpl_div_ins2_content = new Template('edit');
$tpl_div_ins2_content->set('categories', $data->getCategories());
$tpl_div_ins2 = new Template('left_div');
$tpl_div_ins2->set('div_title','edit data');
$tpl_div_ins2->set('div_id','normal');

if($edit_type=='d'){
	$tpl_div_ins2_content->set('genres', $data->getGenres());
	$tpl_div_ins2_content->set('owners', $data->getOwners());
}

$tpl_div_ins2_content->set('ratings', $data->getRatings());
$tpl_div_ins2_content->set('id', $id);
$tpl_div_ins2_content->set('edit_type', $edit_type);
$tpl_div_ins2_content->set('content', $info);
$tpl_div_ins2_content->set('data_content', $data->data_info);
$tpl_div_ins2_content->set('category_array', $data->getFullCategories());
$tpl_div_ins2_content->set('msg', $msg);

if($id!=0)$tpl_div_ins2_content->set('release_end_time', $data->getReleaseEndTime());
else $tpl_div_ins2_content->set('release_end_time', false);

if($id!=0)$tpl_div_ins2_content->set('release_time', $data->getReleaseTime());
else $tpl_div_ins2_content->set('release_time', false);

$tpl_div_ins2->set('content', $tpl_div_ins2_content->fetch());

include_once('inc.getleft.php');



if($auth){
	$right_bar .= $tpl_admin_top->fetch();
	
} else {
	$right_bar .= $tpl_login->fetch();
}
//foreach($right_bar_array as $r) $right_bar .= $r;


$tpl_body->set('left_content', $left_bar);
$tpl_body->set('right_content', $right_bar);

$template->set('title', 'GamersEvents');
$template->set('script', $javascript->getJavascriptString());
$template->set('body_attributes', '');
$template->set('body_content', $tpl_body->fetch());
$template->set('style', $tpl_style->fetch());

//

echo $template->fetch();
?>