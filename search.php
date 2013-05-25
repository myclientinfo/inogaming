<?php

include_once('config.php');
$data = new Data(0,0);

if(!empty($_POST) || isset($_GET['s'])){
    $search_string = '';
    if(isset($_REQUEST['search_text'])){
        $search_string .= ' AND d.title LIKE "%'.$_REQUEST['search_text'].'%"';
    }
    if(isset($_POST['category'])&&!empty($_POST['platform'])){
        $i=0;
        $search_string .= ' AND c.category_short IN(';
        foreach($_POST['category'] as $u => $p){
            $search_string .= ($i==0?'':', ').'"'.$u.'"';
            $i++;
        }
        $search_string .= ')';
    }
    if(substr($search_string, 0, 5) == ' AND ') $search_string = substr($search_string, 5);
    //$GLOBALS['debug']->printr($search_string);
    $order_by = isset($_REQUEST['order_by'])?$_REQUEST['order_by']:'data_title ASC';
    $from = isset($_REQUEST['from'])?$_REQUEST['from']:date('Y-m-d H:i:s');
    $to = isset($_REQUEST['to'])?$_REQUEST['to']:date('');
    $location = '';
    $GLOBALS['debug']->printr($_POST);
    $search_result = $data->getAllData($location, $from, $to, 'instance_id', $order_by, '', false, $search_string);
} else {
    $search_result = array();
}

include_once('inc.left.php');

$tpl_search_content = new Template('search_form');
$tpl_search_content->set('category_array', $data->getFullCategories());
$tpl_search = new Template('left_div');
$tpl_search->set('div_id','data_list');
$tpl_search->set('div_title','search for content');
$tpl_search->set('content', $tpl_search_content->fetch());

$tpl_result_content = new Template('search');
$tpl_result_content->set('content', $search_result);
$tpl_result = new Template('left_div');
$tpl_result->set('div_id','search');
$tpl_result->set('div_title','search results');
$tpl_result->set('content', $tpl_result_content->fetch());

include_once('inc.getleft.php');

$right_bar .= $tpl_search->fetch();
$right_bar .= $tpl_result->fetch();

$tpl_body->set('left_content', $left_bar);
$tpl_body->set('right_content', $right_bar);

$template->set('title', 'GamersEvents');
$template->set('body_attributes', '');
$template->set('script', $javascript->getJavascriptString());
$template->set('body_content', $tpl_body->fetch());
$template->set('style', $tpl_style->fetch());

//$debug->printr($data->getDataByDate($getloc));

echo $template->fetch();

?>