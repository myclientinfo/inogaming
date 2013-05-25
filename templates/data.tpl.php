<div style="width: 100%;">
	<div id="data_image">

	<?php
	if(!empty($instance_array)){
		$image_file = isset($instance_array['instance_image'])&&$instance_array['instance_image']!=''?$instance_array['instance_image']:'boxshot_'.$instance_array['instance_category_short'].'.jpg';
	} else {
		$image_file = isset($data_array['data_image'])&&$data_array['data_image']!=''?$data_array['data_image']:'boxshot_template.jpg';
	}
	if($image_file != ''){
		//print '<img src="/images/boxshots/'.$image_file.'" id="boxshot_image">';
	}
	?>
	</div>

	<div id="data_details">
		<span class="content_title"><?=$data_array['data_title']?></span><br><br>
		<?php if($instance_id){ 
		?>
		<b><?php echo $instance_array['instance_title'] ?></b><br><br>
		<div id="details_box">
        <span class="top_label" id="label"><b>From:</b></span> <?php echo Site::getTimeString(Data::getFirstTime($instance_array['times']), Data::getFirstTime($instance_array['times'])) ?><br>
		<span class="top_label" id="label"><b>Category:</b></span> <?php echo $instance_array['instance_category'] ?><br>
		<span class="top_label" id="label"><b>State:</b></span> <?php echo $instance_array['state'] ?><br>
		<span class="top_label" id="label"><b>City/region:</b></span> <?php echo $instance_array['city'] ?><br>
		<span class="top_label" id="label"><b>Venue:</b></span> <?php echo $instance_array['instance_venue'] ?><br>
		<span class="top_label" id="label"><b>Address:</b></span> <div style="float: left; width: 400px;"><?php echo nl2br($instance_array['instance_address']) ?></div><br>
		<div style="clear: both;"></div>
		<span class="top_label" id="label"><b>Cost:</b></span> <?=(isset($instance_array['instance_price'])?$instance_array['instance_price']:'TBA')?><br>
		<?php } else { ?>
		<span class="top_label" id="label"><b>Website:</b></span> <?=(isset($data_array['data_website'])&&$data_array['data_website']!=''?'<a href="'.$data_array['data_website'].'" target="_blank">Official Site</a>':'TBA')?><br>
		<?php } ?>
        </div>

	</div>
	<div id="data_publisher" style="display: none;">
		<div id="data_publisher_info">
		<div style="padding: 3px;">distributed in australia by:</div>
		<div id="data_publisher_info_inner">
		<?
		if(file_exists($_SERVER['DOCUMENT_ROOT'].'/images/dist_'.$owner_array['username'].'.gif')){
			print '<img src="/images/dist_'.$owner_array['username'].'.gif">';
		}
		?>
		<b><?=$owner_array['company_name']?></b><br>
		<? if($owner_array['url']!=''){ ?><a href="<?=$owner_array['url']?>" target="_blank"><?=$owner_array['url']?></a><br><? } ?>
		<? if($owner_array['location']!=''){ ?><?=$owner_array['location']?><br><? } ?>
		</div>
		</div>
	</div>
	<div style="clear: both;"></div>
</div><br>
<div style="padding:10px;">
<?php
if($instance_id){
	echo nl2br(stripslashes($instance_array['instance_description']));
} else {
	echo nl2br(stripslashes($data_array['data_description']));
}
?>
</div>
<?php


if(isset($_GET['data_title'])){
?>
<script>

function subscribe(){

	new Ajax.Request('/api/add_subscriber.php', {
		method: 'post',
		parameters: $('subscribe_form').serialize(true),
		onSuccess: function(response){
			if(response.responseText){
				$('form_bits').update('Thank you for your interest. You will informed of future updates.');
			}
		}
	});
}

</script>

<form id="subscribe_form" action="" method="post" style="text-align: center; width: 680px; margin-top: 15px; border: 1px solid black; padding: 10px;">
<strong>Subscribe for updates</strong><input type="hidden" name="data_id" id="data_id" value="<?=$data_array['data_id']?>">&nbsp;&nbsp;&nbsp;
<input type="text" name="email" id="email" style="height: 12px; font-size: 9px; width: 180px; border: 1px solid black;">&nbsp;&nbsp;&nbsp;
<input type="image" name="submit" src="/images/button_subscribe.gif" onclick="subscribe();return false;" style="position: relative; top: 3px; left: -3px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<img src="/images/icon_rss.jpg" style="position: relative; top: 3px; left: -3px;"> <a href="/api/changes.php?data_id=<?=$data_array['data_id']?>">RSS for this game</a>
</form>
<?php
}
?>

<div id="form_bits"></div>