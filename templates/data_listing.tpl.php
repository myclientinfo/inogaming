
<script>
function limitTo(val){
	var chosen_class = 'al_'+val;
	var all_links = $$('a.al', 'editDataList_list');
	var selected_links = $$('a.'+chosen_class, 'editDataList_list');
	
	all_links.each( function(link){
		if(val != 'all') link.hide();
	});
	if(val == 'all'){
		all_links.each( function(link){
			link.show();
		});
	} else {
		selected_links.each( function(link){
			link.show();
		});
	}
}

</script>
<?
//

$alphabet_array = array ("all","num", "a", "b", "c", "d", "e", "f", "g", "h", "i",
					"j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
					"v", "w", "x", "y", "z");
	?>
	<div id="editDataList">
	<div style="width: 100%;" id="editDataList_list">
	<div style="float:left; font-weight: bold;">::Click to edit::</div>
	<div style="float:right;cursor: hand; cursor: pointer;" onclick="create_new();"><!--::Create New Title::--><img src="/images/button_addnewgame.gif"></div>
	<div style="clear:both;"></div>
	</div>
	<div id="al_list_box">
	<?php
	if(!empty($data_array)){
	foreach($data_array as $d){
	?>
	<!--<div class="dataListing"><form action="" method="post" style="margin: 0px; style: inline; float: left;"><input type="hidden" name="type" value="d"><input type="hidden" name="data_id" value="<?=$d['data_id']?>"><input type="submit" value="edit" style="width: 30px;" onClick="getData(<?=$d['data_id']?>);return false;"></form> <b><?=$d['title']?></b></div>-->
	<a style="cursor: pointer; cursor: hand;" class="al al_<?
	//print "".strtolower(substr($d['title'],0,1))."-".(int)strtolower(substr($d['title'],0,1));
	if((int)strtolower(substr($d['title'],0,1)) != 0){
		echo 'num';
	} else {
		echo strtolower(substr($d['title'],0,1));
	}
		
	?>" onClick="getData(<?=$d['data_id']?>);return false;"><?=$d['title']?><br></a>
	<?php
	}
	}
	?>
	<div style="clear: both;"></div>
	</div>
	<br>
	<?php foreach($alphabet_array as $al){ ?>
	<a href="" onclick="limitTo('<?=$al?>'); return false;" class="al_list" style="border: 0px solid white; border-right-width: <?=($al=='z'?'0':'1')?>px;"><?=($al=='num'?'0-9':$al)?></a>
	<?php } ?>
	</div>
	<br><br>
	<?php
//}

?>