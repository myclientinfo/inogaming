
<?
//

	?>
	<div id="editDataList">
	<?php
	if(!empty($data_array)){
	foreach($data_array as $d){
	?>
	<!--<div class="dataListing"><form action="" method="post" style="margin: 0px; style: inline; float: left;"><input type="hidden" name="type" value="d"><input type="hidden" name="data_id" value="<?=$d['data_id']?>"><input type="submit" value="edit" style="width: 30px;" onClick="getData(<?=$d['data_id']?>);return false;"></form> <b><?=$d['title']?></b></div>-->
	<a href="admin_edit.html?data_id=<?=$d['data_id']?>"><img src="/images/button_edit.gif" border="0"></a> <a href="/games/<?=Site::createLinkFromTitle($d['title'])?>/" target="_blank"><img src="/images/button_view.gif" border="0"></a> <?=$d['title']?><br>
	<?php
	}
	}
	?>

	</div>
	<?php
//}

?>