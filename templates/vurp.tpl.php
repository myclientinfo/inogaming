<?php
$i=0;
foreach($content as $k => $value){
?>
<div style="width: 35px;font-weight: bold; float: left; color: <?=$platform_colours[$value['platform']][0]?>;"><?=strtoupper($value['platform'])?></div><div style="width: 40px;float: left; "><?php echo date('d M',strtotime($value['release_date']))?></div> <a href="<?php echo $value['link']?>"><?=$value['title']?></a></b><br>
<?php
	$i++;
}
?>
<div style="clear: both;"></div>