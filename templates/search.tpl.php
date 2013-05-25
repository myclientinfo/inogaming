<?php
//$GLOBALS['debug']->printr($_POST);
if(empty($content)){
	if(empty($_POST)&& !isset($_GET['s'])){
		echo "";
	} else {
		echo "There are no records that match this search.";
	}
} else {
//$GLOBALS['debug']->printr($content);
	foreach($content as $d){
		$description = $d['instance_description']!=''?$d['instance_description']:$d['data_description'];
		if(strlen($description)>127){
			$description = substr($description,0,130).'...';
		}
		?>
<b class="mainUpcomingTitle"><a href="/<?php echo strtolower($d['state_short']) ?>/<?php echo strtolower($d['city']) ?>/<?=Site::createLinkFromTitle($d['data_title'])?>/<?=$d['instance_id']?>_<?=Site::createLinkFromTitle($d['instance_title'])?>.html"><?=$d['instance_title']?></a></b> (<?=$d['category']?>) <i><?php echo Site::getTimeString($d['start_time'], $d['end_time']) ?></i><br>
<?=($description!='')?$description.'<br>':''?>
<br>

<?php
	}
	//$GLOBALS['debug']->printr($content);
}
?>