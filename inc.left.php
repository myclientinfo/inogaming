<?php
$location = new Location(0);
$today = Site::getTime('start_day', '', 'MYSQL');
$javascript = new Javascript();

$arbit_string = '';
if(isset($_GET['state'])){
    $arbit_string .= 'st.state_short="'.strtoupper($_GET['state']).'"';
    if(isset($_GET['region'])){
        $arbit_string .= ' AND LOWER(ci.city) = "'.strtolower($_GET['region']).'"';
    }
}

$data_listing = $data->getAllData('', $today, '', 'i.instance_id', 'start_time ASC', '', false, $arbit_string);
$data_listing = $data->allDataToDateData($data_listing);




ksort($data_listing);

$category_array = $data->getCategories();
$location_array = $location->getCapitalLocations();

if(isset($_GET['state'])){
	$data_listing_left = $data->getAllData('', $today, '', 'i.instance_id', 'start_time ASC', '', false, '');
	$data_listing_left = $data->allDataToDateData($data_listing_left);
	ksort($data_listing_left);
} else {
	$data_listing_left = $data_listing;
}

$template = new Template('index');
$tpl_style = new Template('style');
$tpl_body = new Template('main_body');

$tpl_div_new_data = new Template('left_div');
$tpl_div_recent_releases = new Template('left_div');
$tpl_div_upcoming_releases = new Template('left_div');
$tpl_div_logo = new Template('logo');
$tpl_div_left_ad = new Template('left_ad');

$tpl_div_menu = new Template('menu');
$tpl_div_ad = new Template('right_ad');

$tpl_div_new_data->set('div_id','l_news');
$tpl_div_new_data->set('div_title','new games');
$tpl_div_new_data->set('content', $data->getNew(10));

$tpl_div_changes_content = new Template('upcoming_data');
$tpl_div_changes_content->set('content', $data->getDataByType());
$tpl_div_changes = new Template('left_div');
$tpl_div_changes->set('div_id','changes');
$tpl_div_changes->set('div_title','next events');
$tpl_div_changes->set('content', $tpl_div_changes_content->fetch());

$tpl_div_upcoming_data_content = new Template('upcoming_data_left');
$tpl_div_upcoming_data_content->set('content', $data_listing_left);
$tpl_div_upcoming_data_content->set('platform_colours', Site::getPlatformColours());
$tpl_div_upcoming_data = new Template('left_div');
$tpl_div_upcoming_data->set('div_id','upcoming');
$tpl_div_upcoming_data->set('div_title','upcoming events');
$tpl_div_upcoming_data->set('content', $tpl_div_upcoming_data_content->fetch());


$tpl_div_search_content = new Template('search_content');
$tpl_div_search_content->set('location_array', $location_array);
$tpl_div_search_content->set('category_array', $category_array);
$tpl_div_search = new Template('left_div');
$tpl_div_search->set('div_id','top_content');
$tpl_div_search->set('div_title','find an event');
$tpl_div_search->set('content', $tpl_div_search_content->fetch());

?>