<?php
$i=0;
foreach($content as $k => $value){
?>
<?=($i!=0?'---------------------------------':'')?>
<div class="upcomingEventType">NEXT <?=strtoupper($k)?> TITLE</div>
<b class="upcomingEventTitle"><a href="/data.php?instance_id=<?=$value['instance_id']?>.html"><?=$value['data_title']?></a></b><br>
<?=Site::getTimeString($value['start_time'], $value['end_time'])?>
<br>
<br>

<?php
	$i++;
}
?>