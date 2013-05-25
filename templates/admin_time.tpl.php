<?php
$to_minute = isset($_POST['time_to_minute']) ? $_POST['time_to_minute'] : ( $time_id != 0 ? date('i',strtotime($info['end_time'])) : '00' ) ;
$from_minute = isset($_POST['time_from_minute']) ? $_POST['time_from_minute'] : ( $time_id != 0 ? date('i',strtotime($info['start_time'])) : '00' ) ;
$to_hour = (int)isset($_POST['time_to_hour']) ? $_POST['time_to_minute'] : ( $time_id != 0 ? date('G',strtotime($info['end_time'])) : '0' ) ;
$from_hour = (int)isset($_POST['time_from_hour']) ? $_POST['time_from_hour'] : ( $time_id != 0 ? date('G',strtotime($info['start_time'])) : '0' ) ;

$minutes_array = array('00','15','30','45');
?>
<?php echo $formscript ?>
<script src="/js/date_picker.js"></script>
<script>
function setTimes(){
    $('start_time').value = $F('start_time').substr(0,10) + ' ' + $F('time_from_hour') + ':' + $F('time_from_minute') + ':00';
    $('end_time').value = $F('end_time').substr(0,10) + ' ' + $F('time_to_hour') + ':' + $F('time_to_minute') + ':00';
}

function checkTimes(){
	if($('start_time').value > $('end_time').value) $('end_time').value = $('start_time').value;
}
</script>
<h4>Manage Itinerary</h4>

Add or edit activities within your event. Times may overlap, but in order to be a valid event and show up in our listings you <b>must</b> create at least one itinerary item. This can be a single time period spanning from the start of your event to the end, or you may specify individual activities.<br><br>


<div id="data_form">
<div class="formDark" id="box_data">
<a href="?data_id=<?php echo $data_id ?>"><img src="/images/button_edit.gif" border="0" name="submit" style="float: right; " value="Log In"></a>
<label for="data" style="float: left;">Current Overview:</label>
<?php echo $data_title?>
</div>
</div>
<br><br>

<div id="data_form">
<div class="formDark" id="box_instance">
<a href="?data_id=<?php echo $data_id ?>&instance_id=<?php echo $instance_id ?>"><img src="/images/button_edit.gif" border="0" name="submit" style="float: right; "></a>
<label for="data" style="float: left;">Current Event:</label>
<?php echo $parent_info['instance_title']?>
</div>
</div>
<br><br>

<?php if(isset($_GET['saved'])){ ?><div id="admin_message">Itinerary Item Saved</div><br><br><?php } ?>

<form action="" method="post" id="data_form" onsubmit="checkTimes();return validate()">

<input type="hidden" name="next_step" id="next_step" value="0">
<input type="hidden" name="finished" id="finished" value="0">
<input type="hidden" name="data_id" value="<?php echo $data_id ?>">
<input type="hidden" name="instance_id" value="<?php echo $instance_id ?>">
<input type="hidden" name="time_id" value="<?php echo $time_id ?>">
<input type="hidden" name="start_time" id="start_time" value="<?php echo isset($_POST['start_time'])? $_POST['start_time'] : ( isset($info['start_time']) &&  $info['start_time']!='' ? $info['start_time'] : '' ) ?>">
<input type="hidden" name="end_time" id="end_time" value="<?php echo isset($_POST['end_time'])? $_POST['end_time'] : ( isset($info['end_time']) &&  $info['end_time']!='' ? $info['end_time'] : '' ) ?>">

<div class="formDark" id="box_time_title"><label for="time_title">Event title:</label><input type="text" id="time_title" name="time_title" value="<?php echo isset($_POST['time_title'])? $_POST['time_title'] : ( isset($info['time_title']) &&  $info['time_title']!='' ? $info['time_title'] : '' ) ?>"></div>

<div class="formDark" id="box_time_description"><label for="time_description">Description:</label><input type="text" id="time_description" name="time_description" value="<?php echo isset($_POST['time_description'])? $_POST['time_description'] : ( isset($info['time_description']) &&  $info['time_description']!='' ? $info['time_description'] : '' ) ?>"></div>

<div class="formDark" id="box_time_from"><label for="start_time_display">From:</label>
    <div id="start_time_display" style="float: left;padding: 0px;"><?php echo isset($_POST['start_time'])? $_POST['start_time'] : ( isset($info['start_time']) &&  $info['start_time']!='' ? date('F j, Y', strtotime($info['start_time'])) : 'Click to set date &raquo;' ) ?></div>
    <img id="start_time_calendar" src="/images/calendar.png" style="float: left; margin-top: 3px; margin-left: 3px; cursor: hand; cursor: pointer;" onclick="displayDatePicker('start_time', this, 'ymd', '-');return false;">
    <select id="time_from_hour" name="time_from_hour" style="float: left; margin-left: 10px;">
    <?php for($h=0;$h<=23;$h++){ ?><option value="<?php echo str_pad($h, 2, '0', STR_PAD_LEFT) ?>"<?php echo $from_hour == $h ? ' selected' : '' ?>><?php echo $h ?></option><?php } ?>
    </select>
    <div style="float: left;padding: 5px; padding-bottom: 0px; margin-bottom:0px;position: relative; top: -5px;">:</div>
    <select id="time_from_minute" name="time_from_minute" style="float: left;">
    <?php foreach($minutes_array as $m){ ?><option value="<?php echo $m ?>"<?php echo $from_minute == $m ? ' selected' : '' ?>><?php echo $m ?></option><?php } ?>
    </select>
</div>

<div class="formDark" id="box_time_to"><label for="end_time_display">To:</label>
    <div id="end_time_display" style="float: left; padding: 0px;"><?php echo isset($_POST['end_time'])? $_POST['end_time'] : ( isset($info['end_time']) &&  $info['end_time']!='' ? date('F j, Y', strtotime($info['end_time'])) : 'Click to set date &raquo;' ) ?></div>
    <img id="end_time_calendar" src="/images/calendar.png" style="float: left; margin-top: 3px; margin-left: 3px; cursor: hand; cursor: pointer;" onclick="displayDatePicker('end_time', this, 'ymd', '-');return false;">
    <select id="time_to_hour" name="time_to_hour" style="float: left;margin-left: 10px;">
    <?php for($h=0;$h<=23;$h++){ ?><option value="<?php echo str_pad($h, 2, '0', STR_PAD_LEFT) ?>"<?php echo $to_hour == $h ? ' selected' : '' ?>><?php echo $h ?></option><?php } ?>
    </select>
    <div style="float: left; padding: 5px;padding-bottom: 0px; margin-bottom:0px; position: relative; top: -5px;">:</div>
    <select id="time_to_minute" name="time_to_minute" style="float: left;">
    <?php foreach($minutes_array as $m){ ?><option value="<?php echo $m ?>"<?php echo $to_minute == $m ? ' selected' : '' ?>><?php echo $m ?></option><?php } ?>
    </select>
</div>

<?php if($data_id != 'new'){ ?>
<a href="admin_edit.php?data_id=<?php echo $data_id ?>&instance_id=<?php echo $instance_id ?>&time_id=<?php echo $time_id ?>&delete=time" onclick="return confirmDelete('event');"><input type="image" src="/images/button_delete.gif" style="float: left; margin-top: 6px; border-width: 0px;"></a>
<?php } ?>

<input type="image" src="/images/button_saveandaddanother.gif" onclick="nextStep();setTimes();" style="float: right; margin-left: 10px; margin-top: 6px;">
<input type="image" src="/images/button_saveandfinish.gif" onclick="setTimes();finished();" style="float: right; margin-top: 6px;">

</form>
<br><br>