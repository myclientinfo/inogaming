<?php


?>
<script>
var date_mode = 'advanced';
function saveData(){}
function add_log(){}
</script>


<form action="" method="post" id="search_form">
<input type="hidden" name="edit_type" id="edit_type" value="s">
<label for="search_text">Search for: </label><input type="text" name="search_text" id="search_text" value="<?=isset($_POST['search_text'])?$_POST['search_text']:''?>"><br><br>
<label for="platforms_box">Type: </label><div id="platforms_box" style="float: left; width: 630px;border: 0px solid white;">
	<?php $i=0; foreach($category_array as $k => $v){ $i++; ?>
		<input type="checkbox" name="category[<?php echo $v['category_short'] ?>]" id="category_<?php echo $v['category_short'] ?>"><label for="category_<?php echo $v['category_short'] ?>"><?php echo $v['category'] ?></label><?php echo $i==5?'<br>':''?>
	<?php } ?>

</div><br><br>
<!--<label for="from">From</label><input type="text" name="from" id="from"><div style="float: left; width: 20px; text-align: center;font-weight: bold;"> to </div><input type="text" name="to" id="to"><br><br>-->

<label for="from">From</label><div style="float: left; width: 630px;border: 0px solid white;"><div id="start_time_display"><?php echo isset($_REQUEST['from']) ? Site::getTimeString($_REQUEST['from'], $_REQUEST['from']) : 'Click to set time &raquo;' ?></div>
		<img id="start_time_calendar" src="/images/calendar.png" style="float: left; margin-top: 3px; margin-left: 3px; cursor: hand; cursor: pointer;" onclick="displayDatePicker('start_time', this, 'ymd', '-');return false;">
		<div style="float: left; padding: 5px; color: white;">to</div>
		<div id="end_time_display"><?php echo isset($_REQUEST['to']) ? Site::getTimeString($_REQUEST['to'], $_REQUEST['to']) : 'Click to set time &raquo;' ?></div>
		<img src="/images/calendar.png" style="float: left; margin-top: 3px; margin-left: 3px; cursor: hand; cursor: pointer;" onclick="displayDatePicker('end_time', this, 'ymd', '-');return false;">
		</div><br><br>
<input type="hidden" id="start_time" name="start_time" value="<?php echo isset($_REQUEST['from'])?$_REQUEST['from']:'' ?>">
<input type="hidden" id="end_time" name="end_time" value="<?php echo isset($_REQUEST['to'])?$_REQUEST['to']:'' ?>">
<label for="order_by">Sort by:</label>
<select name="order_by" id="order_by"><option value='instance_title ASC'<?php isset($_POST['order_by']) && $_POST['order_by'] == 'instance_title ASC' ? ' selected' : '' ?>>Title</option><option value="t.start_time ASC"<?php isset($_POST['order_by']) && $_POST['order_by'] == 't.start_time ASC' ? ' selected' : '' ?>>Date</option><option value="category ASC"<?php isset($_POST['order_by']) && $_POST['order_by'] == 'category ASC' ? ' selected' : '' ?>>Category</option></select><br>
<div style="text-align: right;">
<input type="image" src="/images/button_search.gif">
</div>
</form>