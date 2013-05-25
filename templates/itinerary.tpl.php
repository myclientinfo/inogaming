<?php
$time_array = Data::timeDataToDateData($instance_array['times']); //this might not work... at all
$i=0;
?>
<table id="itinerary">
<?php foreach($time_array as $day => $items){ $j=0; ?>
<tr><td colspan="5"><b><?php echo Site::getItinararyTitleString($items)?></b></td></tr>
<?php foreach($items as $item){ ?>
<tr>
    <td width="40" valign="top"><?php echo date('g:ia', strtotime($item['instance_start_time']))?></td>
    <td width="10">-</td>
    <td width="40" valign="top"><?php echo date('g:ia', strtotime($item['instance_end_time']))?></td>
    <td valign="top"><b><?php echo stripslashes($item['time_title'])?></b></td>
    <td valign="top"><?php echo stripslashes($item['time_description'])?></td>
</tr>
<?php $j++; } ?>
<tr><td colspan="5"><br></td></tr>
<?php $i++; } ?>
</table>
