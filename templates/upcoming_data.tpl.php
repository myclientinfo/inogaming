<?php
$i=0;
//$GLOBALS['debug']->printr($content);
if(is_array($content)){
foreach($content as $k => $value){
?>
<?=($i!=0?'<div class="change_divider"></div>':'')?>
<div class="upcomingEventType">NEXT <?=strtoupper($k)?></div>
<b class="upcomingEventTitle"><a href="/<?php echo strtolower($value['state_short']) ?>/<?php echo strtolower(str_replace(' ','_',$value['city'])) ?>/<?php echo Site::createLinkFromTitle($value['data_title']) ?>/<?php echo $value['instance_id'] ?>_<?php echo Site::createLinkFromTitle($value['instance_title']) ?>.html"><?=$value['instance_title']?></a></b><br>
<?=Site::getTimeString($value['start_time'], $value['end_time'])?>
<br>
<br>
<?php
	$i++;
}
}
?>