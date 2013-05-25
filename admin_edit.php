<?php

include_once('config.php');
$debug = new Debug(false);
$debug->debugStatus(true);

$is_admin = true;

$auth = Auth::isLoggedIn();
if (!$auth) header('location:admin.html');

$data_id = isset($_GET['data_id'])?$_GET['data_id']:(isset($_POST['data_id'])?$_POST['data_id']:0);
$instance_id = isset($_GET['instance_id'])?$_GET['instance_id']:(isset($_POST['instance_id'])?$_POST['instance_id']:0);
$time_id = isset($_GET['time_id'])?$_GET['time_id']:(isset($_POST['time_id'])?$_POST['time_id']:0);

if(isset($_GET['delete'])){
    if($_GET['delete'] == 'time'){
        Data::deleteTime($time_id);
        header('location:admin_edit.html?data_id='.$data_id.'&instance_id='.$instance_id.'&deleted');
    }elseif($_GET['delete'] == 'instance'){
        Data::deleteInstance($instance_id);
        header('location:admin_edit.html?data_id='.$data_id.'&deleted');
    }elseif($_GET['delete'] == 'data'){
        Data::deleteData($data_id);
        header('location:admin_edit.html');
    }
}

if(!empty($_POST)){
    if($time_id !== 0){
        $time_id = Data::saveTime();
        if($_POST['next_step']==1) header('location:admin_edit.html?data_id='.$data_id.'&instance_id='.$instance_id.'&time_id=new');
        else header('location:admin_edit.html?data_id='.$data_id.'&instance_id='.$instance_id.'&time_id='.$time_id.'&saved');
    } elseif($instance_id !== 0){
        $instance_id = Data::saveInstance();
        Data::saveContact($instance_id);
        if($_POST['next_step']==1) header('location:admin_edit.html?data_id='.$data_id.'&instance_id='.$instance_id.'&time_id=new');
        elseif($_POST['next_step']==0) header('location:admin_edit.html?data_id='.$data_id.'&instance_id='.$instance_id.'&saved');
    } elseif($data_id!== 0){
        $data_id = Data::saveFormData();
        if($_POST['next_step']==1) header('location:admin_edit.html?data_id='.$data_id.'&instance_id=new');
        elseif($_POST['finished']==1) header('location:admin_edit.html');
        elseif($_POST['next_step']==0) header('location:admin_edit.html?data_id='.$data_id.'&saved');
    }
}

//image handling

if(!empty($_FILES)){
    $file_name = Data::uploadImage($instance_id);
} else $file_name = 'blank.jpg';

$data = new Data($data_id, $instance_id);

include_once('inc.left.php');

$tpl_admin_top_content = new Template('admin_top');
$tpl_admin_top_content->set('owner_array', isset($_SESSION['owner_info'])?$_SESSION['owner_info']:'');
$tpl_admin_top = new Template('left_div');
$tpl_admin_top->set('div_title','administration panel');
$tpl_admin_top->set('div_id','admin_top');
$tpl_admin_top->set('content', $tpl_admin_top_content->fetch());


$info = array();
if ($time_id !== 0) {
	$info = $time_id === 'new' ? array() : $data->getTime($time_id);
	$parent_info = $data->instance_info;
	$tpl_admin_edit_content = new Template('admin_time');
	$tpl_admin_edit_content->set('data_title', $data->data_info['data_title']);
} elseif($instance_id !== 0) {
	$info = $data->instance_info;
	$parent_info = $data->data_info;
	if(empty($info)&&$instance_id !== 'new') $info = $data->getInstance($instance_id);
	if(!isset($info['times'])&&$instance_id != 'new') $info['times'] = $data->getTimes($instance_id);
	$tpl_admin_edit_content = new Template('admin_instance');
	$tpl_admin_edit_content->set('category_array', $category_array);
	$tpl_admin_edit_content->set('contact_array', $data->getContact($instance_id));
	$tpl_admin_edit_content->set('location_array', $location_array);
} elseif($data_id !== 0 || $data_id === 'new' ) {
	$info = $data->data_info;
	if(!isset($info['instances'])&&$data_id != 'new') $info['instances'] = $data->getInstances($data_id);
	$parent_info = array();
	$tpl_admin_edit_content = new Template('admin_data');
}	elseif($data_id === 0 && $instance_id === 0 && $time_id === 0) {
	$info = $data->getDataForOwner($_SESSION['owner_id']);
	$tpl_admin_edit_content = new Template('admin_data_select');
	$parent_info = array();
}

$tpl_admin_edit_script = new Template('formscript');
$tpl_admin_edit_script->set('time_id', $time_id);
$tpl_admin_edit_script->set('instance_id', $instance_id);
$tpl_admin_edit_script->set('data_id', $data_id);
$tpl_admin_edit_script->set('owner_array', isset($_SESSION['owner_info'])?$_SESSION['owner_info']:'');
$tpl_admin_edit_content->set('formscript', $tpl_admin_edit_script->fetch());
$tpl_admin_edit_content->set('info', $info);
$tpl_admin_edit_content->set('parent_info', $parent_info);
$tpl_admin_edit_content->set('data_id', $data_id);
$tpl_admin_edit_content->set('instance_id', $instance_id);
$tpl_admin_edit_content->set('time_id', $time_id);
$tpl_admin_edit = new Template('left_div');
$tpl_admin_edit->set('div_title','edit data');
$tpl_admin_edit->set('div_id','normal');
$tpl_admin_edit->set('content', $tpl_admin_edit_content->fetch());

$javascript = new Javascript($is_admin);

include_once('inc.getleft.php');


$right_bar .= $tpl_admin_top->fetch();
$right_bar .= $tpl_admin_edit->fetch();

$tpl_body->set('left_content', $left_bar);
$tpl_body->set('right_content', $right_bar);

$template->set('title', 'GamersEvents');
$template->set('script', $javascript->getJavascriptString());
$template->set('body_attributes', '');
$template->set('body_content', $tpl_body->fetch());
$template->set('style', $tpl_style->fetch());

echo $template->fetch();
?>