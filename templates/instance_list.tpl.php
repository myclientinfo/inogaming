<?php 
foreach($instance_array as $value){ 

$first_date = Data::getFirstTime($value['times']);
?>
<div>
<div style="margin: 2px;padding: 3px;margin-top:0px; background-color:#114E08; color: white; ">
<a href="/<?php echo strtolower($value['state_short']) ?>/<?php echo strtolower(str_replace(' ','_',$value['city'])) ?>/<?php echo strtolower(str_replace(' ','_',$data_array['data_title'])) ?>/<?php echo $value['instance_id'] ?>_<?php echo Site::createLinkFromTitle($value['instance_title']) ?>.html"><?php echo $value['instance_title'] ?></a> - <?php echo $value['instance_category'] ?><br>
<?php echo Site::getTimeString($first_date,$first_date) ?><br>
<?php echo stripslashes($value['instance_description']) ?>
</div>
</div>
<?php } ?>
